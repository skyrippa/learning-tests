<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Http\Resources\Product as ProductResource;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function create(string $model, array $attributes = [])
    {
        $product = $model::factory()->create($attributes);

        return new ProductResource($product);
    }
}
