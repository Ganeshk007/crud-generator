<?php

namespace CrudDev\CrudDev;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CrudDev\CrudDev\Skeleton\SkeletonClass
 */
class CrudDevFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'crud-dev';
    }
}
