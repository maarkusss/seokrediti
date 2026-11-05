@extends('layouts.app')

@section('title', 'Edit admin - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Main content -->
        <!-- Info box -->
        @include('admincp.components.infobox')
        <!-- End info box -->
        <div class="flex flex-row flex-auto h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')
            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Page Header Card -->
                <div class="flex items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">
                            Edit admin: <span class="text-blue-600">{{ $user->first_name }} {{ $user->last_name }}</span>
                            <span class="text-xs font-mono font-medium px-2 py-0.5 rounded bg-slate-100 text-slate-600 ml-1">({{ $user->username }})</span>
                        </h1>
                        <p class="text-xs text-slate-500 mt-0.5">Edit user data or permissions</p>
                    </div>
                    <a href="{{ route('admincp.admins.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded transition-colors no-underline">
                        &larr; Back
                    </a>
                </div>

                <!-- Form Card -->
                <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                    <form action="{{ route('admincp.admins.update', ['admin' => $user->id]) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Main information
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1.5">First name *</label>
                                <input id="first_name" name="first_name" type="text" value="{{ $user->first_name }}" placeholder="First name" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1.5">Last name *</label>
                                <input id="last_name" name="last_name" type="text" value="{{ $user->last_name }}" placeholder="Last name" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium text-slate-700 mb-1.5">Username *</label>
                                <input id="username" name="username" type="text" value="{{ $user->username }}" placeholder="username" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                                <input id="email" name="email" type="email" value="{{ $user->email }}" placeholder="admin@example.com"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">New password (leave blank to keep current)</label>
                                <input id="password" name="password" type="password" placeholder="••••••••"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                        </div>

                        @can('edit permissions')
                            <!-- Section: Data Permissions -->
                            <div class="pt-6 border-t border-slate-100" id="data_inputs">
                                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                                    <h2 class="text-base font-semibold text-slate-800">Content & Data Permissions</h2>
                                    <label class="inline-flex items-center text-sm font-semibold text-blue-600 cursor-pointer">
                                        <input type="checkbox" id="check_all_data" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 mr-2 cursor-pointer">
                                        Select all
                                    </label>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Admins</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view admins]" type="checkbox" @if ($user->getPermissionNames()->contains('view admins')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View admins</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit admins]" type="checkbox" @if ($user->getPermissionNames()->contains('edit admins')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit admins</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[add admins]" type="checkbox" @if ($user->getPermissionNames()->contains('add admins')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Add admins</label>
                                    </div>
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Sections</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view sections]" type="checkbox" @if ($user->getPermissionNames()->contains('view sections')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View sections</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[add sections]" type="checkbox" @if ($user->getPermissionNames()->contains('add sections')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Add sections</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit sections]" type="checkbox" @if ($user->getPermissionNames()->contains('edit sections')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit sections</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[delete sections]" type="checkbox" @if ($user->getPermissionNames()->contains('delete sections')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Delete sections</label>
                                    </div>
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Lenders</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view lenders]" type="checkbox" @if ($user->getPermissionNames()->contains('view lenders')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View lenders</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[add lenders]" type="checkbox" @if ($user->getPermissionNames()->contains('add lenders')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Add lenders</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit lenders]" type="checkbox" @if ($user->getPermissionNames()->contains('edit lenders')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit lenders</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[delete lenders]" type="checkbox" @if ($user->getPermissionNames()->contains('delete lenders')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Delete lenders</label>
                                    </div>
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Loan Types</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view loantypes]" type="checkbox" @if ($user->getPermissionNames()->contains('view loantypes')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View loan types</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[add loantypes]" type="checkbox" @if ($user->getPermissionNames()->contains('add loantypes')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Add loan types</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit loantypes]" type="checkbox" @if ($user->getPermissionNames()->contains('edit loantypes')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit loan types</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[delete loantypes]" type="checkbox" @if ($user->getPermissionNames()->contains('delete loantypes')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Delete loan types</label>
                                    </div>
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Faqs</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view faqs]" type="checkbox" @if ($user->getPermissionNames()->contains('view faqs')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View faqs</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[add faqs]" type="checkbox" @if ($user->getPermissionNames()->contains('add faqs')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Add faqs</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit faqs]" type="checkbox" @if ($user->getPermissionNames()->contains('edit faqs')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit faqs</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[delete faqs]" type="checkbox" @if ($user->getPermissionNames()->contains('delete faqs')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Delete faqs</label>
                                    </div>
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Settings & Media</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view settings]" type="checkbox" @if ($user->getPermissionNames()->contains('view settings')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View settings</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit settings]" type="checkbox" @if ($user->getPermissionNames()->contains('edit settings')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit settings</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view images]" type="checkbox" @if ($user->getPermissionNames()->contains('view images')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View images</label>
                                    </div>
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Connected Countries</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view connectedCountries]" type="checkbox" @if ($user->getPermissionNames()->contains('view connectedCountries')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View countries</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[add connectedCountries]" type="checkbox" @if ($user->getPermissionNames()->contains('add connectedCountries')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Add countries</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit connectedCountries]" type="checkbox" @if ($user->getPermissionNames()->contains('edit connectedCountries')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit countries</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[delete connectedCountries]" type="checkbox" @if ($user->getPermissionNames()->contains('delete connectedCountries')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Delete countries</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Additional Permissions -->
                            <div class="pt-6 border-t border-slate-100" id="info_inputs">
                                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                                    <h2 class="text-base font-semibold text-slate-800">System & Additional Permissions</h2>
                                    <label class="inline-flex items-center text-sm font-semibold text-blue-600 cursor-pointer">
                                        <input type="checkbox" id="check_all_info" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 mr-2 cursor-pointer">
                                        Select all
                                    </label>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Bans</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view bans]" type="checkbox" @if ($user->getPermissionNames()->contains('view bans')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View bans</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[add bans]" type="checkbox" @if ($user->getPermissionNames()->contains('add bans')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Add bans</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit bans]" type="checkbox" @if ($user->getPermissionNames()->contains('edit bans')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit bans</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[delete bans]" type="checkbox" @if ($user->getPermissionNames()->contains('delete bans')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Delete bans</label>
                                    </div>
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Visitors & Data</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view visitors]" type="checkbox" @if ($user->getPermissionNames()->contains('view visitors')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View visitors</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view statistics]" type="checkbox" @if ($user->getPermissionNames()->contains('view statistics')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View statistics</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view history]" type="checkbox" @if ($user->getPermissionNames()->contains('view history')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View history</label>
                                    </div>
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Consumers & Reviews</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view consumers]" type="checkbox" @if ($user->getPermissionNames()->contains('view consumers')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View consumers</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[delete consumers]" type="checkbox" @if ($user->getPermissionNames()->contains('delete consumers')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Delete consumers</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view reviews]" type="checkbox" @if ($user->getPermissionNames()->contains('view reviews')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View reviews</label>
                                    </div>
                                    <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded space-y-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">System Administration</span>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[delete admins]" type="checkbox" @if ($user->getPermissionNames()->contains('delete admins')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Delete admins</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit settings config]" type="checkbox" @if ($user->getPermissionNames()->contains('edit settings config')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit config</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[edit permissions]" type="checkbox" @if ($user->getPermissionNames()->contains('edit permissions')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> Edit permissions</label>
                                        <label class="flex items-center text-sm text-slate-700 cursor-pointer"><input name="permissions[view adminlogs]" type="checkbox" @if ($user->getPermissionNames()->contains('view adminlogs')) checked @endif class="w-4 h-4 text-blue-600 border-slate-300 rounded mr-2"> View activity logs</label>
                                    </div>
                                </div>
                            </div>
                        @endcan

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                @if (auth()->id() !== $user->id)
                                    @can('delete admins')
                                        <button type="button"
                                            onclick="if(confirm('Are you sure you want to delete this admin?')) document.getElementById('delete-admin-form').submit();"
                                            class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 rounded transition-colors cursor-pointer">
                                            Delete admin
                                        </button>
                                    @endcan
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admincp.admins.index') }}"
                                    class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors no-underline">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer">
                                    Save changes
                                </button>
                            </div>
                        </div>
                    </form>

                    @if (auth()->id() !== $user->id)
                        @can('delete admins')
                            <form id="delete-admin-form" action="{{ route('admincp.admins.destroy', ['admin' => $user->id]) }}" method="POST" style="display: none;">
                                @method('DELETE')
                                @csrf
                            </form>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
