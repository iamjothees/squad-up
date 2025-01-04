<?php

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class, RefreshDatabase::class)->group('dto', 'model-dtos');

describe('it_tests_model_dtos', function(){

    test('it_tests_from_array', function (string $dtoType, array $arrayData) {
        // Arrange

        // Act
        $dto = app($dtoType)::fromArray($arrayData);
        
        // Assert
        expect($dto)->toBeInstanceOf($dtoType);
    })
    ->with('dtosForFromArray');

    test('it_tests_from_model', function (string $dtoType, Model $model) {
        // Arrange
        
        // Act
        $dto = app($dtoType)::fromModel($model);
        
        // Assert
        expect($dto)->toBeInstanceOf($dtoType);
    })->with('dtosForFromModel');

    test('it_tests_to_model', function (string $dtoType, Model $modelData, string $modelType) {
        // Arrange
        $dto = app($dtoType)::fromModel($modelData);

        // Act
        $model = $dto->toModel();

        // Assert
        expect($model)->toBeInstanceOf($modelType);
    })->with('dtosForToConversionFromModelData');

    test('it_tests_to_array', function (string $dtoType, Model $modelData) {
        // Arrange
        $dto = app($dtoType)::fromModel($modelData);

        // Act
        $array = $dto->toArray();

        // Assert
        expect($array)->toBeArray();
    })->with('dtosForToConversionFromModelData');

    test('it_tests_to_create_array', function (string $dtoType, Model $modelData) {
        // Arrange
        $dto = app($dtoType)::fromModel($modelData);

        // Act
        $array = $dto->toCreateArray();

        // Assert
        expect($array)->toBeArray();
        expect($array)->not()->toHaveKeys(['id']);
    })->with('dtosForToConversionFromModelData');
});
