<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune', [
    'path' => app_path(),
])->daily();
