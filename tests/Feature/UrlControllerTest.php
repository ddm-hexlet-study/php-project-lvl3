<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    public int $id;
    public string $name;

    protected function setUp(): void
    {
        parent::setUp();

        $this->name = $this->faker->url();
        $this->id = DB::table('urls')->insertGetId([
            'name' => "{$this->name}",
            'created_at' => now()
        ]);
    }

    public function testIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
        $response->assertSeeText($this->name);
    }

    public function testShow()
    {
        $response = $this->get(route('urls.show', $this->id));
        $response->assertOk();
        $response->assertSeeText($this->name);
    }

    public function testStore()
    {
        $oldUrl = ['name' => $this->name];
        $response = $this->post(route('urls.store', ['url' => $oldUrl]));
        $response->assertRedirect(route('urls.show', $this->id));

        $newUrl = ['name' => $this->faker->url()];
        $response = $this->post(route('urls.store', ['url' => $newUrl]));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('urls', ['name' => $newUrl]);

        $invalidUrl = ['name' => 'aaaa'];
        $response = $this->post(route('urls.store', ['url' => $invalidUrl]));
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('urls', ['name' => $invalidUrl]);
    }
}
