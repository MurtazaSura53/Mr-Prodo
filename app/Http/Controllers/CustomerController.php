<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        public CustomerService $customerService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = $this->customerService->paginated();
        if ($request->expectsJson()) {
            return CustomerResource::collection($customers);
        }
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        SaveCustomerRequest $request,
        Customer $customer
    ) {
        $this->authorize('update', $customer);
        $fields = $request->validated();
        $customer = $this->customerService->update($customer, $fields);
        return response()->json([
            'message' => 'Changes Saved',
            'data' => new CustomerResource($customer),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);
        $this->customerService->delete($customer);
        return response()->noContent();
    }
}
