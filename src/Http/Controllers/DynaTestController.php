<?php

namespace Amirsarfar\DynaBase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DynaTestController extends Controller
{

    public function dyna(Request $request)
    {
        dd($request);
    }
}

