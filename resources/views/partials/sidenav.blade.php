<aside class="sidenav" id="sidenav">
    <nav class="sidenav-nav">
        <ul class="sidenav-ul">
            <li id="profileLink"><a href="{{ route('profile.edit') }}"><i class="fa-solid fa-circle-user"></i>Profile</a></li>
            <li id="dashboardLink"><a href="/"><i class="fa-solid fa-chart-simple"></i>Dashboard</a></li>
            <li id="categoriesLink"><a href="{{ route('categories') }}"><i class="fa-solid fa-list"></i>Categories</a></li>
            <li id="productsLink"><a href="{{ route('products') }}"><i class="fa-solid fa-box-open"></i>Products</a></li>
            <li id="purchasesLink"><a href="{{ route('purchases') }}"><i class="fa-solid fa-cart-plus"></i>Purchases</a></li>
            <li id="salesLink"><a href="{{ route('sales') }}"><i class="fa-solid fa-cart-shopping"></i>Sales</a></li>
            <li id="customersLink"><a href="{{ route('customers') }}"><i class="fa-solid fa-users"></i>Customers</a></li>
            <li id="logoutLink">
                <a href="#" id="logoutBtn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>