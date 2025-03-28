<?php

declare(strict_types=1);

namespace App\Http\CspPresets;

use Illuminate\Support\Facades\URL;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;

class Decompressor extends Standard
{
    #[\Override]
    public function configure(Policy $policy): void
    {
        parent::configure($policy);

        // For Monaco editor
        // https://github.com/Microsoft/monaco-editor/issues/271
        $policy
            ->add(Directive::STYLE, Keyword::UNSAFE_INLINE)
            ->add(Directive::FONT, URL::to('/build/').'/');

        // For viewing decompressed images
        $policy
            ->add(Directive::IMG, 'blob:');
    }
}
