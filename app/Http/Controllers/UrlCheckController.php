<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use DiDom\Document;

class UrlCheckController extends Controller
{
/**
 * Makes SEO check for URL
 *
 * @param Int $urlId Id of the URL to make SEO check
 */
    public function __invoke(int $urlId)
    {
        $url = DB::table('urls')->find($urlId);
        if ($url === null) {
            return redirect()->route('urls.index');
        }
        try {
            $response = Http::get($url->name);
        } catch (\Exception $exception) {
            flash($exception->getMessage())->error();
            return redirect()->route('urls.show', $urlId);
        }
        $body = new Document($response->body());
        $h1 = optional($body->first('h1'))->text();
        $title = optional($body->first('title'))->text();
        $description = optional($body->first('meta[name="description"]'))->attr('content');
        $status = $response->status();
        DB::table('url_checks')->
            insert([
                'url_id' => $urlId,
                'status_code' => $status,
                'h1' => $h1,
                'title' => $title,
                'description' => $description,
                'created_at' => now()
            ]);
        //print_r($urlId);
        return redirect()->route('urls.show', $urlId);
    }
}
