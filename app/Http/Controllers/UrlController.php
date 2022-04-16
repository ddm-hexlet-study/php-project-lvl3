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
 * Shows list of URLs and status/date of the last check.
 */
    public function index()
    {
        $checks = DB::table('url_checks')
            ->distinct('url_id')->orderBy('url_id')->latest()->get()->keyBy('url_id');
        $urls = DB::table('urls')->select('id', 'name')->simplePaginate(15);
        return view('urls', ['urls' => $urls, 'checks' => $checks]);
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
            return redirect()->route('index')
                ->with('oldValue', $url['name'])
                    ->withErrors('Некорректный URL');
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
}
