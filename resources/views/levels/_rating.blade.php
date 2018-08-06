@if ($levelSet->overall_rating)
    <div class="levelsRatings__wrap">
    <div class="levelsRatings__row"
         title="Average overall grade from {{ number_format($levelSet->overall_rating_count) }} players: {{ \App\Services\RatingGradeConverter::getGrade($levelSet->overall_rating) }}. Level sets are graded in Ricochet Infinity."
         data-toggle="tooltip"
         data-placement="left">
        <div class="levelsRatings__cell">
            <img src="{{ asset('images/ratingOverall.jpg') }}"
                 alt="Overall grade"
                 width="20"
                 height="20">
        </div>
        <div class="levelsRatings__cell">
            {{ \App\Services\RatingGradeConverter::getGrade($levelSet->overall_rating) }}
        </div>
        @if (isset($showPlayerCount))
            <div class="levelsRatings__cell">
                from {{ number_format($levelSet->overall_rating_count) }} players
            </div>
        @endif
    </div>

    <div class="levelsRatings__row"
         title="Average gameplay grade from {{ number_format($levelSet->fun_rating_count) }} players: {{ \App\Services\RatingGradeConverter::getGrade($levelSet->fun_rating) }}. Level sets are graded in Ricochet Infinity."
         data-toggle="tooltip"
         data-placement="left">
        <div class="levelsRatings__cell">
            <img src="{{ asset('images/ratingGameplay.jpg') }}"
                 alt="Gameplay grade"
                 width="20"
                 height="20">
        </div>
        <div class="levelsRatings__cell">
            {{ \App\Services\RatingGradeConverter::getGrade($levelSet->fun_rating) }}
        </div>
        @if (isset($showPlayerCount))
            <div class="levelsRatings__cell">
                from {{ number_format($levelSet->fun_rating_count) }} players
            </div>
        @endif
    </div>

    <div class="levelsRatings__row"
         title="Average visuals grade from {{ number_format($levelSet->graphics_rating_count) }} players: {{ \App\Services\RatingGradeConverter::getGrade($levelSet->graphics_rating) }}. Level sets are graded in Ricochet Infinity."
         data-toggle="tooltip"
         data-placement="left">
        <div class="levelsRatings__cell">
            <img src="{{ asset('images/ratingVisuals.jpg') }}"
                 alt="Visuals grade"
                 width="20"
                 height="20">
        </div>
        <div class="levelsRatings__cell">
            {{ \App\Services\RatingGradeConverter::getGrade($levelSet->graphics_rating) }}
        </div>
        @if (isset($showPlayerCount))
            <div class="levelsRatings__cell">
                from {{ number_format($levelSet->graphics_rating_count) }} players
            </div>
        @endif
    </div>
    </div>
@endif
