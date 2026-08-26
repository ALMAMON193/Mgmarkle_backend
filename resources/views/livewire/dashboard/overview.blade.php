  <main class="p-4 lg:p-5 w-full min-w-full space-y-4">
    <!-- Top Action Bar / Welcome Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-indigo-900 via-slate-900 to-slate-900 rounded-2xl p-4 lg:p-5 text-white shadow-md shadow-slate-900/10 border border-slate-800 relative overflow-hidden">
        <!-- Background Glows -->
        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-indigo-500/10 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-0.5">
            <div class="flex items-center gap-1.5 text-indigo-400 font-semibold text-[11px] uppercase tracking-wider">
                <i class="fas fa-sparkles"></i> Overview Dashboard
            </div>
            <h1 class="text-xl lg:text-2xl font-extrabold text-white tracking-tight">
                Welcome back, {{ Auth::user()->name ?? 'Admin' }}! 👋
            </h1>
            <p class="text-slate-300 text-xs font-normal">
                Here's what's happening with your system today. You have 3 notifications and 12 new orders.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-2.5 shrink-0">
            <!-- Filter Select -->
            <select class="bg-slate-800/90 border border-slate-700/80 text-xs text-slate-200 rounded-lg px-3 py-1.5 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
                <option value="30">Last 30 Days</option>
                <option value="7">Last 7 Days</option>
                <option value="90">Last 3 Months</option>
                <option value="365">This Year</option>
            </select>
            <!-- Export Button -->
            <button class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-3.5 py-1.5 rounded-lg shadow-md shadow-indigo-600/30 flex items-center gap-1.5 transition">
                <i class="fas fa-download text-xs"></i>
                <span class="hidden sm:inline">Export</span>
            </button>
        </div>
    </div>

    <!-- 4 Stat Cards Grid -->
    <div class="grid gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Stat Card 1: Revenue -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Revenue</span>
                <div class="h-9 w-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    ${{ isset($totalRevenueAmount) && $totalRevenueAmount > 0 ? number_format($totalRevenueAmount, 2) : '45,678.00' }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="inline-flex items-center gap-0.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">
                        <i class="fas fa-arrow-up text-[9px]"></i> +23.1%
                    </span>
                    <span class="text-[10px] font-medium text-slate-400">vs last month</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 2: Orders / Payments -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Orders</span>
                <div class="h-9 w-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-sm group-hover:bg-sky-600 group-hover:text-white transition-colors">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    {{ isset($totalPaymentsCount) && $totalPaymentsCount > 0 ? number_format($totalPaymentsCount) : '1,234' }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="inline-flex items-center gap-0.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">
                        <i class="fas fa-arrow-up text-[9px]"></i> +8.2%
                    </span>
                    <span class="text-[10px] font-medium text-slate-400">vs last month</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 3: Customers -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Customers</span>
                <div class="h-9 w-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm group-hover:bg-amber-600 group-hover:text-white transition-colors">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    {{ isset($totalUsersCount) && $totalUsersCount > 0 ? number_format($totalUsersCount) : '2,543' }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="inline-flex items-center gap-0.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">
                        <i class="fas fa-arrow-up text-[9px]"></i> +12.5%
                    </span>
                    <span class="text-[10px] font-medium text-slate-400">vs last month</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 4: Active Sessions -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Active Sessions</span>
                <div class="h-9 w-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm group-hover:bg-rose-600 group-hover:text-white transition-colors">
                    <i class="fas fa-bolt"></i>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">573</h3>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="inline-flex items-center gap-0.5 text-[11px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">
                        <i class="fas fa-arrow-down text-[9px]"></i> -3.2%
                    </span>
                    <span class="text-[10px] font-semibold text-emerald-600 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Charts Section -->
    <div class="grid gap-4 lg:grid-cols-3">
        <!-- Revenue Growth Chart (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between gap-3 mb-2">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Revenue & Growth Trend</h2>
                    <p class="text-[11px] text-slate-400">Monthly gross earnings and sales volume</p>
                </div>
                <div class="flex items-center gap-1 bg-slate-100 p-0.5 rounded-lg">
                    <button class="px-2.5 py-0.5 text-[11px] font-semibold text-slate-700 bg-white rounded shadow-2xs">Revenue</button>
                    <button class="px-2.5 py-0.5 text-[11px] font-medium text-slate-500 hover:text-slate-900 transition">Orders</button>
                </div>
            </div>
            
            <!-- ApexChart Container -->
            <div class="w-full min-h-[220px]" id="revenueChart"></div>
        </div>

        <!-- Sales by Category Donut Chart (1 Column) -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Category Share</h2>
                <p class="text-[11px] text-slate-400">Distribution across active product categories</p>
            </div>
            
            <div class="my-2 flex items-center justify-center min-h-[190px]" id="categoryChart"></div>

            <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-[11px]">
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                    <span class="text-slate-600 font-medium truncate">Electronics (42%)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span class="text-slate-600 font-medium truncate">Fashion (28%)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    <span class="text-slate-600 font-medium truncate">Home (18%)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                    <span class="text-slate-600 font-medium truncate">Other (12%)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables & Recent Activity Section -->
    <div class="grid gap-4 lg:grid-cols-3">
        <!-- Recent Orders Table (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Recent Transactions</h2>
                    <p class="text-[11px] text-slate-400">Latest completed orders</p>
                </div>
                <a href="#" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 transition">
                    View All <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-2.5 px-4">Order ID</th>
                            <th class="py-2.5 px-3">Customer</th>
                            <th class="py-2.5 px-3">Category</th>
                            <th class="py-2.5 px-3">Amount</th>
                            <th class="py-2.5 px-3">Status</th>
                            <th class="py-2.5 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($recentTransactions as $txn)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">
                                    #{{ $txn->transaction_id ?? 'TXN-'.$txn->id }}
                                </td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($txn->user->name ?? 'User') }}" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span class="truncate max-w-[120px]">{{ $txn->user->name ?? 'Guest User' }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">
                                    {{ $txn->booking->event->title ?? 'General Payment' }}
                                </td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">
                                    ${{ number_format($txn->amount, 2) }}
                                </td>
                                <td class="py-2.5 px-3">
                                    @if($txn->status === 'success' || $txn->status === 'completed')
                                        <span class="px-2 py-0.5 text-[9px] font-bold bg-emerald-50 text-emerald-600 rounded-md border border-emerald-200/50">Completed</span>
                                    @elseif($txn->status === 'pending')
                                        <span class="px-2 py-0.5 text-[9px] font-bold bg-amber-50 text-amber-600 rounded-md border border-amber-200/50">Pending</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[9px] font-bold bg-rose-50 text-rose-600 rounded-md border border-rose-200/50">{{ ucfirst($txn->status) }}</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">#ORD-9829</td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Alex" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span>Alex Morgan</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">Electronics</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">$299.00</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-emerald-50 text-emerald-600 rounded-md border border-emerald-200/50">Completed</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">#ORD-9828</td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Sophia" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span>Sophia Chen</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">Fashion</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">$145.50</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-amber-50 text-amber-600 rounded-md border border-amber-200/50">Processing</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">#ORD-9827</td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Michael" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span>Michael Brown</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">Home & Office</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">$520.00</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-indigo-50 text-indigo-600 rounded-md border border-indigo-200/50">Shipped</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">#ORD-9826</td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Emma" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span>Emma Watson</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">Electronics</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">$89.99</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-rose-50 text-rose-600 rounded-md border border-rose-200/50">Cancelled</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">#ORD-9825</td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=David" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span>David Miller</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">Automotive</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">$640.00</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-emerald-50 text-emerald-600 rounded-md border border-emerald-200/50">Completed</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">#ORD-9824</td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Jessica" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span>Jessica Taylor</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">Fashion</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">$175.00</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-indigo-50 text-indigo-600 rounded-md border border-indigo-200/50">Shipped</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">#ORD-9823</td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Daniel" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span>Daniel Wilson</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">Beauty</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">$95.20</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-emerald-50 text-emerald-600 rounded-md border border-emerald-200/50">Completed</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">#ORD-9822</td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Olivia" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span>Olivia Martinez</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">Sports</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">$310.00</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-amber-50 text-amber-600 rounded-md border border-amber-200/50">Pending</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-2.5 px-4 font-bold text-indigo-600">#ORD-9821</td>
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Liam" class="h-6 w-6 rounded-full bg-slate-100 border" />
                                        <span>Liam Johnson</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">Books</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">$45.00</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-emerald-50 text-emerald-600 rounded-md border border-emerald-200/50">Completed</span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-ellipsis-v text-xs"></i></button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions & Recent Activity Feed (1 Column) -->
        <div class="space-y-4">
            <!-- Quick Actions Panel -->
            <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs">
                <h2 class="text-sm font-bold text-slate-900 mb-3">Quick Management</h2>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('category.index') }}" class="p-2.5 rounded-lg border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50 hover:border-indigo-200 text-left group transition flex flex-col justify-between">
                        <i class="fas fa-folder-plus text-indigo-600 text-base mb-1 group-hover:scale-105 transition-transform"></i>
                        <span class="text-[11px] font-bold text-slate-800">Add Category</span>
                    </a>
                    <a href="{{ route('sub-category.index') }}" class="p-2.5 rounded-lg border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50 hover:border-indigo-200 text-left group transition flex flex-col justify-between">
                        <i class="fas fa-sitemap text-indigo-600 text-base mb-1 group-hover:scale-105 transition-transform"></i>
                        <span class="text-[11px] font-bold text-slate-800">Add Sub-Category</span>
                    </a>
                    <button class="p-2.5 rounded-lg border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50 hover:border-indigo-200 text-left group transition flex flex-col justify-between">
                        <i class="fas fa-user-plus text-indigo-600 text-base mb-1 group-hover:scale-105 transition-transform"></i>
                        <span class="text-[11px] font-bold text-slate-800">Create User</span>
                    </button>
                    <button class="p-2.5 rounded-lg border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50 hover:border-indigo-200 text-left group transition flex flex-col justify-between">
                        <i class="fas fa-file-invoice text-indigo-600 text-base mb-1 group-hover:scale-105 transition-transform"></i>
                        <span class="text-[11px] font-bold text-slate-800">Invoices</span>
                    </button>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs">
                <h2 class="text-sm font-bold text-slate-900 mb-3">System Activity Log</h2>
                
                <div class="relative pl-5 space-y-4 before:absolute before:left-2 before:top-1.5 before:bottom-1.5 before:w-0.5 before:bg-slate-200">
                    <div class="relative">
                        <span class="absolute -left-[19px] top-1 h-2.5 w-2.5 rounded-full bg-indigo-600 ring-2 ring-white"></span>
                        <p class="text-xs font-semibold text-slate-800">Category "Smart Electronics" added</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">12 mins ago</p>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[19px] top-1 h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                        <p class="text-xs font-semibold text-slate-800">Order #ORD-9824 paid ($299.00)</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">45 mins ago by Alex</p>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[19px] top-1 h-2.5 w-2.5 rounded-full bg-amber-500 ring-2 ring-white"></span>
                        <p class="text-xs font-semibold text-slate-800">Sub-category "Laptops" updated</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">2 hours ago</p>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[19px] top-1 h-2.5 w-2.5 rounded-full bg-slate-400 ring-2 ring-white"></span>
                        <p class="text-xs font-semibold text-slate-800">Database backup completed</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">5 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ApexCharts Script Initialization -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Revenue Area Chart
        const revenueOptions = {
            series: [{
                name: 'Revenue ($)',
                data: [12000, 18500, 24000, 31000, 28000, 39000, 45678]
            }, {
                name: 'Orders',
                data: [320, 450, 580, 720, 690, 890, 1234]
            }],
            chart: {
                type: 'area',
                height: 220,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#6366f1', '#10b981'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2.5 },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#94a3b8', fontSize: '10px' } }
            },
            yaxis: {
                labels: { style: { colors: '#94a3b8', fontSize: '10px' } }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 3
            },
            legend: { position: 'top', horizontalAlign: 'right', fontSize: '11px' }
        };

        const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
        revenueChart.render();

        // Category Donut Chart
        const categoryOptions = {
            series: [42, 28, 18, 12],
            chart: {
                type: 'donut',
                height: 190,
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            labels: ['Electronics', 'Fashion', 'Home & Office', 'Other'],
            colors: ['#6366f1', '#10b981', '#f59e0b', '#8b5cf6'],
            legend: { show: false },
            dataLabels: { enabled: false },
            stroke: { width: 0 }
        };

        const categoryChart = new ApexCharts(document.querySelector("#categoryChart"), categoryOptions);
        categoryChart.render();
    });
</script>


