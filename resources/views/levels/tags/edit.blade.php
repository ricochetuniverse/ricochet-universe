@extends('layouts.app', [
    'selected_navbar_item' => 'levels',
])

@section('title', 'Edit tags for '.$levelSet->name)
@section('og:url', action('LevelTagsController@edit', ['levelSet' => $levelSet]))
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ $levelSet->getPermalink() }}" class="btn btn-outline-primary">« Back</a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-header">Edit level set tags for “{{ $levelSet->name }}”</div>

                    <div class="card-body">
                        <p>Select tags to show for this level set. Reordering is currently not supported.</p>

                        <form action="{{ action('LevelTagsController@update', ['levelSet' => $levelSet]) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')

                            @foreach ($allTags as $tag)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $tag->name }}" name="tags[]"
                                           id="tag-{{ $tag->id }}" @if ($levelSet->tags->contains($tag)) checked @endif>

                                    <label class="form-check-label" for="tag-{{ $tag->id }}">
                                        {{ $tag->name }}
                                    </label>
                                </div>
                            @endforeach

                            <div class="row mt-3">
                                <div class="col-sm">
                                    <button type="submit" class="btn btn-outline-primary">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
