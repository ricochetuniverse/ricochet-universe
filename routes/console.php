<?php

use App\Console\Commands\PurgeOldLevelSetDownloadLogs;
use Illuminate\Support\Facades\Schedule;

Schedule::command(PurgeOldLevelSetDownloadLogs::class)->daily();
