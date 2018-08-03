@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
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
                            Downloads and ratings are frozen ‘in time’ and will not change.
                        </p>
                    </div>
                </div>

                @unless ($levelSets->isEmpty())
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>
                                <a href="{{ action('LevelController@index', ['orderBy' => 'Name', 'orderDir' => $orderBy === 'Name' && $orderDirection === 'ASC' ? 'DESC' : 'ASC']) }}">
                                    Name
                                </a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', ['orderBy' => 'Rounds', 'orderDir' => $orderBy === 'Rounds' && $orderDirection === 'DESC' ? 'ASC' : 'DESC']) }}">
                                    Levels
                                </a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', ['orderBy' => 'downloads', 'orderDir' => $orderBy === 'downloads' && $orderDirection === 'DESC' ? 'ASC' : 'DESC']) }}">
                                    Downloads
                                </a>
                            </th>
                            <th class="text-nowrap">
                                <a href="{{ action('LevelController@index', ['orderBy' => 'Date_Posted', 'orderDir' => $orderBy === 'Date_Posted' && $orderDirection === 'DESC' ? 'ASC' : 'DESC']) }}">
                                    Date posted
                                </a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', ['orderBy' => 'overall_rating', 'orderDir' => $orderBy === 'overall_rating' && $orderDirection === 'DESC' ? 'ASC' : 'DESC']) }}">
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
                                             alt="Can only be played in Ricochet Infinity"
                                             title="Can only be played in Ricochet Infinity"
                                             width="32"
                                             height="32"
                                             class="float-right"
                                             data-toggle="tooltip">
                                    @else
                                        <img src="{{ asset('images/RLW.gif') }}"
                                             alt="Can be played in Ricochet Lost Worlds, Ricochet Recharged and Ricochet Infinity"
                                             title="Can be played in Ricochet Lost Worlds, Ricochet Recharged and Ricochet Infinity"
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
                                        by <a href="{{ action('LevelController@index', ['author' => $levelSet->author]) }}">{{ $levelSet->author }}</a>
                                    </p>

                                    <div class="media mt-2">
                                        <a href="{{ action('LevelController@show', ['levelsetname' => $levelSet->name]) }}"
                                           class="mr-2">
                                            <img
                                                src="{{ \App\Services\CatalogService::getFallbackImageUrl() }}{{ $levelSet->image_url }}"
                                                alt="Screenshot of {{ $levelSet->name }}" width="105" height="80">
                                        </a>

                                        <div class="media-body cursor-auto">
                                            <p class="m-0">{{ $levelSet->description }}</p>
                                        </div>
                                    </div>

                                    @if (count($levelSet->tagged) > 0)
                                        <p class="m-0 mt-2">
                                            <strong>Tags:</strong>
                                            @foreach ($levelSet->tagged as $tagged)
                                                <a href="{{ action('LevelController@index', ['tag' => $tagged->tag_name]) }}"
                                                   title="Find other level sets with the {{ $tagged->tag_name }} tag"
                                                >{{ $tagged->tag_name }}</a>{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </p>
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
                                <td class="text-center">
                                    @if ($levelSet->overall_rating)
                                        <div class="row no-gutters">
                                            <div class="col d-flex align-self-center justify-content-end mr-2">
                                                <img src="{{ asset('images/ratingOverall.jpg') }}"
                                                     alt="Overall grade"
                                                     title="Average overall grade from {{ $levelSet->overall_rating_count }} players: {{ \App\Services\RatingGradeConverter::getGrade($levelSet->overall_rating) }}. Level sets are graded in Ricochet Infinity."
                                                     width="20"
                                                     height="20">
                                            </div>
                                            <div class="col text-left">
                                                {{ \App\Services\RatingGradeConverter::getGrade($levelSet->overall_rating) }}
                                            </div>

                                            <div class="w-100 mt-2"></div>

                                            <div class="col d-flex align-self-center justify-content-end mr-2">
                                                <img src="{{ asset('images/ratingGameplay.jpg') }}"
                                                     alt="Gameplay grade"
                                                     title="Average gameplay grade from {{ $levelSet->fun_rating_count }} players: {{ \App\Services\RatingGradeConverter::getGrade($levelSet->fun_rating) }}. Level sets are graded in Ricochet Infinity."
                                                     width="20"
                                                     height="20">
                                            </div>
                                            <div class="col text-left">
                                                {{ \App\Services\RatingGradeConverter::getGrade($levelSet->fun_rating) }}
                                            </div>
                                            <div class="w-100 mt-2"></div>

                                            <div class="col d-flex align-self-center justify-content-end mr-2">
                                                <img src="{{ asset('images/ratingVisuals.jpg') }}"
                                                     alt="Visuals grade"
                                                     title="Average visuals grade from {{ $levelSet->graphics_rating_count }} players: {{ \App\Services\RatingGradeConverter::getGrade($levelSet->graphics_rating) }}. Level sets are graded in Ricochet Infinity."
                                                     width="20"
                                                     height="20">
                                            </div>
                                            <div class="col text-left">
                                                {{ \App\Services\RatingGradeConverter::getGrade($levelSet->graphics_rating) }}
                                            </div>
                                        </div>
                                    @endif
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
