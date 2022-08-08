<?php

namespace Eliasmpjunior\Vader\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Web64\Colors\Facades\Colors;
use Illuminate\Support\Facades\Log;

use Eliasmpjunior\Vader\Services\Vader;
use Eliasmpjunior\Vader\Exceptions\VaderException;


class VaderInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vader:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        try
        {
            Vader::printInfo();
        }
        catch (VaderException $e)
        {
            $e->printException();

            return 1;
        }

        return 0;
    }
}
