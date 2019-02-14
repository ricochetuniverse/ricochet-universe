@extends('layouts.app')

@section('title', 'Tools')
@section('og:description', 'Use various utilities for Ricochet that are useful for tinkerers.')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Tools</div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item p-3">
                            <div>
                                <a href="{{ action('DecompressorController@index') }}"
                                   class="text-secondary font-weight-bold">Decompressor</a>
                            </div>

                            <div>
                                Decompress Ricochet levels, stats and other data.
                            </div>
                        </li>

                        <li class="list-group-item p-3">
                            <div>
                                <a href="{{ action('RedModPackagerController@index') }}"
                                   class="text-secondary font-weight-bold">RED mod packager</a>
                            </div>

                            <div>
                                Package your Ricochet mods to a <code>.RED</code> file for easier distribution.
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
