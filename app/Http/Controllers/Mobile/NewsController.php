<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('mobile/News');
    }

    public function fetch(): JsonResponse
    {
        try {
            $response = Http::timeout(10)->get('https://laravel-news.com/feed/json');

            if ($response->successful()) {
                $data = $response->json();

                $articles = collect($data['items'] ?? [])->map(fn ($item) => [
                    'title' => $item['title'],
                    'link' => $item['url'],
                    'description' => $item['excerpt'],
                    'pubDate' => $item['date_published'],
                    'image' => $item['image'],
                ])->all();

                return response()->json($articles);
            }
        } catch (\Exception) {
            // Return empty array on error
        }

        return response()->json([]);
    }
}
