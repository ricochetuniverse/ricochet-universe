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

                @if ($levelSets->total() > $levelSets->count())
                    <p>
                        Showing {{ number_format($levelSets->firstItem()).'-'.number_format($levelSets->lastItem()) }}
                        of {{ number_format($levelSets->total()) }} level sets
                    </p>
                @elseif ($levelSets->count() > 1)
                    <p>Showing {{ $levelSets->count() }} level sets</p>
                @else
                    <p>Showing {{ $levelSets->count() }} level set</p>
                @endif

                <div class="d-md-none mb-3">
                    <form action="{{ action('LevelController@index') }}" method="GET" class="form-inline">
                        <input class="form-control w-100" type="search" name="search"
                               placeholder="Search level sets by name/author" title="Search level sets by name/author"
                               value="{{ request()->input('search') }}">

                        <div class="w-100 mb-2"></div>

                        <select class="custom-select w-auto flex-grow-1" name="orderBy" title="Sort by">
                            @foreach ([
                                'Name' => 'Name',
                                'Rounds' => 'Level count',
                                'downloads' => 'Downloads',
                                'Date_Posted' => 'Date posted',
                                'Ratings' => 'Ratings'
                            ] as $value => $text)
                                <option value="{{ $value }}" {{ $orderBy === $value ? 'selected' : '' }}>
                                    {{ $text }}
                                </option>
                            @endforeach
                        </select>

                        <select class="custom-select w-auto flex-grow-1 ml-2" name="orderDir" title="Order by">
                            @foreach (['ASC' => 'Ascending', 'DESC' => 'Descending'] as $value => $text)
                                <option value="{{ $value }}" {{ $orderDirection === $value ? 'selected' : '' }}>
                                    {{ $text }}
                                </option>
                            @endforeach
                        </select>

                        @foreach (request()->input() as $name => $value)
                            @if (!in_array($name, ['search', 'orderBy', 'orderDir']))
                                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <div class="w-100 mb-2"></div>

                        <button type="submit" class="btn btn-outline-primary ml-auto">Search</button>
                    </form>
                </div>

                @unless ($levelSets->isEmpty())
                    <table class="table table-bordered">
                        <thead class="d-none d-md-table-header-group thead-light thead-clickable">
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
                                             class="float-right ml-3"
                                             data-toggle="tooltip">
                                    @else
                                        <img src="{{ asset('images/RLW.gif') }}"
                                             alt="Ricochet Lost Worlds logo"
                                             title="Designed for Ricochet Lost Worlds. Can be played in Ricochet Lost Worlds, Ricochet Recharged and Ricochet Infinity."
                                             width="32"
                                             height="32"
                                             class="float-right ml-3"
                                             data-toggle="tooltip">
                                    @endif

                                    <p class="m-0">
                                        <a href="{{ $levelSet->getPermalink() }}"
                                           class="text-secondary font-weight-bold">{{ $levelSet->name }}</a><span
                                            class="d-md-none"> ({{ $levelSet->rounds }}&nbsp;rounds)</span>
                                    </p>

                                    <p class="m-0">
                                        By <a
                                            href="{{ action('LevelController@index', ['author' => $levelSet->author]) }}"
                                            title="Find level sets created by {{ $levelSet->author }}">{{ $levelSet->author }}</a><span
                                            class="d-md-none">, posted on {{ $levelSet->created_at->format('Y-m-d') }}</span>
                                    </p>

                                    <div class="media mt-2">
                                        <a href="{{ $levelSet->getPermalink() }}"
                                           class="mr-2" tabindex="-1">
                                            <img
                                                src="{{ $levelSet->getImageUrl() }}"
                                                alt="Screenshot of {{ $levelSet->name }}" width="105" height="80"
                                                class="d-block">
                                        </a>

                                        <p class="media-body m-0 cursor-auto">{{ $levelSet->description }}</p>
                                    </div>

                                    @if (count($levelSet->mods) > 0)
                                        <div class="media mt-2">
                                            <strong class="mr-2">Mods:</strong>

                                            <div class="media-body">
                                                <a href="{{ action('ModsController@index') }}">
                                                    @foreach ($levelSet->mods as $mod)
                                                        {{ $mod->name }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                </a>
                                            </div>
                                        </div>
                                    @endif

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

                                    @if ($levelSet->overall_rating)
                                        <div class="d-md-none">
                                            <div class="row no-gutters mt-3">
                                                <span class="col-auto mr-2">Ratings:</span>

                                                <div class="col">
                                                    @include('levels._rating', ['levelSet' => $levelSet])
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell text-center">{{ $levelSet->rounds }}</td>
                                <td class="d-none d-md-table-cell text-center">
                                    {{ $levelSet->downloads > 0 ? number_format($levelSet->downloads) : 'N/A' }}
                                </td>
                                <td class="d-none d-md-table-cell text-center text-nowrap">{{ $levelSet->created_at->format('Y-m-d') }}</td>
                                <td class="d-none d-md-table-cell no-gutters levelsTable__ratingColumn">
                                    @include('levels._rating', ['levelSet' => $levelSet])
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mb-n3">
                        <div class="d-md-none">
                            {{ $levelSets->links('pagination::simple-bootstrap-4') }}
                        </div>

                        <div class="d-none d-md-block">
                            {{ $levelSets->links() }}
                        </div>
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
