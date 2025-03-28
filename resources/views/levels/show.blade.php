@extends('layouts.app', [
    'selected_navbar_item' => 'levels',
])

@section('title', $levelSet->name . ' by ' . $levelSet->author)
@section('og:title', $levelSet->name . ' by ' . $levelSet->author)
@section('og:url', $levelSet->getPermalink())
@section('description', $levelSet->description)
@section('og:image', $levelSet->getImageUrl())

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ action('LevelController@index') }}" class="btn btn-outline-primary">
                    « Return to level set list
                </a>

                @if ($brokenLevelSetWarning)
                    <div class="alert alert-danger mt-3" role="alert">
                        This level set can’t be parsed properly, it might be broken or can’t be completed.
                    </div>
                @endif

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="text-secondary font-weight-bold">
                            {{ $levelSet->name }}
                        </div>

                        <div>
                            By <a href="{{ action('LevelController@index', ['author' => $levelSet->author]) }}"
                                  title="Find level sets created by {{ $levelSet->author }}">{{ $levelSet->author }}</a>
                        </div>

                        <div class="media mt-3">
                            <img
                                src="{{ $levelSet->getImageUrl() }}"
                                alt="Screenshot of {{ $levelSet->name }}" width="105" height="80"
                                class="d-block mr-3">

                            <p class="media-body m-0 cursor-auto">{{ $levelSet->description }}</p>
                        </div>

                        <div class="d-table mt-3">
                            <div class="d-table-row">
                                <div class="d-table-cell pr-2">Number of rounds:</div>
                                <div class="d-table-cell">{{ $levelSet->rounds }}</div>
                            </div>

                            @if ($levelSet->downloads > 0)
                                <div class="d-table-row">
                                    <div class="d-table-cell pr-2">Downloads:</div>
                                    <div class="d-table-cell">{{ number_format($levelSet->downloads) }}</div>
                                </div>
                            @endif

                            <div class="d-table-row">
                                <div class="d-table-cell pr-2">Date posted:</div>
                                <div class="d-table-cell">
                                    <time datetime="{{ $levelSet->created_at->format('Y-m-d') }}">
                                        {{ $levelSet->created_at->format('Y-m-d') }}
                                    </time>
                                </div>
                            </div>
                        </div>

                        @if ($levelSet->overall_rating)
                            <div class="row no-gutters mt-3">
                                <span class="col-auto mr-2">Ratings:</span>

                                <div class="col">
                                    @include('levels._ratings', [
                                        'levelSet' => $levelSet,
                                        'showTooltipExplanation' => false,
                                        'showPlayerCount' => true,
                                    ])

                                    <p class="mt-3 mb-0">Level sets are graded in Ricochet Infinity.</p>
                                </div>
                            </div>
                        @endif

                        @if (count($levelSet->tagged) > 0)
                            <div class="media mt-3">
                                <span class="mr-2">Tags:</span>

                                <div class="media-body">
                                    @foreach ($levelSet->tagged as $tagged)
                                        <a href="{{ action('LevelController@index', ['tag' => $tagged->tag_name]) }}"
                                           title="Find other level sets with the {{ $tagged->tag_name }} tag"
                                        >{{ $tagged->tag_name }}</a>{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (count($levelSet->mods) > 0)
                            <div class="alert alert-info mt-3">
                                <p class="m-0">
                                    @if (count($levelSet->mods) === 1)
                                        This level set requires the
                                        <a href="{{ action('ModsController@index') }}" class="alert-link">
                                            {{ $levelSet->mods->first()->name }} mod</a>
                                        to play.
                                    @else
                                        This level set requires these mods to play:
                                        <a href="{{ action('ModsController@index') }}" class="alert-link">
                                            @foreach ($levelSet->mods as $mod)
                                                {{ $mod->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </a>
                                    @endif
                                </p>
                            </div>
                        @endif

                        <div class="media align-items-center mt-3">
                            @if ($levelSet->isDesignedForInfinity())
                                <img src="{{ asset('images/RI.gif') }}"
                                     alt="Ricochet Infinity logo"
                                     width="32"
                                     height="32">
                            @else
                                <img src="{{ asset('images/RLW.gif') }}"
                                     alt="Ricochet Lost Worlds logo"
                                     width="32"
                                     height="32">
                            @endif

                            <div class="media-body ml-3">
                                @if ($levelSet->isDesignedForInfinity())
                                    Designed for Ricochet Infinity. This level set can only be played in Ricochet
                                    Infinity.
                                @else
                                    Designed for Ricochet Lost Worlds. This level set can be played in Ricochet Lost
                                    Worlds, Ricochet Lost Worlds: Recharged and Ricochet Infinity.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex">
                            <a href="{{ action('API\\LevelDownloadController@download', ['File' => 'downloads/raw/'.$levelSet->name.$levelSet->getFileExtension()]) }}"
                               class="d-inline-flex align-items-center">
                                <img src="{{ asset('images/levelDownload.jpg') }}"
                                     alt=""
                                     width="38"
                                     height="38"
                                     class="mr-1">
                                Download this level set
                            </a>
                        </div>

                        <p class="m-0 mt-3">
                            You can also download and play this level set using
                            <a href="{{ action('ReviverController@index') }}">the in-game catalog</a>.
                        </p>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">Round info</div>

                    <div class="card-body">
                        <noscript>
                            <div class="alert alert-danger" role="alert">
                                Please enable JavaScript to view more round info.
                            </div>
                        </noscript>

                        @unless ($levelSet->levelRounds->isEmpty())
                            <div class="row roundInfo__wrapper">
                                @foreach ($levelSet->levelRounds as $round)
                                    <a href="#" class="roundInfo__link"
                                       data-round-count="{{ $loop->index + 1 }}"
                                       data-round-name="{{ $round->name }}"
                                       @if (strlen($round->author) > 0)data-round-author="{{ $round->author }}" @endif
                                       @if (strlen($round->note1) > 0)data-round-note-1="{{ $round->note1 }}" @endif
                                       @if (strlen($round->note2) > 0)data-round-note-2="{{ $round->note2 }}" @endif
                                       @if (strlen($round->note3) > 0)data-round-note-3="{{ $round->note3 }}" @endif
                                       @if (strlen($round->note4) > 0)data-round-note-4="{{ $round->note4 }}" @endif
                                       @if (strlen($round->note5) > 0)data-round-note-5="{{ $round->note5 }}" @endif
                                       @if (strlen($round->source) > 0)data-round-source="{{ $round->source }}" @endif
                                       @if ($round->image_file_name)data-round-image-url="{{ $round->getImageUrl() }}" @endif>
                                        @if ($round->image_file_name)
                                            <img
                                                src="{{ $round->getImageUrl() }}"
                                                alt="Screenshot of “{{ $round->name }}”"
                                                width="105"
                                                height="80"
                                                class="roundInfo__image"
                                                loading="lazy">
                                        @endif

                                        <span class="roundInfo__name">
                                            {{ $round->round_number }}: {{ $round->name }}
                                        </span>

                                        @if (!$authorIsSameForAllRounds)
                                            <span class="roundInfo__author">
                                                by @if (strlen($round->author) > 0)
                                                    {{ $round->author }}
                                                @else
                                                    <em>(not set)</em>
                                                @endif
                                            </span>
                                        @endif

                                        @if ($round->shouldShowViewNotesButton())
                                            <span class="d-block mt-2 btn btn-outline-primary">View notes</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="m-0">Generating images... reload after a few seconds</p>
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
