@extends('layouts.app', [
    'selected_navbar_item' => 'levels',
])

@section('title', 'Edit '.$levelSet->name)
@section('og:url', action('LevelController@edit', ['levelSet' => $levelSet]))
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ $levelSet->getPermalink() }}" class="btn btn-outline-primary">« Back</a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-header">Level set info</div>

                    <div class="card-body">
                        <p>Name: {{ $levelSet->name }}</p>

                        <p>SHA256 checksum: {{ $checksum }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-header">Edit level set</div>

                    <div class="card-body">
                        <form action="{{ action('LevelController@update', ['levelSet' => $levelSet]) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row mb-3">
                                <label for="created_at" class="col-sm-3 col-form-label">created_at timestamp</label>

                                <div class="col-sm-9">
                                    <input type="text" inputmode="numeric" class="form-control" name="created_at"
                                           id="created_at" required
                                           value="{{ old('created_at', $levelSet->created_at->unix()) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
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
                                    <button type="submit" class="btn btn-outline-primary">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
