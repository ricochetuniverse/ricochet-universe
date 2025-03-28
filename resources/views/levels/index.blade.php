@extends('layouts.app', [
    'selected_navbar_item' => 'levels',
])

@section('title', 'Levels')
@section('og:title', 'Ricochet Universe')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header">Levels</div>

                    <div class="card-body">
                        <p class="m-0">
                            These level sets include a great variety of levels. Some may have levels that are extremely
                            difficult, some may finish themselves with no user interaction, and some may have dozens of
                            rings on a single level.
                        </p>
                    </div>
                </div>

                @if ($levelSets->count() > 0)
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
                @endif

                <div class="d-md-none mb-3">
                    <form action="{{ action('LevelController@index') }}" method="GET" class="form-inline">
                        <input class="form-control w-100" type="search" name="search"
                               placeholder="Search level sets by name/author" title="Search level sets by name/author"
                               value="{{ $filteredInput['search'] }}">

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

                        @foreach ($filteredInput as $name => $value)
                            @if (is_string($value) && !in_array($name, ['search', 'orderBy', 'orderDir']))
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
                                <a href="{{ action('LevelController@index', array_merge($filteredInput, ['orderBy' => 'Name', 'orderDir' => $orderBy === 'Name' && $orderDirection === 'DESC' ? 'ASC' : 'DESC'])) }}"
                                   title="Sort by name">
                                    Name
                                </a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', array_merge($filteredInput, ['orderBy' => 'Rounds', 'orderDir' => $orderBy === 'Rounds' && $orderDirection === 'DESC' ? 'ASC' : 'DESC'])) }}"
                                   title="Sort by level count">
                                    Levels
                                </a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', array_merge($filteredInput, ['orderBy' => 'downloads', 'orderDir' => $orderBy === 'downloads' && $orderDirection === 'DESC' ? 'ASC' : 'DESC'])) }}"
                                   title="Sort by downloads">
                                    Downloads
                                </a>
                            </th>
                            <th class="text-nowrap">
                                <a href="{{ action('LevelController@index', array_merge($filteredInput, ['orderBy' => 'Date_Posted', 'orderDir' => $orderBy === 'Date_Posted' && $orderDirection === 'DESC' ? 'ASC' : 'DESC'])) }}"
                                   title="Sort by date posted">
                                    Date posted
                                </a>
                            </th>
                            <th>
                                <a href="{{ action('LevelController@index', array_merge($filteredInput, ['orderBy' => 'overall_rating', 'orderDir' => $orderBy === 'overall_rating' && $orderDirection === 'DESC' ? 'ASC' : 'DESC'])) }}"
                                   title="Sort by overall grade">
                                    Ratings
                                </a>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($levelSets as $levelSet)
                            <tr>
                                <td class="w-100">
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
                                             title="Designed for Ricochet Lost Worlds. Can be played in Ricochet Lost Worlds, Ricochet Lost Worlds: Recharged and Ricochet Infinity."
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
                                            class="d-md-none">, posted on
                                            <time datetime="{{ $levelSet->created_at->format('Y-m-d') }}">
                                                {{ $levelSet->created_at->format('Y-m-d') }}
                                            </time>
                                        </span>
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
                                                    @include('levels._ratings', ['levelSet' => $levelSet])
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell text-center">{{ $levelSet->rounds }}</td>
                                <td class="d-none d-md-table-cell text-center">
                                    {{ $levelSet->downloads > 0 ? number_format($levelSet->downloads) : 'N/A' }}
                                </td>
                                <td class="d-none d-md-table-cell text-center text-nowrap">
                                    <time datetime="{{ $levelSet->created_at->format('Y-m-d') }}">
                                        {{ $levelSet->created_at->format('Y-m-d') }}
                                    </time>
                                </td>
                                <td class="d-none d-md-table-cell no-gutters levelsTable__ratingColumn">
                                    @include('levels._ratings', ['levelSet' => $levelSet])
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
                            @if (strlen($filteredInput['search'] > 0))
                                No level sets found matching “{{ $filteredInput['search'] }}”.
                                <a href="{{ action('LevelController@index') }}">Show all level sets</a>
                            @else
                                No level sets found.
                                <a href="{{ action('LevelController@index') }}">Go back to main page</a>
                            @endif
                        </div>
                    </div>
                @endunless
            </div>
        </div>
    </div>
@endsection
