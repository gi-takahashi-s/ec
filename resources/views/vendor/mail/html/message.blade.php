<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
@php
    $shopDisplay = \App\Services\ShopSettingService::getShopLogoOrName();
@endphp
@if($shopDisplay['type'] === 'logo')
    <img src="{{ $shopDisplay['value'] }}" alt="{{ $shopDisplay['alt'] }}" style="height: 40px; width: auto;">
@else
    {{ $shopDisplay['value'] }}
@endif
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ \App\Services\ShopSettingService::getBasicInfo()['shop_name'] ?? 'ECショップ' }}. @lang('All rights reserved.')
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
