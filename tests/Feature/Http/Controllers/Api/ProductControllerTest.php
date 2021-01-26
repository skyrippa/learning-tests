<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Resources\Product;
use Faker\Factory;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_create_a_collection_of_paginated_products()
    {
        $product1 = $this->create('App\Models\Product');
        $product2 = $this->create('App\Models\Product');
        $product3 = $this->create('App\Models\Product');

        $response = $this->json('GET', '/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'price', 'created_at']
                ],

                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'last_page', 'from',
                    'to', 'path', 'per_page', 'total'
                ]
            ]);

        \Log::info($response->getContent());
    }

    /**
     * @test
     */
    public function can_create_a_product()
    {
        $faker = Factory::create();

        $response = $this->json('POST', '/api/products', [
            'name' => $name = $faker->company,
            'slug' => Str::slug($name),
            'price' => $price = rand(10, 100)
        ]);

        \Log::info(1, [$response->getContent()]);

        $response->assertJsonStructure([
            'id', 'name', 'slug', 'price', 'created_at'
        ])
            ->assertJson([
                'name' => $name,
                'slug' => Str::slug($name),
                'price' => $price
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $price
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_404_if_product_is_not_found()
    {
        $response = $this->json('GET', 'api/products/-1');

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_return_a_product()
    {
        // Given
        $product = $this->create('App\Models\Product');

        // When
        $response = $this->json('GET', "api/products/$product->id");

        // Then
        $response->assertStatus(200)
            ->assertExactJson([
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'created_at' => (string)$product->created_at
            ]);
    }

    /**
     * @test
     */
    public function will_fail_with_404_if_product_we_want_to_update_doesnt_exist()
    {
        $response = $this->json('PUT', 'api/products/-1');

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_update_a_product()
    {
        $product = $this->create('App\Models\Product');

        $response = $this->json('PUT', "api/products/$product->id", [
            'name' => $product->name.'_updated',
            'slug' => Str::slug($product->name).'_updated',
            'price' => $product->price + 10
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                'id' => $product->id,
                'name' => $product->name.'_updated',
                'slug' => Str::slug($product->name).'_updated',
                'price' => $product->price + 10,
                'created_at' => (string)$product->created_at
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name.'_updated',
            'slug' => Str::slug($product->name).'_updated',
            'price' => $product->price + 10,
            'created_at' => (string)$product->created_at,
            'updated_at' => (string)$product->updated_at
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_404_if_product_to_be_deleted_doesnt_exist()
    {
        $response = $this->json('DELETE', 'api/products/-1');

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_delete_a_product()
    {
        $product = $this->create('App\Models\Product');

        $response = $this->json('DELETE', "api/products/$product->id");

        $response->assertStatus(204)
            ->assertSee(null);

        $this->assertDatabaseMissing('products', [
           'id' => $product->id
        ]);
    }
}
