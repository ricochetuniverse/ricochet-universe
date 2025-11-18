@extends('layouts.app', [
    'selected_navbar_item' => 'levels',
])

@section('title', $levelSet->name . ' by ' . $levelSet->author)
@section('og:title', $levelSet->name . ' by ' . $levelSet->author)
@section('og:url', $levelSet->getPermalink())
@section('description', $levelSet->description)
@section('og:image', $levelSet->getImageUrl())
@section('robots', $levelSet->prerelease ? 'noindex,follow' : '')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <a href="{{ action('LevelController@index') }}" class="btn btn-outline-primary">
                            « Return to level set list
                        </a>
                    </div>

                    @can('update', $levelSet)
                        <a href="{{ action('LevelController@edit', ['levelSet' => $levelSet]) }}" class="btn btn-outline-secondary">
                            Edit
                        </a>
                    @endcan
                </div>

                @if ($levelSet->prerelease)
                    <div class="alert alert-warning mt-3" role="alert">
                        This level set is in prerelease and pending test verification before it is published to
                        the public.
                    </div>
                @endif

                @if ($brokenLevelSetWarning)
                    <div class="alert alert-danger mt-3" role="alert">
                        This level set can’t be parsed properly, it might be broken or can’t be completed.
                    </div>
                @endif

                <div class="card mt-3">
                    <div class="card-body">
                        <strong class="{{ !$levelSet->prerelease ? 'text-secondary' : 'levelsName--prerelease' }}">
                            {{ $levelSet->name }}@if ($levelSet->prerelease)
                                (PRERELEASE)
                            @endif
                        </strong>

                        <div>
                            By <a href="{{ action('LevelController@index', ['author' => $levelSet->author]) }}"
                                  title="Find level sets created by {{ $levelSet->author }}">{{ $levelSet->author }}</a>
                        </div>

                        <div class="d-flex mt-3">
                            <img
                                src="{{ $levelSet->getImageUrl() }}"
                                alt="Screenshot of {{ $levelSet->name }}" width="105" height="80"
                                class="d-block me-3">

                            <p class="m-0 cursor-auto">{{ $levelSet->description }}</p>
                        </div>

                        <div class="d-table mt-3">
                            <div class="d-table-row">
                                <div class="d-table-cell pe-2">Number of rounds:</div>
                                <div class="d-table-cell">{{ $levelSet->rounds }}</div>
                            </div>

                            @if ($levelSet->downloads > 0)
                                <div class="d-table-row">
                                    <div class="d-table-cell pe-2">Downloads:</div>
                                    <div class="d-table-cell">{{ number_format($levelSet->downloads) }}</div>
                                </div>
                            @endif

                            <div class="d-table-row">
                                <div class="d-table-cell pe-2">Date posted:</div>
                                <div class="d-table-cell">
                                    <time datetime="{{ $levelSet->created_at->format('Y-m-d') }}">
                                        {{ $levelSet->created_at->format('Y-m-d') }}
                                    </time>
                                </div>
                            </div>
                        </div>

                        @if ($levelSet->overall_rating)
                            <div class="d-flex mt-3">
                                <span class="me-2">Ratings:</span>

                                <div>
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
                            <div class="d-flex mt-3">
                                <span class="me-2">Tags:</span>

                                <div>
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

                        <div class="d-flex align-items-center mt-3">
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

                            <div class="ms-3">
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
                                     class="me-1">
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
                            <div class="roundInfo__wrapper">
                                @foreach ($levelSet->levelRounds as $round)
                                    <a href="#" class="roundInfo__link js-open-round-info-modal"
                                       data-round-info="{{ $round->toRoundInfoJson() }}">
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
