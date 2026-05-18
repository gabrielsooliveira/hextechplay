<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)->get();

        $urls = [
            url('/'),
            url('/games'),
            url('/lorequestion'),
            url('/wordlol'),
            url('/clickchallenger'),
            url('/blog'),
            url('/contact'),
            url('/partners'),
        ];

        foreach ($posts as $post) {
            $urls[] = url('/blog/' . $post->slug);
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . $url . '</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
