<?php

declare(strict_types=1);

namespace App\Http\CspPresets;

use Illuminate\Support\Facades\Storage;
use Spatie\Csp\Directive;
use Spatie\Csp\Policy;

class DataBrowser extends Standard
{
    protected bool $canUseNonce = false;

    #[\Override]
    public function configure(Policy $policy): void
    {
        parent::configure($policy);

        $this->addForMonacoEditor($policy);

        // For viewing files
        $disk = Storage::disk('data-browser');
        $policy
            ->add(Directive::CONNECT, $disk->url(''));
        $policy
            ->add(Directive::MEDIA, $disk->url(''));
    }
}
