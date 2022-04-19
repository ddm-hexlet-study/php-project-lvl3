<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\Feature\UrlTestsSetUp;

class UrlControllerTest extends UrlTestsSetUp
{
    public function testIndex()
    {
        $url = DB::table('urls')->find(1);
        $response = $this->get(route('urls.index'));
        $response->assertOk();
        $response->assertSeeText($url->name);
    }

    public function testShow()
    {
        $url = DB::table('urls')->find(1);
        $response = $this->get(route('urls.show', $url->id));
        $response->assertOk();
        $response->assertSeeText($url->name);
    }

    public function testStore()
    {
        $oldUrl = DB::table('urls')->find(1);
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
}
