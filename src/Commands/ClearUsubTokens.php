<?php

namespace RB\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class ClearUsubTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usub:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired tokens from database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    }
}
