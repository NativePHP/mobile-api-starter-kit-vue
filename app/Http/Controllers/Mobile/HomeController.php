<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Native\Mobile\Facades\SecureStorage;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('mobile/Home', [
            'userName' => SecureStorage::get('user_name', 'User'),
            'userEmail' => SecureStorage::get('user_email', ''),
        ]);
    }
}
