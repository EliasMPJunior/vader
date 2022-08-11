<?php

namespace Eliasmpjunior\Vader\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Web64\Colors\Facades\Colors;

use Eliasmpjunior\Vader\Services\DeclaredClasses;
use Eliasmpjunior\Brasitable\Services\Brasitable;
use Eliasmpjunior\Brasitable\Contracts\BrasitableException;

use DB;


class VaderStoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vader:store
                            {model_name : Model name in any case form}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The current model to be stored.
     *
     * @var string
     */
    protected $currentModel;

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

        $classObject = ((reset($declaredClasses))['classObject']);
        $tableName = $classObject->getTable();
        $connectionName = $classObject->getConnectionName();
        $schemaBuilder = DB::connection($connectionName)->getDoctrineSchemaManager();
        $schemaTable = $schemaBuilder->listTableDetails($tableName);
        $this->currentModel = collect($schemaTable->getColumns())
                                ->reject(function ($item, $key) {
                                    return in_array($key, array(
                                                            'id',
                                                            'created_at',
                                                            'updated_at',
                                                            'deleted_at'
                                                        )
                                                    );
                                })
                                ->map(function ($item, $key) {
                                    return array(
                                                'name' => $key,
                                                'type' => $item->getType()->getName(),
                                                'length' => $item->getLength(),
                                                'default' => $item->getDefault(),
                                                'nullable' => ! $item->getNotnull(),
                                                'value' => null,
                                            );
                                })
                                ->values()
                                ->map(function ($item, $key) {
                                    $item['order'] = $key;
                                    return $item;
                                });

        $this->printCurrentModel();
    }

    protected function printCurrentModel()
    {
        
        $tableHeader = array(
                            array(
                                'title' => 'Order',
                                'color' => 'light_green',
                            ),
                            array(
                                'title' => 'Column',
                                'color' => 'light_green',
                            ),
                            array(
                                'title' => 'Type',
                                'color' => 'light_green',
                            ),
                            array(
                                'title' => 'Length',
                                'color' => 'light_green',
                            ),
                            array(
                                'title' => 'Default',
                                'color' => 'light_green',
                            ),
                            array(
                                'title' => 'Nullable',
                                'color' => 'light_green',
                            ),
                            array(
                                'title' => 'Value',
                                'color' => 'light_green',
                            ),
                        );

        $tableContent = $this->currentModel
                                ->map(function ($item) {
                                    return array(
                                                $item['order'],
                                                $item['name'],
                                                $item['type'],
                                                $item['length'],
                                                is_null($item['default']) ? '-' : $item['default'],
                                                $item['nullable'] ? 'yes' : 'no',
                                                array(
                                                    'title' => is_null($item['value']) ? 'null' : $item['value'],
                                                    'color' => (
                                                                    $item['nullable'] ?
                                                                    'line' :
                                                                    (
                                                                        is_null($item['value']) ?
                                                                        'light_yellow' :
                                                                        'line'
                                                                    )
                                                                ),
                                                ),
                                            );
                                })
                                ->all();

        try
        {
            Brasitable::printTable($tableHeader, $tableContent);
        }
        catch (BrasitableException $e)
        {
            throw new TableShowErrorException($e);
        }
    }
}


        /*
        $schemaBuilder = DB::connection($connectionName)->getSchemaBuilder();
        $columnListing = collect($schemaBuilder->getColumnListing($tableName))
                                ->reject(function ($item) {
                                    return in_array($item, array(
                                                            'created_at',
                                                            'updated_at',
                                                            'deleted_at'
                                                        )
                                                    );
                                })
                                ->map(function ($item) use ($tableName, $schemaBuilder) {
                                    return array(
                                                'name' => $item,
                                                'type' => $schemaBuilder->getColumnType($tableName, $item),
                                            );
                                });
        */