<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestCommand extends Command
{
    protected $signature = 'app:email-hash {id}';

    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::withTrashed()->where('id', 1)->first();
        $user->restore();
        dd(User::onlyTrashed()->where('id', 1)->get()->toArray());
    }
}
