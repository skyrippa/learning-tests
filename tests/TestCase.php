<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function create(string $model, string $resource, array $attributes = [], $hasResource = true)
    {
        $resourceModel = $model::factory()->create($attributes);

        if (!$hasResource)
            return $resourceModel;

        return new $resource($resourceModel);
    }
}
