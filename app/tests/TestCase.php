<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use App\Constants\AppConstant;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
