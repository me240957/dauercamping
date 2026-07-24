<!DOCTYPE html>
<html lang="de" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dauercamping') – Verwaltung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">

<div class="min-h-screen flex">

    {{-- ── Sidebar ──────────────────────────────────────────────────────── --}}
    <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
        <div class="flex-1 flex flex-col min-h-0 bg-emerald-800">

            {{-- Logo --}}
            <div class="flex items-center h-16 flex-shrink-0 px-4 bg-emerald-900">
                <svg class="h-8 w-8 text-emerald-300 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10l9-7 9 7v11a1 1 0 01-1 1H4a1 1 0 01-1-1V10z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 21V12h6v9"/>
                </svg>
                <span class="text-white text-lg font-bold">Dauercamping</span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-2 py-4 space-y-1">
                @php
                    $navItems = [
                        ['route' => 'dashboard',           'label' => 'Dashboard',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'stellplaetze.index', 'label' => 'Stellplätze',  'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
                        ['route' => 'paechter.index',     'label' => 'Pächter',      'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['route' => 'vertraege.index',    'label' => 'Verträge',     'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route' => 'zahlungen.index',    'label' => 'Zahlungen',    'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php $active = request()->routeIs($item['route'] . '*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="{{ $active ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ $active ? 'text-emerald-300' : 'text-emerald-400' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- User --}}
            <div class="flex-shrink-0 flex border-t border-emerald-700 p-4">
                <div class="flex items-center w-full">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-sm font-medium">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-emerald-300 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Abmelden"
                                class="ml-2 text-emerald-400 hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main Content ─────────────────────────────────────────────────── --}}
    <div class="md:pl-64 flex flex-col flex-1">

        {{-- Top Bar --}}
        <div class="sticky top-0 z-10 bg-white shadow-sm border-b border-gray-200">
            <div class="px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', '')</h1>
                <div class="flex items-center space-x-3">
                    @yield('header-actions')
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        <div class="px-4 sm:px-6 lg:px-8 pt-4">
            @if(session('success'))
                <div class="rounded-md bg-green-50 border border-green-200 p-4 mb-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-md bg-red-50 border border-red-200 p-4 mb-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 102 0V9a1 1 0 10-2 0zm1-5a1 1 0 110 2 1 1 0 010-2z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-4 pb-8">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
