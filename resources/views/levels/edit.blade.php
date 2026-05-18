@extends('layouts.app', [
    'selected_navbar_item' => 'levels',
])

@section('title', 'Edit '.$levelSet->name)
@section('og:url', action('LevelController@edit', ['levelSet' => $levelSet]))
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid vstack gap-3">
        <div class="row">
            <div class="col">
                <x-button href="{{ $levelSet->getPermalink() }}">« Back</x-button>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-card>
                    <x-card.header>Level set info</x-card.header>

                    <x-card.body>
                        <p>Name: {{ $levelSet->name }}</p>

                        <p>SHA256 checksum: {{ $checksum }}</p>
                    </x-card.body>
                </x-card>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-card>
                    <x-card.header tag="h2">Edit level set</x-card.header>

                    <x-card.body>
                        <form action="{{ action('LevelController@update', ['levelSet' => $levelSet]) }}" method="POST"
                              class="vstack gap-3">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                <label for="created_at" class="col-sm-3 col-form-label">created_at timestamp</label>

                                <div class="col-sm-9">
                                    <input type="text" inputmode="numeric" class="form-control" name="created_at"
                                           id="created_at" required
                                           value="{{ old('created_at', $levelSet->created_at->unix()) }}">
                                </div>
                            </div>

                            <div class="row">
                                <label for="download_url" class="col-sm-3 col-form-label">Download URL</label>

                                <div class="col-sm-9">
                                    <input type="url" class="form-control" name="download_url" id="download_url"
                                           required
                                           value="{{ old('download_url', $levelSet->alternate_download_url) }}">

                                    <div class="form-text">
                                        Changing the download URL will not re-download or reprocess the file.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm offset-sm-3">
                                    <x-button type="submit">Edit</x-button>
                                </div>
                            </div>
                        </form>
                    </x-card.body>
                </x-card>
            </div>
        </div>
    </div>
@endsection
