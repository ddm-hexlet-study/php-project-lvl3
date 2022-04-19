<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

abstract class UrlTestsSetUp extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $name = $this->faker->url();
        DB::table('urls')->insert([
                'id' => 1,
                'name' => "{$name}",
                'created_at' => now()
            ]);
    }
}
