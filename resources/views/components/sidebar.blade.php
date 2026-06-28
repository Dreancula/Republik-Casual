<aside class="hidden lg:flex w-[290px] h-screen shrink-0 bg-white border-r border-black/5 flex-col px-6 py-7 overflow-y-auto">

    <!-- TOP -->
    <div>

        <!-- LOGO -->
        <div class="mb-12">

            <h1 class="text-[34px] font-extrabold leading-[0.9] tracking-tight text-[#111111]">
                REPUBLIK<br>CASUAL
            </h1>

            <p class="mt-4 text-xs uppercase tracking-[0.35em] text-black/40">
                Customers Manager
            </p>

        </div>

        <!-- MENU -->
        <nav class="space-y-3">

    <!-- DASHBOARD -->
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-4 px-5 py-4 rounded-2xl font-medium transition-all duration-300

       {{ request()->routeIs('dashboard')
            ? 'bg-[#111111] text-white shadow-lg'
            : 'text-black/70 hover:bg-black/5' }}">

        <span class="text-lg">🏠</span>

        Dashboard

    </a>

    <!-- USERS -->
    @if(auth()->user()->role === 'admin')

        <a href="{{ route('users.index') }}"
           class="flex items-center gap-4 px-5 py-4 rounded-2xl font-medium transition-all duration-300

           {{ request()->routeIs('users.*')
                ? 'bg-[#111111] text-white shadow-lg'
                : 'text-black/70 hover:bg-black/5' }}">

            <span class="text-lg">👤</span>

            Users

        </a>

    @endif

    <!-- CUSTOMERS -->
    <a href="{{ route('customers') }}"
       class="flex items-center gap-4 px-5 py-4 rounded-2xl font-medium transition-all duration-300

       {{ request()->routeIs('customers')
            ? 'bg-[#111111] text-white shadow-lg'
            : 'text-black/70 hover:bg-black/5' }}">

        <span class="text-lg">👥</span>

        Customers

    </a>

    <!-- ORDERS -->
    <a href="{{ route('orders') }}"
       class="flex items-center gap-4 px-5 py-4 rounded-2xl font-medium transition-all duration-300

       {{ request()->routeIs('orders')
            ? 'bg-[#111111] text-white shadow-lg'
            : 'text-black/70 hover:bg-black/5' }}">

        <span class="text-lg">📦</span>

        Orders

    </a>
@if(auth()->user()->role === 'admin')

    <a href="{{ route('chats.index') }}"
       class="flex items-center gap-4 px-5 py-4 rounded-2xl font-medium transition-all duration-300
       {{ request()->routeIs('chats.index') || request()->is('admin/chats*')
            ? 'bg-[#111111] text-white shadow-lg'
            : 'text-black/70 hover:bg-black/5' }}">

        <span class="text-lg">💬</span>

        <span>Chats</span>

    </a>

@endif

    <!-- PRODUCTS -->
    <a href="{{ route('products') }}"
       class="flex items-center gap-4 px-5 py-4 rounded-2xl font-medium transition-all duration-300

       {{ request()->routeIs('products')
            ? 'bg-[#111111] text-white shadow-lg'
            : 'text-black/70 hover:bg-black/5' }}">

        <span class="text-lg">🛍️</span>

        Products

    </a>

    <a href="{{ route('categories.index') }}"
   class="flex items-center gap-4 px-5 py-4 rounded-2xl font-medium transition-all duration-300

   {{ request()->routeIs('categories.*')
        ? 'bg-[#111111] text-white shadow-lg'
        : 'text-black/70 hover:bg-black/5' }}">

    <span class="text-lg">🏷️</span>

    Categories

</a>

<a href="{{ route('brands.index') }}"
   class="flex items-center gap-4 px-5 py-4 rounded-2xl font-medium transition-all duration-300

   {{ request()->routeIs('brands.*')
        ? 'bg-[#111111] text-white shadow-lg'
        : 'text-black/70 hover:bg-black/5' }}">

    <span class="text-lg">🏢</span>

    Brands

</a>

    <!-- ANALYTICS -->
    <a href="{{ route('analytics') }}"
       class="flex items-center gap-4 px-5 py-4 rounded-2xl font-medium transition-all duration-300

       {{ request()->routeIs('analytics')
            ? 'bg-[#111111] text-white shadow-lg'
            : 'text-black/70 hover:bg-black/5' }}">

        <span class="text-lg">📊</span>

        Analytics

    </a>

</nav>

    </div>

</aside>