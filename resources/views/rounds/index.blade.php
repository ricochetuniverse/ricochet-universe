@extends('layouts.app', [
    'selected_navbar_item' => 'levels',
])

@section('title', 'Search rounds')
@section('og:title', 'Ricochet Universe')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header">Search rounds</div>

                    <div class="card-body">
                        <form class="d-flex" method="GET" action="{{ action('RoundsController@index') }}">
                            <input class="form-control me-2" type="search" name="search"
                                   placeholder="Search rounds by name" aria-label="Search rounds by name"
                                   value="{{ $search }}"
                                   minlength="{{ \App\Http\Controllers\RoundsController::MIN_INPUT }}">

                            <button class="btn btn-outline-primary" type="submit">Search</button>
                        </form>
                    </div>
                </div>

                @unless ($rounds->isEmpty())
                    @if ($rounds->count() > 0)
                        @if ($rounds->total() > $rounds->count())
                            <p>
                                Showing {{ number_format($rounds->firstItem()).'-'.number_format($rounds->lastItem()) }}
                                of {{ number_format($rounds->total()) }} rounds
                            </p>
                        @elseif ($rounds->count() > 1)
                            <p>Showing {{ $rounds->count() }} rounds</p>
                        @else
                            <p>Showing {{ $rounds->count() }} round</p>
                        @endif
                    @endif

                    <noscript>
                        <div class="alert alert-danger" role="alert">
                            Please enable JavaScript to view more round info.
                        </div>
                    </noscript>

                    <div class="card mb-3">
                        <ul class="list-group list-group-flush">
                            @foreach ($rounds as $round)
                                <li class="list-group-item p-3">
                                    <div class="d-flex align-items-center">
                                        <a href="#" class="me-3 js-open-round-info-modal"
                                           data-round-info="{{ $round->toRoundInfoJson() }}">
                                            @if ($round->image_file_name)
                                                <img
                                                    src="{{ $round->getImageUrl() }}"
                                                    alt="Screenshot of “{{ $round->name }}”"
                                                    width="105"
                                                    height="80"
                                                    loading="lazy">
                                            @endif
                                        </a>

                                        <div>
                                            <p>
                                                <a href="#" class="js-open-round-info-modal"
                                                   data-round-info="{{ $round->toRoundInfoJson() }}">
                                                    {{ $round->name }}
                                                </a>
                                            </p>

                                            <p class="m-0">
                                                #{{ $round->round_number }} on
                                                <a href="{{ $round->levelSet->getPermalink() }}"
                                                   class="link-secondary fw-bold">{{ $round->levelSet->name }}</a>
                                                by
                                                <a href="{{ action('LevelController@index', ['author' => $round->levelSet->author]) }}"
                                                   title="Find level sets created by {{ $round->levelSet->author }}">{{ $round->levelSet->author }}</a>
                                            </p>
                                        </div>
                                    </div>
                                </li>

                            @endforeach
                        </ul>
                    </div>

                    <div class="d-flex justify-content-center mb-n3">
                        <div class="d-md-none">
                            {{ $rounds->links('pagination::simple-bootstrap-4') }}
                        </div>

                        <div class="d-none d-md-block">
                            {{ $rounds->links() }}
                        </div>
                    </div>
                @elseif (strlen($search) > 0)
                    @if (strlen($search) < \App\Http\Controllers\RoundsController::MIN_INPUT)
                        <div class="alert alert-danger" role="alert">
                            Please enter a search input with {{ \App\Http\Controllers\RoundsController::MIN_INPUT }}+
                            characters.
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            No rounds found matching “{{ $search }}”.
                        </div>
                    @endif
                @endunless
            </div>
        </div>
    </div>
@endsection
