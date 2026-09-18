<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function options()
    {
        return Customer::all(['id', 'name', 'email', 'phone']);
    }
    public function paginated()
    {
        return Customer::withSum('sales', 'total_amount')->withCount('sales')->paginate(10);
    }
    public function create(array $fields)
    {
        return Customer::create($fields);
    }
    public function update(Customer $customer, array $fields)
    {
        $customer->update($fields);

        return $customer->fresh();
    }
    public function delete(Customer $customer)
    {
        $customer->delete();
    }
}
