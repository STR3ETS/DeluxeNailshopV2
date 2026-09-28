{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach ($paginas as $pagina)
    <url>
        <loc>{{ $pagina['loc'] }}</loc>
@if (! empty($pagina['lastmod']))
        <lastmod>{{ $pagina['lastmod']->toAtomString() }}</lastmod>
@endif
    </url>
@endforeach
@foreach ($producten as $product)
    <url>
        <loc>{{ route('product.show', $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
@foreach (array_filter([$product->image, $product->image_2]) as $foto)
        <image:image>
            <image:loc>{{ asset($foto) }}</image:loc>
        </image:image>
@endforeach
    </url>
@endforeach
</urlset>
