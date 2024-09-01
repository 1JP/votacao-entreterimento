<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\CategoryType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_category(): void
    {
        $category_type = CategoryType::factory()->create();

        $category = Category::factory()->create([
            'category_type_id' => $category_type->id,
            'name' => 'Batman Cavaleiro das trevas'
        ]);

        $this->assertEquals($category->type->id, $category_type->id);
        $this->assertEquals($category->name, 'Batman Cavaleiro das trevas');
        $this->assertTrue($category->active);
    }

    public function test_update_category(): void
    {
        $category_type_old = CategoryType::factory()->create();
        $category_type_new = CategoryType::factory()->create();

        $category = Category::factory()->create([
            'category_type_id' => $category_type_old->id,
            'name' => 'Batman Cavaleiro das trevas'
        ]);

        $category->update([
            'category_type_id' => $category_type_new->id,
            'name' => 'Super-man o retorno',
        ]);

        $this->assertEquals($category->type->id, $category_type_new->id);
        $this->assertEquals($category->name, 'Super-man o retorno');
        $this->assertTrue($category->active);

        $category->update([
            'category_type_id' => $category_type_new->id,
            'name' => 'Super-man o retorno',
            'active' => false,
        ]);

        $this->assertEquals($category->type->id, $category_type_new->id);
        $this->assertEquals($category->name, 'Super-man o retorno');
        $this->assertFalse($category->active);
    }

    public function test_delete_category():void
    {
        $category_type = CategoryType::factory()->create();

        $category = Category::factory()->create([
            'category_type_id' => $category_type->id,
            'name' => 'Batman Cavaleiro das trevas'
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Batman Cavaleiro das trevas',
        ]);

        $category->delete();

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
            'name' => 'Batman Cavaleiro das trevas',
        ]);
    }

    public function test_not_create_category()
    {
        $this->expectException(InvalidArgumentException::class);

        $category_type = CategoryType::factory()->create();

        Category::create([
            'category_type_id' => $category_type->id,
            'name' => 123,
            'active' => fake()->name(),
        ]);
    }

    public function test_not_create_name_with_max_45_characters_category()
    {
        $this->expectException(InvalidArgumentException::class);

        $category_type = CategoryType::factory()->create();

        $longString = "Esta é uma string que contém mais de 45 caracteres, para testar a validação e outras funções.";

        Category::create([
            'category_type_id' => $category_type->id,
            'name' => $longString,
            'active' => true,
        ]);
    }
}

