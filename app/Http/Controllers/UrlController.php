<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use DiDom\Document;

class UrlController extends Controller
{
    public function new(Request $request)
    {
        //print_r($request->input('url'));
        return view('main');
    }

    public function index()
    {
        $subQuery = DB::table('url_checks')
                   ->select('url_id', DB::raw('MAX(created_at) as latest_created_at'))->groupBy('url_id');
        $latestCheck = DB::table('url_checks')
        ->select('url_checks.url_id', 'url_checks.status_code', 'sub.latest_created_at')
            ->joinSub($subQuery, 'sub', function ($join) {
                $join->on('url_checks.url_id', '=', 'sub.url_id')
                    ->on('url_checks.created_at', '=', 'sub.latest_created_at');
            });
        $urls = DB::table('urls')
        ->select('urls.id', 'urls.name', 'latest_check.status_code', 'latest_check.latest_created_at')
                   ->leftJoinSub($latestCheck, 'latest_check', function ($join) {
                       $join->on('urls.id', '=', 'latest_check.url_id');
                   })->simplePaginate(15);

        return view('urls', ['urls' => $urls]);
    }

    public function show(int $urlId)
    {
        $id = (int) $urlId;
        $url = DB::table('urls')->select('name', 'id', 'created_at')->where('id', '=', $id)->first();
        $checks = DB::table('url_checks')->where('url_id', '=', $id)->simplePaginate(15);
        if ($url === null) {
            return redirect()->route('urls.index');
        }
        return view('url', ['url' => $url, 'checks' => $checks]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => 'required|url|max:255'
        ]);
        if ($validator->fails()) {
            $url = $request->input('url');
            return redirect()->route('index')->with('oldValue', $url['name'])->withErrors($validator);
        }

        $url = $request->input('url');
        $oldData = DB::table('urls')->where('name', '=', $url['name'])->first();
        if ($oldData === null) {
            $date = now();
            $id = DB::table('urls')->insertGetId([
               'name' => $url['name'],
               'created_at' => $date
            ]);
            flash('Страница успешно добавлена')->info();
        } else {
            $id = $oldData->id;
            flash('Страница уже существует')->info();
        }
        return redirect()->route('urls.show', $id);
    }

    public function check(int $urlId)
    {
        $url = DB::table('urls')->find($urlId);
        //dump($url);
        //die();
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
        $date = now();

        $data = DB::table('url_checks')->
            insert([
                'url_id' => $urlId,
                'status_code' => $status,
                'h1' => $h1,
                'title' => $title,
                'description' => $description,
                'created_at' => $date
            ]);
        return redirect()->route('urls.show', $urlId);
    }
}
