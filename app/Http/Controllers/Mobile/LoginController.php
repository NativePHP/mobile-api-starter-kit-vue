<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('mobile/auth/Login');
    }
}
