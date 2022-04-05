<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlController extends Controller
{
    private const GMT = '3';
    public function getUrl($urlId)
    {
        $url = DB::table('urls')->select('name', 'id', 'created_at')->where('id', '=', $urlId)->first();
        $checks = DB::table('url_checks')->where('url_id', '=', $urlId)->get() ?? [];
        //dump($checks);
        if ($url === null) {
            abort(404);
        }
        return view('url', ['url' => $url, 'checks' => $checks]);
    }

    public function addUrl(Request $request)
    {
        $request->validate([
            'url.name' => 'required|url|max:255'
        ]);
        $url = $request->input('url');
        if (DB::table('urls')->where('name', '=', $url)->exists()) {
            $request->session()->flash('status', 'Страница уже существует');
            return view('main');
        }
        $date = Carbon::now(self::GMT);
        DB::table('urls')->insert([
           ['name' => $url['name'], 'created_at' => $date]
        ]);
        $request->session()->flash('status', 'Страница добавлена');
        return view('main');
    }

    public function showUrls()
    {
        $latestCheck = DB::table('url_checks')
                   ->select('url_id', DB::raw('MAX(created_at) as latest_created_at'))
                   ->groupBy('url_id');
        $urls = DB::table('urls')
                   ->leftJoinSub($latestCheck, 'latest_check', function ($join) {
                       $join->on('urls.id', '=', 'latest_check.url_id');
                   })->get();
        return view('urls', ['urls' => $urls]);
    }

    public function checkUrl($urlId)
    {
        $date = Carbon::now(self::GMT);
        $data = DB::table('url_checks')->insert(['url_id' => $urlId, 'created_at' => $date]);
        return redirect()->route('url', $urlId);
    }
}
