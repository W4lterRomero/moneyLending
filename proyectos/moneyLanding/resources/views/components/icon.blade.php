@props([
    'name' => 'dots',
    'class' => 'w-5 h-5',
])

@php
    $icons = [
        'menu' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6h16.5M3.75 12h16.5M3.75 18h16.5" />',
        'close' => '<path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18" />',
        'chart-bar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 20.25h18M4.5 9.75 9 4.5l4.5 5.25L18 7.5v8.25H4.5V9.75Z" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 13.5c2.485 0 4.5-2.015 4.5-4.5S14.485 4.5 12 4.5 7.5 6.515 7.5 9s2.015 4.5 4.5 4.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 18.75a6.75 6.75 0 0 1 13.5 0" />',
        'credit-card' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5v10.5H3.75V6.75Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 10.5h16.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25h3.75" />',
        'banknotes' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5h12.75a1.5 1.5 0 0 1 1.5 1.5v7.5H3.75V7.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6h12.75a1.5 1.5 0 0 1 1.5 1.5v7.5h-1.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 12.75a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />',
        'document-chart' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v3.75h3.75M9 15.75v-2.25m2.25 2.25v-4.5m2.25 4.5V12" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.5h6l3 3v9.75a1.5 1.5 0 0 1-1.5 1.5h-7.5a1.5 1.5 0 0 1-1.5-1.5V6a1.5 1.5 0 0 1 1.5-1.5Z" />',
        'cog' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.108 1.204.165.397.505.71.93.781l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.109l-.893.15c-.425.071-.765.384-.93.781-.164.398-.142.854.108 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.164-.71.504-.781.929l-.15.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.02-.398-1.109-.94l-.15-.894c-.07-.425-.384-.765-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.505-.71-.93-.781l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.109l.894-.15c.424-.07.765-.384.93-.781.164-.398.142-.854-.108-1.204l-.527-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.108.397-.165.71-.505.781-.93l.15-.893Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
        'moon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79Z" />',
        'sun' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v-2.25m0 19.5v-2.25M7.5 12H5.25m13.5 0H16.5m-1.593-6.657 1.59-1.59m-9.9 9.9 1.59-1.59m0-6.72-1.59-1.59m9.9 9.9-1.59-1.59M12 7.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Z" />',
    ];

    $path = $icons[$name] ?? $icons['dots'];
@endphp

<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
    {{ $attributes->merge(['class' => $class]) }}>
    {!! $path !!}
</svg>
