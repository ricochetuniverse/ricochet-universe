@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ action('LevelController@index') }}" class="btn btn-outline-primary">
                    « Return to level set list
                </a>

                <div class="card my-3">
                    <div class="card-body">
                        <div class="text-secondary font-weight-bold">
                            {{ $levelSet->name }}
                        </div>

                        <div>
                            by <a href="{{ action('LevelController@index', ['author' => $levelSet->author]) }}"
                                  title="Find level sets created by {{ $levelSet->author }}">{{ $levelSet->author }}</a>
                        </div>

                        <div class="mt-3 cursor-auto">
                            {{ $levelSet->description }}
                        </div>

                        <div class="d-table mt-3">
                            <div class="d-table-row">
                                <div class="d-table-cell pr-2">Date posted:</div>
                                <div class="d-table-cell">{{ $levelSet->created_at->format('Y-m-d') }}</div>
                            </div>

                            <div class="d-table-row">
                                <div class="d-table-cell pr-2">Number of rounds:</div>
                                <div class="d-table-cell">{{ $levelSet->rounds }}</div>
                            </div>

                            <div class="d-table-row">
                                <div class="d-table-cell pr-2">Downloads:</div>
                                <div class="d-table-cell">{{ number_format($levelSet->downloads) }}</div>
                            </div>
                        </div>

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
                                    Worlds, Ricochet Recharged and Ricochet Infinity.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex">
                            <a href="{{ $levelSet->alternate_download_url }}"
                               class="d-inline-flex align-items-center font-weight-bold">
                                <img src="{{ asset('images/levelDownload.jpg') }}"
                                     alt=""
                                     width="38"
                                     height="38"
                                     class="mr-1">
                                Download this level set
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Round images</div>

                    <div class="card-body">
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
                                       data-round-image-url="{{ $round->getImageUrl() }}">
                                        <img
                                            src="{{ $round->getImageUrl() }}"
                                            alt="Screenshot of “{{ $round->name }}”" width="105" height="80"
                                            class="roundInfo__image">

                                        <span class="roundInfo__name">{{ $loop->index + 1 }}: {{ $round->name }}</span>

                                        @if (!$authorIsSameForAllRounds)
                                            <span class="roundInfo__author">
                                                by @if (strlen($round->author) > 0)
                                                    {{ $round->author }}
                                                @else
                                                    <em>(no author)</em>
                                                @endif
                                            </span>
                                        @endif

                                        @if (strlen($round->note1) > 0 || strlen($round->note2) > 0 || strlen($round->note3) > 0 || strlen($round->note4) > 0 || strlen($round->note5) > 0)
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
