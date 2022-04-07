<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private const GMT = '3';

    protected function setUp(): void
    {
        parent::setUp();
        $testTableSize = 10;
        for ($i = 0; $i < $testTableSize; $i++) {
            $name = $this->faker->url();
            DB::table('urls')->insert(['id' => $i, 'name' => $name, 'created_at' => now(self::GMT)]);
        }
    }

    public function testGetUrl()
    {
        $url = DB::table('urls')->select('id', 'name')->inRandomOrder()->first();
        $response = $this->get(route('url', $url->id));
        $response->assertSeeText($url->name);
    }
    

    public function testAddUrl()
    {
        $oldUrl = (array) DB::table('urls')->select('name')->inRandomOrder()->first();
        $response = $this->post(route('store', ['url' => $oldUrl]));
        $response->assertSee('Страница уже существует');

        $newUrl = ['name' => $this->faker->url()];
        $response = $this->post(route('store', ['url' => $newUrl]));
        $this->assertDatabaseHas('urls', ['name' => $newUrl]);

        $invalidUrl = ['name' => 'aaaa'];
        $response = $this->post(route('store', ['url' => $invalidUrl]));
        $this->assertDatabaseMissing('urls', ['name' => $invalidUrl]);
    }

    public function testShowUrls()
    {
        $urls = DB::table('urls')->get();
        $response = $this->get('/urls', ['urls' => $urls]);
        $response->assertOk();
    }

    public function testCheckUrl()
    {
        $id = (array) DB::table('urls')->select('id')->inRandomOrder()->first();
        Http::fake();
        $response = $this->post(route('check', $id));
        $response->assertRedirect(route('url', $id));
        $this->assertDatabaseHas('url_checks', ['url_id' => $id]);
    }
}
