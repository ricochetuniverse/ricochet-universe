@if ($levelSet->overall_rating)
    <div class="levelsRatings__wrap">
        @component('levels._rating_row', [
            'rating' => $levelSet->overall_rating,
            'totalCount' => $levelSet->overall_rating_count,
            'gradeExplanation' => 'overall',
            'showTooltipExplanation' => $showTooltipExplanation ?? true,
            'showPlayerCount' => $showPlayerCount ?? false,
        ])
            @slot('image')
                <img src="{{ mix('rating-overall.jpg') }}" alt="Overall grade" width="20" height="20">
            @endslot
        @endcomponent

        @component('levels._rating_row', [
            'rating' => $levelSet->fun_rating,
            'totalCount' => $levelSet->fun_rating_count,
            'gradeExplanation' => 'gameplay',
            'showTooltipExplanation' => $showTooltipExplanation ?? true,
            'showPlayerCount' => $showPlayerCount ?? false,
        ])
            @slot('image')
                <img src="{{ mix('rating-gameplay.jpg') }}" alt="Gameplay grade" width="20" height="20">
            @endslot
        @endcomponent

        @component('levels._rating_row', [
            'rating' => $levelSet->graphics_rating,
            'totalCount' => $levelSet->graphics_rating_count,
            'gradeExplanation' => 'visuals',
            'showTooltipExplanation' => $showTooltipExplanation ?? true,
            'showPlayerCount' => $showPlayerCount ?? false,
        ])
            @slot('image')
                <img src="{{ mix('rating-visuals.jpg') }}" alt="Visuals grade" width="20" height="20">
            @endslot
        @endcomponent
    </div>
@endif
