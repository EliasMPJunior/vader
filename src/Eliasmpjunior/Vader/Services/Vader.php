<?php

namespace Eliasmpjunior\Vader\Services;

use Web64\Colors\Facades\Colors;

use Eliasmpjunior\Brasitable\Services\Brasitable;
use Eliasmpjunior\Brasitable\Contracts\BrasitableException;
use Eliasmpjunior\Vader\Exceptions\TableShowErrorException;


class Vader
{
	public static function printInfo()
	{
        $tableHeader = array(
                            array(
                                'title' => 'Data',
                                'color' => 'light_green',
                            ),
                            array(
                                'title' => 'Value',
                                'color' => 'light_green',
                            ),
                        );

        $tableContent = array(
                            array(
                                'Name',
                                'eliasmpjunior/vader'
                            ),
                            array(
                                'Version',
                                '0.1.0 beta'
                            ),
                            array(
                                'Author',
                                'eliasmpjunior'
                            ),
                            array(
                                'Email',
                                'elias@brasidata.com.br'
                            ),
                            array(
                                'Homepage',
                                'https://brasidata.com.br'
                            ),
                        );

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