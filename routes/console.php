<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune', [
    '--path' => 'app',
])->daily();
