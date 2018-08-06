@extends('layouts.app')

@section('title', 'Levels')
@section('og:title', 'Ricochet Levels')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header">Levels</div>

                    <div class="card-body">
                        <p>
                            These level sets include a great variety of levels. Some may have levels that are extremely
                            difficult, some may finish themselves with no user interaction, and some may have dozens of
                            rings on a single level.
                        </p>

                        <p class="m-0">
                            Downloads and ratings are frozen in time and will not change.
                        </p>
                    </div>
                </div>

                @unless ($levelSets->isEmpty())
                    <table class="table table-bordered">
                        <thead class="thead-light thead-clickable">
                        <tr>
                            <th>
                                <a href="{{ action('LevelController@index', array_merge(request()->input(), ['orderBy' => 'Name', 'orderDir' => $orderBy === 'Name' && $orderDirection === 'ASC' ? 'DESC' : 'ASC'])) }}"
                                   title="Sort by name">
                                    Name
                                </a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', array_merge(request()->input(), ['orderBy' => 'Rounds', 'orderDir' => $orderBy === 'Rounds' && $orderDirection === 'DESC' ? 'ASC' : 'DESC'])) }}"
                                   title="Sort by level count">
                                    Levels
                                </a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', array_merge(request()->input(), ['orderBy' => 'downloads', 'orderDir' => $orderBy === 'downloads' && $orderDirection === 'DESC' ? 'ASC' : 'DESC'])) }}"
                                   title="Sort by downloads">
                                    Downloads
                                </a>
                            </th>
                            <th class="text-nowrap">
                                <a href="{{ action('LevelController@index', array_merge(request()->input(), ['orderBy' => 'Date_Posted', 'orderDir' => $orderBy === 'Date_Posted' && $orderDirection === 'DESC' ? 'ASC' : 'DESC'])) }}"
                                   title="Sort by date posted">
                                    Date posted
                                </a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', array_merge(request()->input(), ['orderBy' => 'overall_rating', 'orderDir' => $orderBy === 'overall_rating' && $orderDirection === 'DESC' ? 'ASC' : 'DESC'])) }}"
                                   title="Sort by overall grade">
                                    Ratings
                                </a>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($levelSets as $levelSet)
                            <tr>
                                <td>
                                    @if ($levelSet->isDesignedForInfinity())
                                        <img src="{{ asset('images/RI.gif') }}"
                                             alt="Ricochet Infinity logo"
                                             title="Designed for Ricochet Infinity. Can only be played in Ricochet Infinity."
                                             width="32"
                                             height="32"
                                             class="float-right"
                                             data-toggle="tooltip">
                                    @else
                                        <img src="{{ asset('images/RLW.gif') }}"
                                             alt="Ricochet Lost Worlds logo"
                                             title="Designed for Ricochet Lost Worlds. Can be played in Ricochet Lost Worlds, Ricochet Recharged and Ricochet Infinity."
                                             width="32"
                                             height="32"
                                             class="float-right"
                                             data-toggle="tooltip">
                                    @endif

                                    <p class="m-0">
                                        <a href="{{ action('LevelController@show', ['levelsetname' => $levelSet->name]) }}"
                                           class="font-weight-bold">
                                            {{ $levelSet->name }}
                                        </a>
                                    </p>

                                    <p class="m-0">
                                        by <a
                                            href="{{ action('LevelController@index', ['author' => $levelSet->author]) }}"
                                            title="Find level sets created by {{ $levelSet->author }}">{{ $levelSet->author }}</a>
                                    </p>

                                    <div class="media mt-2">
                                        <a href="{{ action('LevelController@show', ['levelsetname' => $levelSet->name]) }}"
                                           class="mr-2" tabindex="-1">
                                            <img
                                                src="{{ $levelSet->getImageUrl() }}"
                                                alt="Screenshot of {{ $levelSet->name }}" width="105" height="80"
                                                class="d-block">
                                        </a>

                                        <p class="media-body m-0 cursor-auto">{{ $levelSet->description }}</p>
                                    </div>

                                    @if (count($levelSet->tagged) > 0)
                                        <div class="media mt-2">
                                            <strong class="mr-2">Tags:</strong>

                                            <div class="media-body">
                                                @foreach ($levelSet->tagged as $tagged)
                                                    <a href="{{ action('LevelController@index', ['tag' => $tagged->tag_name]) }}"
                                                       title="Find other level sets with the {{ $tagged->tag_name }} tag"
                                                    >{{ $tagged->tag_name }}</a>{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex">
                                        <a href="{{ $levelSet->alternate_download_url }}"
                                           class="d-inline-flex align-items-center mt-2">
                                            <img src="{{ asset('images/levelDownload.jpg') }}"
                                                 alt=""
                                                 width="38"
                                                 height="38"
                                                 class="mr-1">
                                            Download
                                        </a>
                                    </div>
                                </td>
                                <td class="text-center">{{ $levelSet->rounds }}</td>
                                <td class="text-center">{{ number_format($levelSet->downloads) }}</td>
                                <td class="text-center text-nowrap">{{ $levelSet->created_at->format('Y-m-d') }}</td>
                                <td class="no-gutters levelsTable__ratingColumn">
                                    @include('levels._rating', ['levelSet' => $levelSet])
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $levelSets->links() }}
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            @if (request()->input('search'))
                                No level sets found matching “{{ request()->input('search') }}”.
                                <a href="{{ action('LevelController@index') }}">Show all level sets</a>
                            @else
                                No level sets found.
                                <a href="{{ action('LevelController@index') }}">Go back to main page?</a>
                            @endif
                        </div>
                    </div>
                @endunless
            </div>
        </div>
    </div>
@endsection
