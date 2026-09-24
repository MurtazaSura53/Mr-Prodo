<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleService
{
    //-------------------------------------------------------------------
    //  READ SALE
    //-------------------------------------------------------------------
    public function paginated(): LengthAwarePaginator
    {
        return  Sale::with(['customer', 'saleItems.product'])->latest()->paginate(10);
    }
    public function get(Sale $sale): Sale
    {
        return $sale->load('saleItems.product');
    }
    //-------------------------------------------------------------------
    //  CREATE SALE
    //-------------------------------------------------------------------
    public function create(array $fields): mixed
    {
        return DB::transaction(function () use ($fields) {
            //create or update customer or if email not provided set it null (Walk in Customer)
            if ($fields['customer_email']) {
                $customer = Customer::where('email', $fields['customer_email'])->first();
                if ($customer) {
                    $customer->update([
                        'name' => $fields['customer_name'] ?? $customer->name,
                        'phone' => $fields['customer_phone'] ?? $customer->phone,
                    ]);
                } else {
                    $customer = Customer::create([
                        'user_id' => Auth::id(),
                        'name' => $fields['customer_name'] ?? null,
                        'email' => $fields['customer_email'] ?? null,
                        'phone' => $fields['customer_phone'] ?? null,
                    ]);
                }
            } else {
                $customer = null;
            }

            $productIds = array_column($fields['sale_items'], 'product_id');
            $products = Product::whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            //preparing saleItems data
            $saleItemsData = [];
            foreach ($fields['sale_items'] as $item) {
                $productId = $item['product_id'];

                $product = $products[$productId];

                $qty = $item['qty'];
                $price = $item['price'];

                $purchasePrice = $product->purchase_price;
                $totalPurchasePrice = $purchasePrice * $qty;

                $subtotal = $qty * $price;
                $profit = $subtotal - $totalPurchasePrice;



                $saleItemsData[] = [
                    'product_id' => $productId,
                    'unit' => $product->unit,
                    'qty' => $qty,
                    'price' => $price,
                    'profit' => $profit,
                    'subtotal' => $subtotal,
                ];
            }
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'customer_id' => $customer?->id ?? null,
                'sale_date' => $fields['sale_date'],
                'total_profit' => array_sum(array_column($saleItemsData, 'profit')),
                'total_amount' => array_sum(array_column($saleItemsData, 'subtotal')),
            ]);

            //inserting saleId in saleItems
            foreach ($saleItemsData as &$row) {
                $row['sale_id'] = $sale->id;
                $row['created_at'] = now();
                $row['updated_at'] = now();
            }
            unset($row);
            SaleItem::insert($saleItemsData);

            //deducting stock from products
            foreach ($saleItemsData as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if ($product->stock < $item['qty']) {
                    throw new InsufficientStockException("Insufficient Stock for: {$product->name}");
                }
                $product->stock -= $item['qty'];
                $product->selling_price = $item['price'];

                $product->save();
            }
            return $sale->load(['customer', 'saleItems.product']);
        });
    }
    //-------------------------------------------------------------------
    //  UPDATE SALE
    //-------------------------------------------------------------------
    public function update(
        Sale $sale,
        array $fields,
    ) {
        return DB::transaction(function () use ($sale, $fields) {
            /*
            Crate/Update/Null Customer ✅
            Add old sale stock return to products stock.✅
            Delete all old saleItems.✅
            Create new SaleItemsData. ✅
            Update Sale.✅
            Insert new SaleItems ✅
            Deduct Stock from Products ✅
            Check for InsufficientStock ✅

            {If InsufficientStock Throw Exception
            Else Deduct stock)✅

            Return Updated Sale ✅
            */


            $sale->load('saleItems.product');
            //Crate/Update/Null Customer
            if ($fields['customer_email']) {
                $customer = Customer::where('email', $fields['customer_email'])->first();
                if ($customer) {
                    $customer->update([
                        'name' => $fields['customer_name'] ?? $customer->name,
                        'phone' => $fields['customer_phone'] ?? $customer->phone,
                    ]);
                } else {
                    $customer = Customer::create([
                        'user_id' => Auth::id(),
                        'name' => $fields['customer_name'] ?? null,
                        'email' => $fields['customer_email'] ?? null,
                        'phone' => $fields['customer_phone'] ?? null,
                    ]);
                }
            } else {
                $customer = null;
            }

            //Add old sale stock return to products stock.
            $saleItems = $sale->saleItems;
            foreach ($saleItems as $saleItem) {
                $product = $saleItem->product;
                $product->stock += $saleItem->qty;
                $product->save();
            }

            // Deduct Stock from Products
            $sale->saleItems()->delete();

            $productIds = array_column($fields['sale_items'], 'product_id');
            $products = Product::whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            //Create new SaleItemsData.
            $saleItemsData = [];
            foreach ($fields['sale_items'] as $item) {
                $product = $products[$item['product_id']];
                $saleId = $sale->id;
                $productId = $item['product_id'];
                $unit = $product->unit;
                $qty = $item['qty'];
                $price = $item['price'];
                $totalCost = $qty * $product->purchase_price;
                $subtotal = $qty * $price;
                $profit = $subtotal - $totalCost;

                $saleItemsData[] = [
                    'sale_id' => $saleId,
                    'product_id' => $productId,
                    'unit' => $unit,
                    'qty' => $qty,
                    'price' => $price,
                    'profit' => $profit,
                    'subtotal' => $subtotal,
                    'updated_at' => now(),
                    'created_at' => now(),
                ];
            }

            //Update Sale.
            $sale->update([
                'customer_id' => $customer?->id ?? null,
                'sale_date' => $fields['sale_date'],
                'total_profit' => array_sum(array_column($saleItemsData, 'profit')),
                'total_amount' => array_sum(array_column($saleItemsData, 'subtotal')),
            ]);

            //Insert new SaleItems
            SaleItem::insert($saleItemsData);

            //Deduct Stock from Products
            foreach ($saleItemsData as $item) {
                $product = $products[$item['product_id']];

                if ($product->stock < $item['qty']) {
                    throw new InsufficientStockException("Insufficient Stock for: {$product->name}");
                }
                $product->stock -= $item['qty'];
                $product->selling_price = $item['price'];

                $product->save();
            }
            return $sale->load([
                'customer',
                'saleItems.product'
            ]);
        });
    }
    //-------------------------------------------------------------------
    //  DELETE SALE
    //-------------------------------------------------------------------
    public function delete(Sale $sale)
    {
        /*
        * Add old sale stock return to products stock. ✅
        * Delete Sale. ✅
        */
        return DB::transaction(function () use ($sale) {
            $sale->load('saleItems.product');
            $saleItems = $sale->saleItems;
            foreach ($saleItems as $saleItem) {
                $product = $saleItem->product;
                $product->stock += $saleItem->qty;
                $product->save();
            }
            $sale->delete();
        });
    }
}
