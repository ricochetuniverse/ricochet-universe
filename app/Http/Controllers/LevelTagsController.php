<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\LevelSet;
use Conner\Tagging\Model\Tag;
use Illuminate\Http\Request;

class LevelTagsController extends Controller
{
    public function edit(LevelSet $levelSet)
    {
        $allTags = Tag::orderBy('name')->get();
        $levelSet->load('tagged');

        return view('levels.tags.edit', [
            'levelSet' => $levelSet,
            'allTags' => $allTags,
        ]);
    }

    public function update(Request $request, LevelSet $levelSet)
    {
        $levelSet->retag($request->input('tags'));

        flash('Level set tags edited.')->success();

        return redirect($levelSet->getPermalink());
    }
}
