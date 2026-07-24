<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <div class="p-2 rounded-lg {{ $color ?? 'bg-emerald-100' }}">
                <svg class="h-6 w-6 {{ $iconColor ?? 'text-emerald-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                </svg>
            </div>
        </div>
        <div class="ml-4 flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-500 truncate">{{ $label }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
        </div>
    </div>
    @if(isset($sub))
        <p class="mt-2 text-xs text-gray-500">{{ $sub }}</p>
    @endif
</div>
