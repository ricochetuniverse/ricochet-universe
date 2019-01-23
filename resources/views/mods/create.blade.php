@extends('layouts.app')

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

                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Name</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="author" class="col-sm-3 col-form-label">Author</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="author" id="author" value="" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-sm-3 col-form-label">Description</label>

                                <div class="col-sm-9">
                                    <textarea class="form-control" name="description" id="description"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="video_embed_source" class="col-sm-3 col-form-label">
                                    Video embed source
                                </label>

                                <div class="col-sm-9">
                                    <input type="url" class="form-control" name="video_embed_source"
                                           id="video_embed_source" value="">

                                    <small class="form-text text-muted">
                                        Website Content-Security-Policy may prohibit video embeds other than YouTube.
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="download_link" class="col-sm-3 col-form-label">Download link</label>

                                <div class="col-sm-9">
                                    <input type="url" class="form-control" name="download_link" id="download_link"
                                           value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="trigger_codename" class="col-sm-3 col-form-label">Trigger codename</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="trigger_codename"
                                           id="trigger_codename" value="">

                                    <small class="form-text text-muted">
                                        Used during level set processing to auto-assign level sets to this mod.
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm offset-sm-3">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
