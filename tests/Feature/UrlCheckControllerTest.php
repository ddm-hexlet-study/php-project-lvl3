<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\Feature\UrlTestsSetUp;

class UrlCheckControllerTest extends UrlTestsSetUp
{

    public function providerTestCheck()
    {
        return [
            ['title', 'Test title'],
            ['description', 'Test description'],
            ['h1', 'Test h1']
        ];
    }

    /**
     * @dataProvider providerTestCheck
     */
    public function testStore(string $key, string $value)
    {
        $url = DB::table('urls')->select('id')->find(1);
        $fakeResponse = file_get_contents('tests/fixtures/test.html');
        Http::fake([
            '*' => HTTP::response($fakeResponse)
        ]);
        $response = $this->post(route('urls.checks.store', $url->id));
        $response->assertRedirect(route('urls.show', $url->id));
        $this->assertDatabaseHas('url_checks', [$key => $value]);
    }
}
