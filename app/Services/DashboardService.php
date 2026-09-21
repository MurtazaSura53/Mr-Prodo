<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;

class DashboardService
{
    public function getData()
    {
        $dashboardData = [];

        $dashboardData['overviews'] = [
            [
                'link' => route('products'),
                'label' => 'Products',
                'fa_icon' => "fa-solid fa-box-open",
                'content' => number_format(Product::count()),
            ],
            [
                'link' => route('categories'),
                'label' => 'Categories',
                'fa_icon' => "fa-solid fa-layer-group",
                'content' => number_format(Category::count()),
            ],
            [
                'link' => route('customers'),
                'label' => 'Customers',
                'fa_icon' => "fa-solid fa-users",
                'content' => number_format(Customer::count()),
            ],
            [
                'link' => route('purchases'),
                'label' => 'Purchases',
                'fa_icon' => 'fa-solid fa-cart-plus',
                'content' => '₹' . number_format(round(Purchase::sum('total_amount'))),
            ],
            [
                'link' => route('sales'),
                'label' => 'Sales',
                'fa_icon' => 'fa-solid fa-cart-shopping',
                'content' => '₹' . number_format(round(Sale::sum('total_amount'))),
            ],
            [
                'link' => route('sales'),
                'label' => 'Profit',
                'fa_icon' => 'fa-solid fa-money-bill-trend-up',
                'content' => '₹' . number_format(round(Sale::sum('total_profit'))),
            ],
        ];

        $dashboardData['recentSales'] = Sale::with('customer')->latest()->take(10)->get();
        $dashboardData['inventory'] = [
            'totalProducts' => Product::count(),
            'inStock' => Product::inStock()->count(),
            'outOfStock' => Product::outOfStock()->count(),
        ];

        $dashboardData['topSellers'] = Product::withSum('saleItems as total_sold', 'qty')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
        return $dashboardData;
    }
}
