<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\LevelSetName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LevelSubmitController extends Controller
{
    public function submit(Request $request)
    {
        $upload_available = app()->environment('local');

        if (! $upload_available) {
            return $this->showResponse(false, 'Level uploads through the game are not supported yet, please upload to #ricocheti-upload-channel on Discord instead.');
        }

        $file = $request->file('file');

        $original_file_name = $file->getClientOriginalName();
        if (! Str::endsWith($original_file_name, '.RicochetI')) {
            return $this->showResponse(false, 'The file name must end with a .RicochetI file extension.');
        }

        $levelSetName = Str::beforeLast($original_file_name, '.RicochetI');
        $validator = Validator::make([
            'file_name' => $levelSetName,
        ], [
            'file_name' => ['required', 'string', new LevelSetName, 'unique:App\\LevelSet,name'],
        ], [], [
            'file_name' => 'level set name',
        ]);
        if ($validator->fails()) {
            return $this->showResponse(false, $validator->errors()->first());
        }

        // todo read the file, parse it
        // Storage::disk('levels')->put($original_file_name, $file);

        return $this->showResponse(true, '');
    }

    private function showResponse(bool $success, string $error)
    {
        if ($success && strlen($error) > 0) {
            throw new \InvalidArgumentException('Game will not show error message if it\'s successful');
        }

        $success = (int) $success;

        $text = <<<EOF
CWebResponse
{
  SessionID=343882
  Success=${success}
  ErrorMessage=${error}
}
EOF;

        return response($text)
            ->header('Content-Type', 'text/plain');
    }
}
