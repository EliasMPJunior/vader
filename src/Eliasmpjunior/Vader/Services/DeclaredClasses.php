<?php

namespace Eliasmpjunior\Vader\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;


class DeclaredClasses
{
    public static function models()
    {
        return collect(File::allFiles(app_path()))
                                ->merge(File::allFiles(str_replace('/app', '/vendor', app_path())))
                                ->filter(function ($item) {
                                    return Str::endsWith($item->getFileName(), '.php');
                                })
                                ->filter(function ($item) {
                                    return Str::endsWith($item->getRelativePath(), 'Models');
                                })
                                ->map(function ($item) {
                                    return array(
                                                'relativePath' => $item->getRelativePath(),
                                                'name' => $item->getFileName(),
                                                'realPath' => $item->getRealPath(),
                                            );
                                })
                                ->map(function ($item) {
                                    $item['classPath'] = str_replace(str_replace('/app', '', app_path()), '', $item['realPath']);

                                    if (Str::startsWith($item['classPath'], '/app'))
                                    {
                                        $item['classPath'] = Str::before($item['classPath'], '.php');

                                        $item['classPath'] = str_replace('/', '\\', str_replace('/app', 'App', $item['classPath']));

                                        try
                                        {
                                            $item['classObject'] = app($item['classPath']);
                                            $item['relativePath'] = 'App\\'.$item['relativePath'];
                                        }
                                        catch (\Throwable $th) {}
                                    }

                                    return $item;
                                })
                                ->map(function ($item) {
                                    if (Str::contains($item['classPath'], '/src/'))
                                    {
                                        $item['relativePath'] = Str::after($item['relativePath'], '/src/');
                                        $item['classPath'] = Str::after($item['classPath'], '/src/');

                                        $namespace = explode('/', $item['relativePath']);

                                        for ($i = 0; $i < count($namespace); $i++)
                                        {
                                            if ( ! array_key_exists('classObject', $item) or ! $item['classObject'] instanceof Model)
                                            {
                                                $namespacePath = implode(
                                                                    '\\',
                                                                    collect($namespace)
                                                                        ->filter(function ($item, $key) use ($i) {
                                                                            return $key >= $i;
                                                                        })
                                                                        ->all()
                                                                );

                                                try
                                                {
                                                    $className = Str::before($item['name'], '.php');
                                                    $item['classObject'] = app($namespacePath.'\\'.$className);
                                                    $item['relativePath'] = $namespacePath;
                                                    $item['classPath'] = $namespacePath.'\\'.$className;
                                                }
                                                catch (\Throwable $th) {}
                                            }
                                        }
                                    }

                                    return $item;
                                })
                                ->filter(function ($item) {
                                    return array_key_exists('classObject', $item) and $item['classObject'] instanceof Model;
                                })
                                ->map(function ($item) {
                                    return array(
                                                'classPath' => $item['classPath'],
                                                'classObject' => $item['classObject'],
                                                'name' => Str::before($item['name'], '.php'),
                                            );
                                });
    }
}
