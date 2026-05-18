@extends('layouts.app', [
    'selected_navbar_item' => 'levels',
])

@section('title', 'Edit tags for '.$levelSet->name)
@section('og:url', action('LevelTagsController@edit', ['levelSet' => $levelSet]))
@section('robots', 'noindex,follow')

@section('content')
    <div class="container-fluid vstack gap-3">
        <div class="row">
            <div class="col">
                <x-button href="{{ $levelSet->getPermalink() }}">« Back</x-button>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <h1 class="card-header">Edit level set tags for “{{ $levelSet->name }}”</h1>

                    <div class="card-body">
                        <p>Select tags to show for this level set. Reordering is currently not supported.</p>

                        <form action="{{ action('LevelTagsController@update', ['levelSet' => $levelSet]) }}"
                              method="POST" class="vstack gap-3">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                <div class="col">
                                    @foreach ($allTags as $tag)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $tag->name }}"
                                                   name="tags[]"
                                                   id="tag-{{ $tag->id }}"
                                                   @if ($levelSet->tags->contains($tag)) checked @endif>

                                            <label class="form-check-label" for="tag-{{ $tag->id }}">
                                                {{ $tag->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <x-button type="submit">Edit</x-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
