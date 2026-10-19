<?php

use Illuminate\Support\Facades\Blade;

test('dropdownSearch component renders with options and placeholder', function () {
    $options = [
        ['id' => '1', 'name' => 'Governor Office', 'code' => 'OFF-01'],
        ['id' => '2', 'name' => 'Health Office', 'code' => 'OFF-02'],
    ];

    $view = Blade::render(
        '<x-dropdownSearch name="office_id" :options="$options" placeholder="Choose Office" labelKey="name" subtextKey="code" />',
        ['options' => $options]
    );

    expect($view)->toContain('name="office_id"');
    expect($view)->toContain('Choose Office');
    expect($view)->toContain('Governor Office');
    expect($view)->toContain('OFF-01');
    expect($view)->toContain('Health Office');
    expect($view)->toContain('OFF-02');
});

test('dropdownSearch component renders with pre-selected item', function () {
    $options = [
        ['id' => '10', 'name' => 'Provincial Engineering', 'code' => 'PEO'],
        ['id' => '20', 'name' => 'Provincial Tourism', 'code' => 'PTO'],
    ];

    $view = Blade::render(
        '<x-dropdownSearch name="office_id" :options="$options" selected="20" labelKey="name" subtextKey="code" />',
        ['options' => $options]
    );

    expect($view)->toContain('value="20"');
    expect($view)->toContain('Provincial Tourism');
    expect($view)->toContain('PTO');
});

test('dropdownSearch component supports kebab-case component alias', function () {
    $options = ['Red', 'Green', 'Blue'];

    $view = Blade::render(
        '<x-dropdown-search name="color" :options="$options" placeholder="Select Color" />',
        ['options' => $options]
    );

    expect($view)->toContain('name="color"');
    expect($view)->toContain('Select Color');
    expect($view)->toContain('Red');
    expect($view)->toContain('Green');
    expect($view)->toContain('Blue');
});
