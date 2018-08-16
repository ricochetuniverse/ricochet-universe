@if ($errors->any())
    <div class="container-fluid my-3">
        <div class="row">
            <div class="col">
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p @if ($loop->last) class="m-0"@endif>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
