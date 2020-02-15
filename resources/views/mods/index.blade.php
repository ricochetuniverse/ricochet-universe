@extends('layouts.app')

@section('title', 'Mods')
@section('description', 'Explore and play new environments, custom content and modifications for Ricochet Infinity.')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Mods</div>

                    <div class="card-body">
                        <p>
                            Explore and play new environments, custom content and modifications for Ricochet Infinity.
                        </p>

                        <p class="m-0">
                            To use these mods, download and place the .RED file on the same folder with
                            RicochetInfinity.exe
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @auth
            <div class="row mt-3">
                <div class="col text-right">
                    <a href="{{ action('ModsController@create') }}" class="btn btn-outline-secondary">Add new mod</a>
                </div>
            </div>
        @endauth

        <div class="row justify-content-center">
            @foreach ($mods as $mod)
                <div class="col-md-6 col-xl-4 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <span class="text-secondary font-weight-bold">{{ $mod->name }}</span> by {{ $mod->author }}

                            @if ($mod->description)
                                <p>{{ $mod->description }}</p>
                            @endif

                            @if ($mod->video_embed_source)
                                <div class="embed-responsive embed-responsive-4by3">
                                    <iframe width="316" height="240" src="{{ $mod->video_embed_source }}"
                                            allow="picture-in-picture" allowfullscreen
                                            class="embed-responsive-item"></iframe>
                                </div>
                            @endif

                            @if ($mod->download_link)
                                <div class="d-flex">
                                    <a href="{{ $mod->download_link }}"
                                       class="d-inline-flex align-items-center mt-3">
                                        <img src="{{ mix('download-circle.jpg') }}"
                                             alt=""
                                             width="38"
                                             height="38"
                                             class="mr-1">
                                        Download
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
