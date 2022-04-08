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

    private const GMT = '0';

    protected function setUp(): void
    {
        parent::setUp();
        $testTableSize = 10;
        for ($i = 0; $i < $testTableSize; $i++) {
            $name = $this->faker->url();
            DB::table('urls')->insert([
                'id' => $i, 'name' => "{$name}{$i}",
                'created_at' => now(self::GMT)
            ]);
        }
    }

    public function testNew()
    {
        $response = $this->get(route('index'));
        $response->assertOk();
    }

    public function testIndex()
    {
        $url = DB::table('urls')->select('name')->inRandomOrder()->first();
        $response = $this->get(route('urls.index'));
        $response->assertSeeText($url->name);
    }

    public function testShow()
    {
        $url = DB::table('urls')->select('id', 'name')->inRandomOrder()->first();
        $response = $this->get(route('urls.show', $url->id));
        $response->assertSeeText($url->name);
    }

    public function testStore()
    {
        $oldUrl = DB::table('urls')->select('id', 'name')->inRandomOrder()->first();
        $response = $this->post(route('urls.store', ['url' => $oldUrl]));
        $response->assertRedirect(route('urls.show', $oldUrl->id));

        $newUrl = ['name' => $this->faker->url()];
        $response = $this->post(route('urls.store', ['url' => $newUrl]));
        $this->assertDatabaseHas('urls', ['name' => $newUrl]);

        $invalidUrl = ['id' => 1, 'name' => 'aaaa'];
        $response = $this->post(route('urls.store', ['url' => $invalidUrl]));
        $this->assertDatabaseMissing('urls', ['name' => $invalidUrl]);
    }

    public function testCheck()
    {
        $id = (array) DB::table('urls')->select('id')->inRandomOrder()->first();
        Http::fake([
            '*' => HTTP::response('stub response')
        ]);
        $response = $this->post(route('urls.check', $id));
        $response->assertRedirect(route('urls.show', $id));
        $this->assertDatabaseHas('url_checks', ['url_id' => $id]);

        $response = $this->post(route('urls.check', 'dfdfdgfgfgf'));
        $response->assertRedirect(route('urls.index'));
    }
}
