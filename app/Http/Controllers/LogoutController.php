<?php

namespace App\Http\Controllers;

use Native\Mobile\Edge\Edge;

class LogoutController extends Controller
{
    public function __invoke()
    {
        Edge::clear();

        return response()->noContent();
    }
}
