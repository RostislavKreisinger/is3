<?php

namespace App\Console\Commands;

use Monkey\Translator\Tr;
use Illuminate\Console\Command;

/**
 * Class TranslatorTest
 * @package App\Console\Commands
 */
class TranslatorTest extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:translator {btf?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates new btf which should be reported to JIRA as new task for translators';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        Tr::initTranslator("TEST");

        $btf = $this->argument('btf');
        if (empty($btf)) {
            $btf = "absolutely_non_existent_btf_please_do_not_translate_" . time();
        }

        $this->comment("Btf: {$btf}");

        $translated = Tr::_($btf);

        $this->comment("translation: {$translated}");
        $this->info("done!..");

        return 0;
    }
}
