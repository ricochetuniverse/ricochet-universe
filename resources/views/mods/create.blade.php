@extends('layouts.app', [
    'selected_navbar_item' => 'mods',
])

@section('title', 'Add new mod')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ action('ModsController@index') }}" class="btn btn-outline-primary">« Back</a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-header">Add new mod</div>

                    <div class="card-body">
                        <form action="{{ action('ModsController@store') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="row mb-3">
                                <label for="name" class="col-sm-3 col-form-label">Name</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="author" class="col-sm-3 col-form-label">Author</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="author" id="author" value="" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description" class="col-sm-3 col-form-label">Description</label>

                                <div class="col-sm-9">
                                    <textarea class="form-control" name="description" id="description"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="video_embed_source" class="col-sm-3 col-form-label">
                                    Video embed source
                                </label>

                                <div class="col-sm-9">
                                    <input type="url" class="form-control" name="video_embed_source"
                                           id="video_embed_source" value="">

                                    <div class="form-text">
                                        Website Content-Security-Policy may prohibit video embeds other than YouTube.
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="download_link" class="col-sm-3 col-form-label">Download link</label>

                                <div class="col-sm-9">
                                    <input type="url" class="form-control" name="download_link" id="download_link"
                                           value="">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="trigger_codename" class="col-sm-3 col-form-label">Trigger codename</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="trigger_codename"
                                           id="trigger_codename" value="">

                                    <div class="form-text">
                                        Used during level set processing to auto-assign level sets to this mod.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm offset-sm-3">
                                    <button type="submit" class="btn btn-outline-primary">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
