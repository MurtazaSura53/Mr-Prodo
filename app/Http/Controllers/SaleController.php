<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct(
        public SaleService $saleService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sales = $this->saleService->paginated();
        if ($request->expectsJson()) {
            return SaleResource::collection($sales);
        }
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all(['id', 'name', 'unit', 'selling_price', 'purchase_price']);
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveSaleRequest $request)
    {
        $fields = $request->validated();
        $saleItemsData = $fields['sale_items'];

        $products = Product::whereIn(
            'id',
            array_column($saleItemsData, 'product_id'),
        )->get();

        foreach ($products as $product) {
            $this->authorize('sale', $product);
        }

        $sale = $this->saleService->create($fields);
        return response()->json([
            'message' => 'New Sale Added',
            'data' => new SaleResource($sale),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $this->authorize('update', $sale);
        $sale = $this->saleService->get($sale);
        $products = Product::all(['id', 'name', 'unit', 'selling_price']);
        return view('sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveSaleRequest $request, Sale $sale)
    {
        $this->authorize('update', $sale);
        $fields = $request->validated();
        $saleItemsData = $fields['sale_items'];

        $products = Product::whereIn(
            'id',
            array_column($saleItemsData, 'product_id'),
        )->get();

        foreach ($products as $product) {
            $this->authorize('sale', $product);
        }

        $sale = $this->saleService->update($sale, $fields);
        return response()->json([
            'message' => 'Changes Saved',
            'data' => new SaleResource($sale),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $this->authorize('delete', $sale);
        $this->saleService->delete($sale);
        return response()->noContent();
    }
}
