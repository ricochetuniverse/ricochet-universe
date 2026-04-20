<?php

declare(strict_types=1);

namespace App\Http\CspPresets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;

class LevelSetInfo extends Standard
{
    protected bool $canUseNonce = false;

    #[\Override]
    public function configure(Policy $policy): void
    {
        parent::configure($policy);

        // To download level set file
        $policy
            ->add(Directive::CONNECT, action('API\LevelDownloadController@download'));

        $this->addForMonacoEditor($policy);
    }
}
