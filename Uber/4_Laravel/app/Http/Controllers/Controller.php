<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// A NE SURTOUT PAS TOUCHER, BASE CONTROLLER //
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
