@extends('layouts.app')

@section('title', 'Upload')
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

                        <form class="form-inline my-3" method="GET" action="{{ action('LevelController@index') }}">
                            <input class="form-control mr-2" type="search" name="search"
                                   placeholder="Search levels" aria-label="Search levels"
                                   value="{{ request()->input('search') }}">

                            <button class="btn btn-outline-primary" type="submit">Search</button>
                        </form>

                        <p>
                            Upload your level set to the #ricocheti-upload-channel on the
                            <a href="https://discord.gg/{{ config('ricochet.discord_invite') }}">Discord community</a>,
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

                                <div class="form-group row">
                                    <label for="url" class="col-sm-3 col-form-label">Level set URL</label>

                                    <div class="col-sm-9">
                                        <input type="url" class="form-control" name="url" id="url" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Name</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" id="name" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="date_posted" class="col-sm-3 col-form-label">Date posted</label>

                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="date_posted" id="date_posted"
                                               value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm offset-sm-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
