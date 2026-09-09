<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function paginated()
    {
        $purchases = Purchase::with('purchaseItems.product')->paginate(10);
        return $purchases;
    }
    public function get(Purchase $purchase)
    {
        return $purchase->load('purchaseItems.product');
    }
    public function create(array $fields)
    {
        DB::beginTransaction();
        try {
            $productIds = array_column($fields['purchase_items'], 'product_id');
            $productUnits = Product::whereIn('id', $productIds)->pluck('unit', 'id');

            $purchase_items_data = [];

            foreach ($fields['purchase_items'] as $item) {
                $productId = $item['product_id'];
                $qty = $item['qty'];
                $price = $item['price'];

                $purchase_items_data[] = [
                    'product_id' => $productId,
                    'unit'       => $productUnits[$productId],
                    'qty'        => $qty,
                    'price'      => $price,
                    'subtotal'   => $qty * $price,
                ];
            }
            $purchase = Purchase::create([
                'user_id'       => Auth::id(),
                'supplier_name' => $fields['supplier_name'],
                'purchase_date' => $fields['purchase_date'],
                'total_amount'  => array_sum(array_column($purchase_items_data, 'subtotal')),
            ]);

            foreach ($purchase_items_data as &$row) {
                $row['purchase_id'] = $purchase->id;
                $row['created_at'] = now();
                $row['updated_at'] = now();
            }
            unset($row);
            PurchaseItem::insert($purchase_items_data);

            foreach ($purchase_items_data as $item) {
                $product = Product::find($item['product_id']);

                $product->stock += $item['qty'];
                $product->purchase_price = $item['price'];

                $product->save();
            }
            DB::commit();
            return $purchase->load('purchaseItems.product');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Purchase Creation Failed: ' . $e->getMessage());
            return false;
        }
    }
    public function update(
        Purchase $purchase,
        array $fields,
    ) {
        DB::beginTransaction();
        try {
            $productIds = array_column($fields['purchase_items'], 'product_id');
            $product_units = Product::whereIn('id', $productIds)->pluck('unit', 'id');

            //Removing old added stock from product
            $purchase_items = $purchase->purchaseItems()->get();
            foreach ($purchase_items as $purchase_item) {
                $product = $purchase_item->product;
                $product->stock -= $purchase_item->qty;
                $product->save();
            }
            //Removing old purchase records
            PurchaseItem::where('purchase_id', $purchase->id)->delete();

            //Creating new Purchase items data
            $purchase_items_data = [];
            foreach ($fields['purchase_items'] as $item) {
                $purchase_id = $purchase->id;
                $product_id = $item['product_id'];
                $qty = $item['qty'];
                $price = $item['price'];

                $purchase_items_data[] = [
                    'purchase_id' => $purchase_id,
                    'product_id' => $product_id,
                    'unit' => $product_units[$product_id],
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $price * $qty,
                    'updated_at' => now(),
                    'created_at' => now(),
                ];
            }
            //Updating Purchase
            $purchase_data = [
                'supplier_name' => $fields['supplier_name'],
                'purchase_date' => $fields['purchase_date'],
                'total_amount' => array_sum(array_column($purchase_items_data, 'subtotal')),
            ];
            $purchase->update($purchase_data);

            //Inserted new purchase items
            PurchaseItem::insert($purchase_items_data);
            //Setting new purchase price to product if price is changed
            foreach ($purchase_items_data as $item_data) {
                $product = Product::find($item_data['product_id']);

                $product->stock += $item_data['qty'];
                $product->purchase_price = $item_data['price'];

                $product->save();
            }
            DB::commit();
            return $purchase->load('purchaseItems.product');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Purchase Update Failed: ' . $e->getMessage());
            return false;
        }
    }
    public function delete(Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            $purchase_items = $purchase->purchaseItems()->get();
            foreach ($purchase_items as $purchase_item) {
                $product = $purchase_item->product;
                $product->stock -= $purchase_item->qty;
                $product->save();
            }
            $purchase->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Purchase Delete Failed: ' . $e->getMessage());
            return false;
        }
    }
}
