<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavePurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function __construct(
        public PurchaseService $purchaseService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purchases = $this->purchaseService->paginated();
        if ($request->expectsJson()) {
            return PurchaseResource::collection($purchases);
        }
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all(['id', 'name', 'purchase_price', 'unit']);
        return view('purchases.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SavePurchaseRequest $request)
    {
        $fields = $request->validated();
        $purchase_items_data = $fields['purchase_items'];

        $products = Product::whereIn(
            'id',
            array_column($purchase_items_data, 'product_id')
        )->get();

        foreach ($products as $product) {
            $this->authorize('purchase', $product);
        }

        $purchase = $this->purchaseService->create($fields);
        return response()->json([
            'message' => 'New Purchase Added',
            'data' => new PurchaseResource($purchase),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $this->authorize('view', $purchase);
        $purchase = $this->purchaseService->get($purchase);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $this->authorize('update', $purchase);
        $purchase = $this->purchaseService->get($purchase);
        $products = Product::all(['id', 'name', 'purchase_price', 'unit']);
        return view('purchases.edit', compact('purchase', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SavePurchaseRequest $request, Purchase $purchase)
    {
        $this->authorize('update', $purchase);
        $fields = $request->validated();
        $purchase_items_data = $fields['purchase_items'];

        $products = Product::whereIn(
            'id',
            array_column($purchase_items_data, 'product_id')
        )->get();

        foreach ($products as $product) {
            $this->authorize('purchase', $product);
        }

        $purchase = $this->purchaseService->update($purchase, $fields);
        return response()->json([
            'message' => 'Changes Saved',
            'data' => new PurchaseResource($purchase),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $this->authorize('delete', $purchase);
        $this->purchaseService->delete($purchase);
        return response()->noContent();
    }
}
