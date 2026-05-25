<?php

declare(strict_types=1);

namespace App;

use Database\Factories\LevelSetTagFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
#[UseFactory(LevelSetTagFactory::class)]
class LevelSetTag extends Model
{
    use HasFactory;
}
