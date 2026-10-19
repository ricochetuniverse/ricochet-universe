<?php

namespace App\Http\Controllers;

use App\Http\CspPresets\Mods;
use App\Mod;
use App\Rules\TagName;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModsController extends Controller
{
    public function index(): View
    {
        $mods = Mod::orderBy('created_at')->get();

        return view('mods.index', ['mods' => $mods]);
    }

    public function create(): View
    {
        return view('mods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => ['required', 'string', new TagName, 'unique:mods'],
            'author' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'video_embed_source' => ['nullable', 'url', 'starts_with:'.Mods::YOUTUBE_EMBED_URL],
            'download_link' => ['nullable', 'url'],
            'trigger_codename' => ['nullable', 'string'],
        ]);

        $mod = new Mod;
        $mod->name = $request->input('name');
        $mod->author = $request->input('author');
        $mod->description = $request->input('description', '');
        $mod->video_embed_source = $request->input('video_embed_source', '');
        $mod->download_link = $request->input('download_link', '');
        $mod->trigger_codename = $request->input('trigger_codename', '');
        $mod->save();

        flash('Mod added.')->success();

        return redirect()->action('ModsController@index');
    }

    public function edit(Mod $mod)
    {
        //
    }

    public function update(Request $request, Mod $mod)
    {
        //
    }

    public function destroy(Mod $mod)
    {
        // $mod->delete();
        //
        // flash('Mod removed.')->success();
        //
        // return redirect()->action('ModsController@index');
    }
}
