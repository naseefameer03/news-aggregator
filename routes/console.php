<?php

use Illuminate\Console\Scheduling\Schedule;

app(Schedule::class)->command('app:fetch-articles-command')->hourly();