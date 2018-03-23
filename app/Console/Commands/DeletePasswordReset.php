<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PasswordReset;
use DB;

class DeletePasswordReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeletePasswordReset:deletepasswordreset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete password_reset table line';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = new PasswordReset;
        $result = $model->whereRaw('NOW() >= DATE_ADD(created_at, INTERVAL 15 MINUTE)')->delete();
    }
}
