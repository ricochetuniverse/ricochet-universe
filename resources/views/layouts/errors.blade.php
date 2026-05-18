@if ($errors->any())
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col">
                <x-alert type="danger">
                    @foreach ($errors->all() as $error)
                        <p @if ($loop->last) class="m-0"@endif>{{ $error }}</p>
                    @endforeach
                </x-alert>
            </div>
        </div>
    </div>
@endif

{{-- https://github.com/laracasts/flash --}}
@foreach (session('flash_notification', []) as $message)
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col">
                <x-alert :type="$message['level']">
                    {{ $message['message'] }}
                </x-alert>
            </div>
        </div>
    </div>
@endforeach

{{ session()->forget('flash_notification') }}
