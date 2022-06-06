<?php
/**
 * Created by PhpStorm.
 * User: mahfuz
 * Date: 7/8/18
 * Time: 1:21 PM
 */

namespace Tests\Unit;

use Tests\TestCase;

class ArticleApiUnitTest extends TestCase
{
    public function test_it_can_create_an_article()
    {
        $data = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph
        ];

        $this->post(route('articles.store'), $data)
            ->assertStatus(201)
            ->assertJson($data);
    }
}