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
        $this->assertDatabaseHas('urls', ['name' => $this->name]);
    }

    public function testShow()
    {
        $response = $this->get(route('urls.show', $this->id));
        $response->assertOk();
        $this->assertDatabaseHas('urls', ['name' => $this->name]);
    }

    public function testStoreOldData()
    {
        $oldUrl = ['name' => $this->name];
        $response = $this->post(route('urls.store', ['url' => $oldUrl]));
        $response->assertRedirect(route('urls.show', $this->id));
    }

    public function testStoreNewData()
    {
        $newUrl = ['name' => $this->faker->url()];
        $this->post(route('urls.store', ['url' => $newUrl]));
        $this->assertDatabaseHas('urls', ['name' => $newUrl]);
    }

    public function testStoreInvalidData()
    {
        $invalidUrl = ['name' => 'aaaa'];
        $this->post(route('urls.store', ['url' => $invalidUrl]));
        $this->assertDatabaseMissing('urls', ['name' => $invalidUrl]);
    }
}
