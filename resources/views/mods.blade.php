@extends('layouts.app')

@section('title', 'Mods')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Mods</div>

                    <div class="card-body">
                        <p class="m-0">
                            Explore and play new environments, custom content and modifications for Ricochet Infinity.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 col-xl-4 mt-3">
                <div class="card">
                    <div class="card-body">
                        <span class="text-secondary font-weight-bold">Neon Environment</span> by Moymoy13

                        <p>A futuristic display of technology with the touch of Xtreme.</p>

                        <div class="embed-responsive embed-responsive-4by3">
                            <iframe width="316" height="240" src="https://www.youtube.com/embed/O89ajSkL6-A?rel=0"
                                    allow="picture-in-picture" allowfullscreen
                                    class="embed-responsive-item"></iframe>
                        </div>

                        <div class="d-flex">
                            <a href="https://drive.google.com/uc?id=1fk1V77d03CuqQR_CKTmikLfrSI_0589k&amp;export=download"
                               class="d-inline-flex align-items-center mt-3">
                                <img src="{{ asset('images/levelDownload.jpg') }}"
                                     alt=""
                                     width="38"
                                     height="38"
                                     class="mr-1">
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
