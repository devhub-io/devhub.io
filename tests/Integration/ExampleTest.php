<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testHome()
    {
         $this->visit('/')->seeInElement('title', 'DevelopHub');
    }

    /**
     * test Database
     *
     * @return void
     */
    public function testDatabase()
    {
        $this->seeInDatabase('users', [
            'name' => 'admin'
        ]);
    }
}
