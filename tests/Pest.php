<?php

use Brain\Monkey;
use Brain\Monkey\Functions;

Functions\when('get_sub_field')->alias(function ($key) {
    return match ($key) {
        'title' => 'Test Title',
        'number' => '42',
        default => null
    };
});

Functions\when('get_sub_field_object')->alias(function ($key) {
    return match ($key) {
        'title' => ['type' => 'text', 'value' => 'Test Title'],
        'number' => ['type' => 'number', 'value' => '42'],
        default => null
    };
});

Functions\when('get_sub_field')->alias(fn($key) => 'Test Value');
Functions\when('get_sub_field_object')->alias(fn($key) => ['type' => 'text', 'value' => 'Test Value']);
Functions\when('get_field')->justReturn(null);
Functions\when('get_field_object')->justReturn(null);
Functions\when('get_field')->justReturn(null);
Functions\when('get_field_object')->justReturn(null);

beforeEach(function () {
    Monkey\setUp();
});

afterEach(function () {
    Monkey\tearDown();
});
