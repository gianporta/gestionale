<div class="mt-2">
    @if($ambiente === 'production')
    <div class="w-full text-center py-3 rounded-lg bg-red-600 text-white font-semibold">
        PRODUZIONE
    </div>
    @else
    <div class="w-full text-center py-3 rounded-lg bg-green-600 text-white font-semibold">
        STAGING
    </div>
    @endif
</div>
