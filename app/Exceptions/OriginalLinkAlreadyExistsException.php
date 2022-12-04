<?php

namespace App\Exceptions;

use Exception;

class OriginalLinkAlreadyExistsException extends Exception
{
    public function render()
    {
        return response()->json(["error" => true, "message" => $this->getMessage()]);
    }
}
