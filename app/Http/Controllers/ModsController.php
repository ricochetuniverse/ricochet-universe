<?php

namespace App\Http\Controllers;

use App\Mod;
use App\Rules\TagName;
use Illuminate\Http\Request;

class ModsController extends Controller
{
    public function index()
    {
        $mods = Mod::orderBy('created_at')->get();

        return view('mods.index', ['mods' => $mods]);
    }

    public function create()
    {
        return view('mods.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', new TagName, 'unique:mods'],
            'author' => ['required', 'string'],
            'description' => '',
            'video_embed_source' => ['nullable', 'url'],
            'download_link' => ['nullable', 'url'],
            'trigger_codename' => '',
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
        $mod->delete();

        flash('Mod removed.')->success();

        return redirect()->action('ModsController@index');
    }
}
