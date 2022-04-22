<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UrlCheckControllerTest extends TestCase
{
    public int $id;

    protected function setUp(): void
    {
        parent::setUp();

        $name = $this->faker->url();
        $this->id = DB::table('urls')->insertGetId([
            'name' => "{$name}",
            'created_at' => now()
        ]);
    }

    public function testStore()
    {
        $controlData = [
            ['title', 'Test title'],
            ['description', 'Test description'],
            ['h1', 'Test h1']
        ];
        $fakeResponse = file_get_contents('tests/fixtures/test.html');
        Http::fake([
            '*' => HTTP::response($fakeResponse)
        ]);
        $response = $this->post(route('urls.checks.store', $this->id));
        $response->assertRedirect(route('urls.show', $this->id));
        $this->assertDatabaseHas('url_checks', $controlData);
    }
}
