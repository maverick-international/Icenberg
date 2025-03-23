<?php

use MVRK\Icenberg\Icenberg;


test('it initializes with layout', function () {
    $ice = new Icenberg('test_layout');
    expect($ice->layout)->toBe('test_layout');
});

test('it returns an element string from get_element', function () {
    $ice = new Icenberg('test_layout');
    $output = $ice->get_element('title');
    expect($output)->toBeString();
});

test('it echoes element via the_element', function () {
    $ice = new Icenberg('test_layout');
    ob_start();
    $ice->the_element('title');
    $output = ob_get_clean();
    expect($output)->toBeString();
});

test('it handles field() with and without comma', function () {
    $ice = new Icenberg('test_layout');
    $ice->field('title');
    expect($ice->field)->toBe('Test Value');
    $ice->field('title,option');
    expect($ice->field)->toBe('Test Value');
});

test('it returns null if field is not found', function () {
    Functions\when('get_sub_field')->justReturn(null);
    $ice = new Icenberg('test_layout');
    $result = $ice->field('nonexistent');
    expect($result)->toBeNull();
});

test('it prunes group fields correctly', function () {
    $ice = new Icenberg('test_layout');
    $ice->field_object = [
        'type' => 'group',
        'value' => ['keep' => 'yes', 'remove' => 'no'],
        'sub_fields' => [
            ['name' => 'keep'],
            ['name' => 'remove'],
        ]
    ];
    $ice->prune(['remove']);
    expect($ice->field_object['value'])->toHaveKey('keep');
    expect($ice->field_object['value'])->not->toHaveKey('remove');
});

test('it prunes repeater fields correctly', function () {
    $ice = new Icenberg('test_layout');
    $ice->field_object = [
        'type' => 'repeater',
        'value' => [
            ['keep' => '1', 'remove' => '2'],
            ['keep' => '3', 'remove' => '4'],
        ],
        'sub_fields' => [
            ['name' => 'keep'],
            ['name' => 'remove'],
        ]
    ];
    $ice->prune(['remove']);
    foreach ($ice->field_object['value'] as $row) {
        expect($row)->not->toHaveKey('remove');
    }
});

test('it includes only specified group fields', function () {
    $ice = new Icenberg('test_layout');
    $ice->field_object = [
        'type' => 'group',
        'value' => ['include' => 'yes', 'skip' => 'no'],
        'sub_fields' => [
            ['name' => 'include'],
            ['name' => 'skip'],
        ]
    ];
    $ice->only(['include']);
    expect($ice->field_object['value'])->toHaveKey('include');
    expect($ice->field_object['value'])->not->toHaveKey('skip');
});

test('it includes only specified repeater fields', function () {
    $ice = new Icenberg('test_layout');
    $ice->field_object = [
        'type' => 'repeater',
        'value' => [
            ['include' => '1', 'skip' => '2'],
        ],
        'sub_fields' => [
            ['name' => 'include'],
            ['name' => 'skip'],
        ]
    ];
    $ice->only(['include']);
    foreach ($ice->field_object['value'] as $row) {
        expect($row)->not->toHaveKey('skip');
    }
});

test('it returns correct HTML from get()', function () {
    $ice = new Icenberg('test_layout');
    $ice->field_object = ['type' => 'group', 'value' => [], 'sub_fields' => []];
    $html = $ice->get();
    expect($html)->toContain('<div>');
});

test('it wraps and renders HTML via get_enclose', function () {
    $ice = new Icenberg('cta');
    $html = $ice->get_enclose('text', ['<p>Hello</p>', '<p>World</p>']);
    expect($html)->toContain('block--cta__text');
});

test('it checks equality with is()', function () {
    $ice = new Icenberg('test_layout');
    $ice->field = 'yes';
    expect($ice->is('yes'))->toBeTrue();
    expect($ice->is('no'))->toBeFalse();
});

test('it returns true from has() if condition is present', function () {
    $ice = new Icenberg('test_layout');
    $ice->field = ['a', 'b', 'c'];
    expect($ice->has('b'))->toBeTrue();
    expect($ice->has('z'))->toBeNull();
});

test('it handles greaterThan correctly for allowed types', function () {
    $ice = new Icenberg('test_layout');
    $ice->field = '10';
    $ice->field_object = ['type' => 'number'];
    expect($ice->greaterThan(5))->toBeTrue();
    expect($ice->greaterThan(15))->toBeFalse();
});

test('it handles lessThan correctly for allowed types', function () {
    $ice = new Icenberg('test_layout');
    $ice->field = '10';
    $ice->field_object = ['type' => 'number'];
    expect($ice->lessThan(15))->toBeTrue();
    expect($ice->lessThan(5))->toBeFalse();
});

test('it returns null from get_buttons when field not found', function () {
    $ice = new Icenberg('test_layout');
    $result = $ice->get_buttons('missing');
    expect($result)->toBeNull();
});

test('it returns buttons HTML from get_buttons', function () {
    $ice = new Icenberg('test_layout');
    $ice->field_object = ['type' => 'buttons', 'value' => 'click me'];
    $html = $ice->get_buttons('title');
    expect($html)->toBeString();
});

test('it echoes buttons via the_buttons', function () {
    $ice = new Icenberg('test_layout');
    ob_start();
    $ice->the_buttons('title');
    $output = ob_get_clean();
    expect($output)->toBeString();
});

test('it echoes enclosed content via enclose', function () {
    $ice = new Icenberg('test_layout');
    ob_start();
    $ice->enclose('content', ['<p>A</p>', '<p>B</p>']);
    $out = ob_get_clean();
    expect($out)->toContain('block--test-layout__content');
});
