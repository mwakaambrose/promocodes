<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use App\Http\Controllers\Api\v1\ApiController;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {

        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->is("api/*")) {
            $data = [
                "message" => $e->getMessage() ?? "Something went wrong",
            ];
            if ($e instanceof AuthenticationException){
                return (new ApiController())->unauthorized($data);
            }else {
                return (new ApiController())->error($data);
            }
        }

        return parent::render($request, $e);
    }


}
