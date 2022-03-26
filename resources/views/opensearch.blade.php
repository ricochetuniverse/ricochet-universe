{!! '<'.'?' !!}xml version="1.0" encoding="UTF-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"
                       xmlns:moz="http://www.mozilla.org/2006/browser/search/">
    <ShortName>Ricochet Levels Search</ShortName>
    <Description>Search Ricochet level sets by name/author</Description>
    <Image width="16" height="16" type="image/x-icon">{{ url('favicon.ico') }}</Image>
    <Url type="text/html"
         template="{{ str_replace('searchTerms', '{searchTerms}', action('LevelController@index', ['search' => 'searchTerms'])) }}"/>
    <Url type="application/opensearchdescription+xml" rel="self"
         template="{{ action('API\OpensearchController@index') }}"/>
    <moz:SearchForm>{{ action('LevelController@index') }}</moz:SearchForm>
</OpenSearchDescription>
