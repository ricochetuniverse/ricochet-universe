@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Upload your levels</div>

                    <div class="card-body">
                        <p class="m-0">Self-service level submissions are not currently finished.</p>
                    </div>
                </div>

                <div class="card mt-3">
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
                                    <input type="date" class="form-control" name="date_posted" id="date_posted" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required>
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
    </div>
@endsection
