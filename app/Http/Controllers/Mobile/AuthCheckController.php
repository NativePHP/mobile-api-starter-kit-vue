<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Native\Mobile\Facades\SecureStorage;

class AuthCheckController extends Controller
{
    public function __invoke(): Response|RedirectResponse
    {
        // If no token, redirect to login immediately (server-side)
        if (blank(SecureStorage::get('api_token'))) {
            return redirect()->route('login');
        }

        // User has token, show biometric check page
        return Inertia::render('mobile/auth/Check');
    }
}
