<?php

namespace App\Http\CspPolicies;

use Illuminate\Support\Facades\URL;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;

class Decompressor extends Standard
{
    #[\Override]
    public function configure(): void
    {
        parent::configure();

        // For Monaco editor
        // https://github.com/Microsoft/monaco-editor/issues/271
        $this
            ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::FONT, URL::to('/build/').'/');

        // For viewing decompressed images
        $this
            ->addDirective(Directive::IMG, 'blob:');
    }
}
