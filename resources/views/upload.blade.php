@extends('layouts.app', [
    'selected_navbar_item' => 'levels',
])

@section('title', 'Upload')
@section('og:url', action('UploadController@index'))
@section('description', 'Upload and share your Ricochet level sets.')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Upload your levels</div>

                    <div class="card-body">
                        <p class="m-0">
                            New level sets need to be manually added by the site admin for now, self-service uploads are
                            planned in the future.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Instructions</div>

                    <div class="card-body">
                        <p>
                            First, ensure your level set is ready to be shared by opening it on the level editor, then
                            click File&nbsp;→&nbsp;Share&nbsp;LevelSet. The level set’s author and description should be
                            filled in, and all the levels and rings should be tested.
                        </p>

                        <p>
                            Search for the name of your level set to ensure it isn’t already taken, each level set in
                            the catalog must have a unique name.
                        </p>

                        <form class="d-flex my-3" method="GET" action="{{ action('LevelController@index') }}">
                            <input class="form-control me-2" type="search" name="search"
                                   placeholder="Search levels" aria-label="Search levels">

                            <button class="btn btn-outline-primary" type="submit">Search</button>
                        </form>

                        <p>
                            Upload your level set to the #ricocheti-upload-channel on the
                            <a href="{{ action('DiscordRedirectController@index') }}">Discord community</a>,
                            the site admin will notice it and download it.
                        </p>

                        <p class="m-0">
                            The site admin will do some basic sanity checking such as ensuring the level set will load
                            in the game and there are no errors on the Share LevelSet screen.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Other upload questions</div>

                    <div class="card-body">
                        <p>
                            <strong>Can I overwrite my uploaded level set with a new version?</strong>
                        </p>

                        <p class="m-0">
                            Overwriting existing level sets with the exact same name is problematic as anyone who has
                            already downloaded your level set will not be able to see the new version, the game does not
                            have a built-in mechanism to differentiate level set versions. If you still like to
                            overwrite it anyway, contact the site admin.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @auth
            <div class="row mt-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ action('UploadController@store') }}" method="POST">
                                {{ csrf_field() }}

                                <div class="row mb-3">
                                    <label for="url" class="col-sm-3 col-form-label">Level set URL</label>

                                    <div class="col-sm-9">
                                        <input type="url" class="form-control" name="url" id="url" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="name" class="col-sm-3 col-form-label">Name</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" id="name" value="" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="timestamp" class="col-sm-3 col-form-label">Unix timestamp</label>

                                    <div class="col-sm-9">
                                        <input type="text" inputmode="numeric" class="form-control" name="timestamp"
                                               id="timestamp" value="{{ Carbon\Carbon::now()->unix() }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm offset-sm-3">
                                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
    </div>
@endsection
