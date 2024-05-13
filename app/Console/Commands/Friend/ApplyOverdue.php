<?php

namespace App\Console\Commands;

use App\Models\Friend;
use Illuminate\Console\Command;

class ApplyOverdue extends Command
{

    protected $signature = 'Friend:apply:overdue';

    protected $description = 'Friend apply auto overdue.';

    public function handle()
    {
    }
}
