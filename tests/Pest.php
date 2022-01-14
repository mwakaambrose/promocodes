<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

// Uses the given test case and trait in the current folder recursively
uses(TestCase::class, DatabaseTransactions::class)->in(__DIR__);
