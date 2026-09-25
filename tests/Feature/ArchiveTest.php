<?php

test('index page returns success and displays admin dashboard', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('admin.index');
});

test('create page returns success and passes dynamic dataset', function () {
    $response = $this->get('/archives/create');

    $response->assertStatus(200);
    $response->assertViewIs('admin.pages.archives.create');
    $response->assertViewHasAll([
        'formats',
        'archiveTypes',
        'emplacementsPhysiques',
        'emplacementsVirtuels',
        'groupesAcces',
    ]);
});
