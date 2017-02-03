<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class FrontTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testVisit()
    {
        $this->visit('/')->seeInElement('title', 'DevHub - Development Tools Repositories Developers Hub');

        $this->visit('/sites')->assertResponseStatus(200);
        $this->visit('/search')->assertResponseStatus(200);
        $this->visit('/developers')->assertResponseStatus(200);
        $this->visit('/search')->assertResponseStatus(200);
        // $this->visit('/sitemap')->assertResponseStatus(200);
        $this->visit('/feed')->assertResponseStatus(200);
        $this->visit('/news')->assertResponseStatus(200);
        $this->visit('/category/swift')->assertResponseStatus(200);
        $this->visit('/repos/apple-swift')->assertResponseStatus(200);
        $this->visit('/developer/01org')->assertResponseStatus(200);
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
