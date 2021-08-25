<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidAppDataException extends RuntimeException
{
    public function render($request)
    {
        return redirect(route('admin::tips'))
                    ->withErrors($this->getMessage());
    }
}
