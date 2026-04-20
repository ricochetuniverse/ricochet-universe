<?php

declare(strict_types=1);

namespace App\Http\CspPresets;

use Illuminate\Support\Facades\URL;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;

class Decompressor extends Standard
{
    protected bool $canUseNonce = false;

    #[\Override]
    public function configure(Policy $policy): void
    {
        parent::configure($policy);

        $this->addForMonacoEditor($policy);

        // For viewing decompressed images
        $policy
            ->add(Directive::IMG, 'blob:');

        // For .NET / NuVelocity Unpacker
        $policy
            ->add(Directive::SCRIPT, Keyword::UNSAFE_WEB_ASSEMBLY_EXECUTION)
            ->add(Directive::CONNECT, URL::to('/build/').'/')
            ->add(Directive::IMG, 'data:');
    }
}
