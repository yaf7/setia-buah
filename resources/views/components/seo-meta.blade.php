@props([
    'title' => 'Setia Buah - Solusi Buah Segar Petani Lokal',
    'description' => 'Beli buah-buahan segar langsung dari petani lokal terbaik dengan harga yang bersaing dan kualitas terjamin.',
    'image' => asset('logo.png'),
    'url' => url()->current(),
    'jsonLd' => null,
])

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $url }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="{{ $image }}">

@if($jsonLd)
<script type="application/ld+json">
{!! collect($jsonLd)->toJson() !!}
</script>
@endif