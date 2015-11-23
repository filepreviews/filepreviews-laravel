<?php

namespace FilePreviews\Laravel;

use Illuminate\Support\Facades\Facade;

class FilePreviewsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'FilePreviews';
    }
}
