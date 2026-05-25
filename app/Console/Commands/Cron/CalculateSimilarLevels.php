<?php

declare(strict_types=1);

namespace App\Console\Commands\Cron;

use App\LevelSet;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * @see https://gitlab.com/ngyikp/ricochet-levels/-/work_items/26
 */
#[Signature('ricochet-cron:calculate-similar-levels')]
#[Description('Calculate similar levels for all level sets')]
class CalculateSimilarLevels extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (LevelSet::orderBy('created_at')->orderBy('id')->lazyById() as $levelSet) {
            $levelSet->similar_levels = implode(';', $levelSet->getSimilarLevels());

            if ($levelSet->isDirty()) {
                $this->line(sprintf('Updating %s (ID: %d)', $levelSet->name, $levelSet->id));
            }
            $levelSet->save();
        }

        $this->info('Finished calculating similar levels.');
    }
}
