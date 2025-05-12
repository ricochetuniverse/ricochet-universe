<li class="list-group-item p-3">
    <div>
        <a href="{{ $levelSet->getPermalink() }}"
           class="link-secondary fw-bold">{{ $levelSet->name }}</a> ({{ $levelSet->rounds }}&nbsp;rounds)
    </div>

    <div>
        By <a href="{{ action('LevelController@index', ['author' => $levelSet->author]) }}"
              title="Find level sets created by {{ $levelSet->author }}">{{ $levelSet->author }}</a>,
        posted on
        <time datetime="{{ $levelSet->created_at->format('Y-m-d') }}">
            {{ $levelSet->created_at->format('Y-m-d') }}
        </time>
    </div>

    <div class="d-flex mt-3">
        <a href="{{ $levelSet->getPermalink() }}"
           class="me-3" tabindex="-1">
            <img
                src="{{ $levelSet->getImageUrl() }}"
                alt="Screenshot of {{ $levelSet->name }}" width="105" height="80"
                class="d-block">
        </a>

        <p class="m-0 cursor-auto">{{ $levelSet->description }}</p>
    </div>
</li>
