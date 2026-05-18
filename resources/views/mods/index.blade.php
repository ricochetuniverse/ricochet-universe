@extends('layouts.app', [
    'selected_navbar_item' => 'mods',
])

@section('title', 'Mods')
@section('og:url', action('ModsController@index'))
@section('description', 'Explore and play new environments, custom content and modifications for Ricochet Infinity.')

@section('content')
    <div class="container-fluid vstack gap-3">
        <div class="row">
            <div class="col">
                <x-card>
                    <x-card.header>Mods</x-card.header>

                    <x-card.body>
                        <p>
                            Explore and play new environments, custom content and modifications for Ricochet Infinity.
                            For even more mods,
                            <a href="https://discord.com/channels/295184393109110785/390998849537310721">check out
                                #mod-showcase on Discord</a>.
                        </p>

                        <p class="m-0">
                            To use these mods, download and place the .RED file on the same folder with
                            RicochetInfinity.exe
                        </p>
                    </x-card.body>
                </x-card>
            </div>
        </div>

        @can('create', \App\Mod::class)
            <div class="row">
                <div class="col text-end">
                    <x-button href="{{ action('ModsController@create') }}" appearance="secondary">Add new mod</x-button>
                </div>
            </div>
        @endcan

        <div class="row row-gap-3">
            @foreach ($mods as $mod)
                <div class="col-md-6 col-xl-4">
                    <x-card>
                        <x-card.body>
                            <h2 class="fs-6 m-0 lh-base">
                                <span class="text-secondary fw-bold">{{ $mod->name }}</span> by {{ $mod->author }}
                            </h2>

                            @if ($mod->description)
                                <p>{{ $mod->description }}</p>
                            @endif

                            @if ($mod->video_embed_source)
                                <div class="ratio ratio-4x3">
                                    <iframe width="316" height="240" src="{{ $mod->video_embed_source }}"
                                            allow="picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @endif

                            @if ($mod->download_link)
                                <div class="d-flex">
                                    <a href="{{ $mod->download_link }}"
                                       class="d-inline-flex align-items-center mt-3">
                                        <img src="{{ asset('images/levelDownload.jpg') }}"
                                             alt=""
                                             width="38"
                                             height="38"
                                             class="me-1">
                                        Download
                                    </a>
                                </div>
                            @endif
                        </x-card.body>
                    </x-card>
                </div>
            @endforeach
        </div>
    </div>
@endsection
