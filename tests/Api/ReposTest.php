<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ReposTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testVisit()
    {
        $this->visit('/')->seeInElement('title', 'DevHub - Development Tools Repositories Developers Hub');
    }
}
