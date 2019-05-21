@if ($rating)
    <div class="levelsRatings__row"
         @if ($showTooltipExplanation)
         title="Average {{ $gradeExplanation }} grade from {{ number_format($totalCount) }} players: {{ \App\Services\RatingGradeConverter::getGrade($rating) }}. Level sets are graded in Ricochet Infinity."
         data-toggle="tooltip"
         data-placement="left"
         @endif
    >
        <div class="levelsRatings__cell">
            {{ $image }}
        </div>

        <div class="levelsRatings__cell">
            {{ \App\Services\RatingGradeConverter::getGrade($rating) }}
        </div>

        @if ($showPlayerCount)
            <div class="levelsRatings__cell">
                from {{ number_format($totalCount) }} players
            </div>
        @endif
    </div>
@endif
