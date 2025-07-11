<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-primary-dk to-primary-md p-6 sm:p-8">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">My Access Requests</h1>
                        <p class="text-primary-lt mt-1">Manage your organization access requests</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if ($requests->isEmpty())
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <div class="bg-primary-lt/10 dark:bg-primary-dk/20 p-6 rounded-full mb-6">
                        <svg class="w-12 h-12 text-primary-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Access Requests Yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-center max-w-md">
                        You haven't made any access requests yet. Start by requesting access to an organization.
                    </p>
                </div>
            @else
                <!-- Requests Table -->
                <div class="overflow-hidden">
                    <!-- Mobile View -->
                    <div class="block sm:hidden">
                        <div class="p-4 space-y-4">
                            @foreach ($requests as $req)
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">
                                                {{ $req->medicalRepresentative->name ?? 'Unknown Representative' }}
                                            </h4>
                                        </div>
                                        <div class="ml-3">
                                            @if ($req->is_approved)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                                    Approved
                                                </span>
                                            @elseif ($req->is_rejected)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                                    Rejected
                                                </span>
                                            @elseif ($req->request_sent)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5 animate-pulse"></span>
                                                    Pending
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                    N/A
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        @if ($req->is_approved)
                                           <div class="flex gap-2">
                                                <form method="POST" action="{{ route('medical_rep.organization.request.reject', $req->id) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Revoke access
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif ($req->request_sent && !$req->is_approved && !$req->is_rejected)
                                            <div class="flex gap-2">
                                                <form method="POST" action="{{ route('medical_rep.organization.request.approve', $req->id) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Accept
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('medical_rep.organization.request.reject', $req->id) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-gray-400 dark:text-gray-500 text-sm">No actions available</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Desktop View -->
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Medical Representative
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($requests as $req)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-primary-lt/20 dark:bg-primary-dk/30 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-primary-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                        {{ $req->medicalRepresentative->name ?? 'Unknown Representative' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $req->medicalRepresentative->organization->name ?? 'Unknown Organization' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($req->is_approved)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                                    Approved
                                                </span>
                                            @elseif ($req->is_rejected)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                                    Rejected
                                                </span>
                                            @elseif ($req->request_sent)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                                                    Pending
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                    N/A
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($req->is_approved)
                                               <div class="flex gap-2">
                                                <form method="POST" action="{{ route('medical_rep.organization.request.reject', $req->id) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Revoke access
                                                    </button>
                                                </form>
                                            </div>
                                            @elseif ($req->request_sent && !$req->is_approved && !$req->is_rejected)
                                                <div class="flex gap-2">
                                                    <form method="POST" action="{{ route('medical_rep.organization.request.approve', $req->id) }}">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Accept
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('medical_rep.organization.request.reject', $req->id) }}">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">No actions available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>