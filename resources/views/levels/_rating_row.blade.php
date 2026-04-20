@if ($rating)
    <div class="levelsRatings__row"
         @if ($showTooltipExplanation ?? true)
             title="Average {{ $gradeExplanation }} grade from {{ number_format($totalCount) }} players: {{ \App\Services\RatingGradeConverter::getGrade($rating) }}. Level sets are graded in Ricochet Infinity."
         data-bs-toggle="tooltip"
         data-bs-placement="left"
        @endif
    >
        <div class="levelsRatings__cell">
            {{ $image }}
        </div>

        @if ($showInlineGradeExplanation ?? false)
            <div class="levelsRatings__cell">
                {{ ucfirst($gradeExplanation) }}:
            </div>
        @endif

        <div class="levelsRatings__cell">
            {{ \App\Services\RatingGradeConverter::getGrade($rating) }}
        </div>

        @if ($showPlayerCount ?? false)
            <div class="levelsRatings__cell">
                from {{ number_format($totalCount) }} players
            </div>
        @endif
    </div>
@endif
