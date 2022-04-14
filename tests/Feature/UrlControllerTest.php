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

    protected int $randomId;

    protected function setUp(): void
    {
        parent::setUp();

        $name = $this->faker->url();
        $collectionOfId[] = DB::table('urls')->insertGetId([
                'id' => 1,
                'name' => "{$name}",
                'created_at' => now()
            ]);
    }

    public function testIndex()
    {
        $url = DB::table('urls')->first();
        $response = $this->get(route('urls.index'));
        $response->assertOk();
        $response->assertSeeText($url->name);
    }

    public function testShow()
    {
        $url = DB::table('urls')->first();
        $response = $this->get(route('urls.show', $url->id));
        $response->assertOk();
        $response->assertSeeText($url->name);
    }

    public function testStore()
    {
        $oldUrl = DB::table('urls')->first();
        $response = $this->post(route('urls.store', ['url' => $oldUrl]));
        $response->assertRedirect(route('urls.show', $oldUrl->id));

        $newUrl = ['name' => $this->faker->url()];
        $response = $this->post(route('urls.store', ['url' => $newUrl]));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('urls', ['name' => $newUrl]);

        $invalidUrl = ['name' => 'aaaa'];
        $response = $this->post(route('urls.store', ['url' => $invalidUrl]));
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('urls', ['name' => $invalidUrl]);
    }

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
    public function testCheck($key, $value)
    {
        $url = (array) DB::table('urls')->select('id')->inRandomOrder()->first();
        $fakeResponse = file_get_contents('tests/fixtures/test.html');
        Http::fake([
            '*' => HTTP::response($fakeResponse)
        ]);
        $response = $this->post(route('urls.check', $url['id']));
        $response->assertRedirect(route('urls.show', $url['id']));
        $this->assertDatabaseHas('url_checks', [$key => $value]);
    }
}
