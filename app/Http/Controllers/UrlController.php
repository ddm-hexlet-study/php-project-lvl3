<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use DiDom\Document;

class UrlController extends Controller
{
/**
 * Main page.
 */
    public function new()
    {
        return view('main');
    }

/**
 * Shows list of URLs and status/date of the last check.
 *
 * @param Int $urlId Id of the URL to look for
 */
    public function index()
    {
        $latestChecks = DB::table('url_checks')
            ->distinct('url_id')->orderBy('url_id')->latest();
        $urls = DB::table('urls')
        ->select('urls.id', 'urls.name', 'latest_check.status_code', 'latest_check.created_at')
                   ->leftJoinSub($latestChecks, 'latest_check', function ($join) {
                       $join->on('urls.id', '=', 'latest_check.url_id');
                   })->simplePaginate(15);
        return view('urls', ['urls' => $urls]);
    }

/**
 * Shows URL page according to its ID.
 *
 * @param Int $urlId Id of the URL to look for
 */
    public function show(int $urlId)
    {
        $url = DB::table('urls')->find($urlId);
        if ($url === null) {
            return redirect()->route('urls.index');
        }
        $checks = DB::table('url_checks')->where('url_id', '=', $urlId)->simplePaginate(15);
        return view('url', ['url' => $url, 'checks' => $checks]);
    }

/**
 * Adds a new URL or shows info messages in case of this URL already exists.
 *
 * @param Request $request income resquest containing new URL data
 */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => 'required|url|max:255'
        ]);
        if ($validator->fails()) {
            $url = $request->input('url');
            flash('Некорректный URL')->error();
            return redirect()->route('index')->with('oldValue', $url['name']);
        }
        $url = $request->input('url');
        $oldData = DB::table('urls')->where('name', '=', $url['name'])->first();
        if ($oldData === null) {
            $id = DB::table('urls')->insertGetId([
               'name' => $url['name'],
               'created_at' => now()
            ]);
            flash('Страница успешно добавлена')->info();
        } else {
            $id = $oldData->id;
            flash('Страница уже существует')->info();
        }
        return redirect()->route('urls.show', $id);
    }

/**
 * Makes SEO check for URL
 *
 * @param Int $urlId Id of the URL to make SEO check
 */
    public function check(int $urlId)
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
        return redirect()->route('urls.show', $urlId);
    }
}
