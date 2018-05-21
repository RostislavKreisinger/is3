<?php

namespace App\Console\Commands;

use App\Helpers\Inside\InsideTokenRefresher;
use Illuminate\Console\Command;

class InsideTokenRefresherCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inside:token-refresher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh secret token for user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            $userId = $this->ask("User id");
            if (!is_numeric($userId)) {
                $this->error("User id must be number");
                return;
            }

            $provider = new InsideTokenRefresher();
            $command = $this;
            $provider->refreshSecret($userId, function($text) use ($command){
                $command->info($text);
            });


        } catch (\Throwable $e) {
            vde($e);
            var_dump($e);
            exit;
        }

    }
}
