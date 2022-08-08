<?php

namespace Eliasmpjunior\Vader\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Web64\Colors\Facades\Colors;
use Illuminate\Database\QueryException;

use Eliasmpjunior\Vader\Exceptions\ModelNotFoundException;
use Eliasmpjunior\Vader\Services\DeclaredClasses;


class VaderIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vader:index
                            {model_name : Model name in any case form}';

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
        $modelName = Str::studly(Str::singular($this->argument('model_name')));

        $declaredClasses = DeclaredClasses::models()
                                ->filter(function ($item) use ($modelName) {
                                    return $item['name'] === $modelName;
                                })
                                ->keyBy(function ($item) {
                                    return $item['classPath'];
                                })
                                ->all();

        if (count($declaredClasses) < 1)
        {
            (new ModelNotFoundException($modelName))->printException();
        }
        elseif (count($declaredClasses) > 1)
        {
            // code...
        }

        $modelList = ((reset($declaredClasses))['classObject'])->all();

        dd($modelList);

        return 0;
    }
}
