{{-- The XML declaration is split: a literal "?>" would close the compiled PHP block. --}}
{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
@foreach ($url['links'] as $link)
        <xhtml:link rel="alternate" hreflang="{{ $link['hreflang'] }}" href="{{ $link['href'] }}"/>
@endforeach
    </url>
@endforeach
</urlset>
