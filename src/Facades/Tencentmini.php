<?php
namespace Ttmn\Tencentmini\Facades;

use Illuminate\Support\Facades\Facade;

class Tencentmini extends Facade

{

    protected static function getFacadeAccessor()

    {

        return 'tencentmini';

    }

}