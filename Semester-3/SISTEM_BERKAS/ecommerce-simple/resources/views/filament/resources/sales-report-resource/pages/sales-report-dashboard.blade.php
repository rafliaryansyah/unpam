<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Report Type Selector -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Sales Report Dashboard</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('filament.admin.resources.sales-reports.index', ['report_type' => 'monthly']) }}" 
                   class="p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="text-center">
                        <div class="text-2xl mb-2">📅</div>
                        <h4 class="font-semibold">Monthly Report</h4>
                        <p class="text-sm text-gray-600">Current month sales</p>
                    </div>
                </a>
                
                <a href="{{ route('filament.admin.resources.sales-reports.index', ['report_type' => 'quarterly']) }}" 
                   class="p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="text-center">
                        <div class="text-2xl mb-2">📊</div>
                        <h4 class="font-semibold">Quarterly Report</h4>
                        <p class="text-sm text-gray-600">Current quarter sales</p>
                    </div>
                </a>
                
                <a href="{{ route('filament.admin.resources.sales-reports.index', ['report_type' => 'semester']) }}" 
                   class="p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="text-center">
                        <div class="text-2xl mb-2">📈</div>
                        <h4 class="font-semibold">Semester Report</h4>
                        <p class="text-sm text-gray-600">Current semester sales</p>
                    </div>
                </a>
                
                <a href="{{ route('filament.admin.resources.sales-reports.index', ['report_type' => 'yearly']) }}" 
                   class="p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="text-center">
                        <div class="text-2xl mb-2">📋</div>
                        <h4 class="font-semibold">Year-over-Year (YoY)</h4>
                        <p class="text-sm text-gray-600">Current year sales</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-700 mb-2">Report Types Available</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Monthly: Current month data
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                        Quarterly: Current quarter data
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                        Semester: Current semester data
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                        Yearly: Current year data
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-700 mb-2">Features</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Product-wise sales breakdown
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                        Date range filtering
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                        Export capabilities
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                        Growth comparison
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-700 mb-2">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('filament.admin.resources.sales-reports.index') }}" 
                       class="block w-full bg-blue-500 text-white text-center py-2 px-4 rounded hover:bg-blue-600 transition-colors">
                        View All Reports
                    </a>
                    <a href="{{ route('filament.admin.resources.orders.index') }}" 
                       class="block w-full bg-green-500 text-white text-center py-2 px-4 rounded hover:bg-green-600 transition-colors">
                        View Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>