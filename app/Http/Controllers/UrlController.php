<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlController extends Controller
{
    public function getUrl($urlId)
    {
        $url = DB::table('urls')->select('name', 'id', 'created_at')->where('id', '=', $urlId)->first();
        if ($url === null) {
            abort(404);
        }
        return view('url', ['url' => $url]);
    }

    public function addUrl(Request $request)
    {
        $ddd = $request->validate([
            'url.name' => 'required|url|max:255'
        ]);
        $url = $request->input('url');
        if (DB::table('urls')->where('name', '=', $url)->exists()) {
            $request->session()->flash('status', 'Страница уже существует');
            return view('main');
        }
        $date = Carbon::now();
        DB::table('urls')->insert([
           ['name' => $url['name'], 'created_at' => $date]
        ]);
        $request->session()->flash('status', 'Страница добавлена');
        return view('main');
    }

    public function showUrls()
    {
        $urls = DB::table('urls')->get();
        return view('urls', ['urls' => $urls]);
    }
}
