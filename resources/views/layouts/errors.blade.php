@if ($errors->any())
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col">
                <div class="alert alert-danger m-0" role="alert">
                    @foreach ($errors->all() as $error)
                        <p @if ($loop->last) class="m-0"@endif>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

{{-- https://github.com/laracasts/flash --}}
@foreach (session('flash_notification', collect())->toArray() as $message)
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col">
                <div class="alert alert-{{ $message['level'] }} m-0" role="alert">
                    {{ $message['message'] }}
                </div>
            </div>
        </div>
    </div>
@endforeach

{{ session()->forget('flash_notification') }}
