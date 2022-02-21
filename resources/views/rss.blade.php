{!! '<'.'?' !!}xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <title>{{ config('app.name') }}</title>
        <description>Explore, download and play Ricochet Infinity and Ricochet Lost Worlds level sets created by the community</description>
        <link>{{ action('HomeController@index') }}</link>

        @foreach ($levelSets as $levelSet)
            <item>
                <title>{{ $levelSet->name }} by {{ $levelSet->author }}</title>
                <link>{{ $levelSet->getPermalink() }}</link>
                <description>{{ $levelSet->description }}</description>
            </item>
        @endforeach
    </channel>
</rss>
