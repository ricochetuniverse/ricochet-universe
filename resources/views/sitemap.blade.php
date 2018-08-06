{!! '<'.'?' !!}xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($levelSets as $levelSet)
        <url>
            <loc>{{ action('LevelController@show', ['levelsetname' => $levelSet->name]) }}</loc>
            <lastmod>{{ $levelSet->updated_at->format('Y-m-d') }}</lastmod>
        </url>
    @endforeach
</urlset>
