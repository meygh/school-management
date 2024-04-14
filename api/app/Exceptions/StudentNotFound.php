<?php

namespace App\Exceptions;

use Exception;

class StudentNotFound extends Exception
{
    protected $itemId;

    public function __construct($itemId)
    {
        $this->itemId = $itemId;
    }


    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json(['message' => "دانش‌آموز شناسه {$this->itemId} یافت نشد!"], 404);
    }
}

