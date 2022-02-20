@extends('layouts.app')

@section('title', 'Not signed in')
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">Not signed in</div>

                    <div class="card-body">
                        <p>Thanks for signing in, however user access is only available for certain users for now.</p>

                        <p class="m-0">
                            If you believe you should have access, mention your Discord user ID
                            <span class="text-monospace">{{ $discordUserId }}</span> to the site administrator to be
                            whitelisted.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
