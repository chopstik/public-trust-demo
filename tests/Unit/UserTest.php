<?php

namespace Tests\Unit;

use App\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_create_users()
    {


        $user = new User();

        $this->assertEquals(5, $user->db()->query('SELECT count(*) FROM users')->fetchColumn());
    }
}


