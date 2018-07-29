@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header">Levels</div>

                    <div class="card-body">
                        These Level Sets include a great variety of levels. Some may have levels that are extremely
                        difficult, some may finish themselves with no user interaction, and some may have dozens of
                        rings on a single level.
                    </div>
                </div>

                @unless ($levelSets->isEmpty())
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>
                                <a href="{{ action('LevelController@index', ['orderBy' => 'Name', 'orderDir' => $orderBy === 'Name' && $orderDirection === 'ASC' ? 'DESC' : 'ASC']) }}">Name</a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', ['orderBy' => 'Rounds', 'orderDir' => $orderBy === 'Rounds' && $orderDirection === 'ASC' ? 'DESC' : 'ASC']) }}">Levels</a>
                            </th>
                            <th class="text-nowrap">
                                <a href="{{ action('LevelController@index', ['orderBy' => 'Date_Posted', 'orderDir' => $orderBy === 'Date_Posted' && $orderDirection === 'ASC' ? 'DESC' : 'ASC']) }}">Date
                                    posted</a>
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
                                             class="float-right">
                                    @else
                                        <img src="{{ asset('images/RLW.gif') }}"
                                             alt="Can be played in Ricochet Lost Worlds, Ricochet Recharged and Ricochet Infinity"
                                             title="Can be played in Ricochet Lost Worlds, Ricochet Recharged and Ricochet Infinity"
                                             width="32"
                                             height="32"
                                             class="float-right">
                                    @endif

                                    <p class="m-0"><a href="" class="font-weight-bold">{{ $levelSet->name }}</a></p>
                                    <p class="m-0">by <a href="">{{ $levelSet->author }}</a></p>
                                    <p class="m-0">{{ $levelSet->description }}</p>

                                    @if (count($levelSet->tagged) > 0)
                                        <p class="m-0">
                                            <strong>Tags:</strong>
                                            @foreach ($levelSet->tagged as $tagged)
                                                <a href=""
                                                   title="Find other level sets with the {{ $tagged->tag_name }} tag"
                                                >{{ $tagged->tag_name }}</a>{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </p>
                                    @endif

                                    <div class="d-flex align-items-center mt-2">
                                        <a href="{{ $levelSet->alternate_download_url }}" class="mr-1">
                                            <img src="{{ asset('images/levelDownload.jpg') }}"
                                                 alt=""
                                                 title="Download this level set"
                                                 width="38"
                                                 height="38">
                                        </a>

                                        <a href="{{ $levelSet->alternate_download_url }}">Download</a>
                                    </div>
                                </td>
                                <td class="text-center">{{ $levelSet->rounds }}</td>
                                <td class="text-center text-nowrap">{{ $levelSet->created_at->format('Y-m-d') }}</td>
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
