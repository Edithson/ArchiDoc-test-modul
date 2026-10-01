<?php

namespace Tests\Feature;

use App\Models\Archive;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('creating a department automatically records created_by and updated_by', function () {
    $this->actingAs($this->user);

    $department = Department::create([
        'name' => 'AUDIT-DEPT',
        'description' => 'Test Audit Trail',
    ]);

    expect($department->created_by)->toBe($this->user->id);
    expect($department->updated_by)->toBe($this->user->id);
    expect($department->creator->id)->toBe($this->user->id);
});

test('updating a record updates updated_by to current authenticated user', function () {
    $this->actingAs($this->user);

    $department = Department::create([
        'name' => 'DEPT-1',
        'description' => 'Initial',
    ]);

    $anotherUser = User::factory()->create();
    $this->actingAs($anotherUser);

    $department->update(['description' => 'Updated Description']);

    $department->refresh();
    expect($department->created_by)->toBe($this->user->id);
    expect($department->updated_by)->toBe($anotherUser->id);
});

test('soft deleting a record automatically records deleted_by', function () {
    $this->actingAs($this->user);

    $department = Department::create([
        'name' => 'DEPT-DEL',
    ]);

    $deleterUser = User::factory()->create();
    $this->actingAs($deleterUser);

    $department->delete();

    $deletedDept = Department::withTrashed()->find($department->id);
    expect($deletedDept->trashed())->toBeTrue();
    expect($deletedDept->deleted_by)->toBe($deleterUser->id);
    expect($deletedDept->deleter->id)->toBe($deleterUser->id);
});

test('soft deleting an archive purges physical file on disk while preserving metadata record with deleted_by', function () {
    Storage::fake('public');

    $this->actingAs($this->user);

    $filePath = 'archives/test_doc.pdf';
    Storage::disk('public')->put($filePath, 'content file test');

    $archive = Archive::create([
        'description' => 'Archive Document Audit Test',
        'filepath' => $filePath,
    ]);

    expect(Storage::disk('public')->exists($filePath))->toBeTrue();

    $deleter = User::factory()->create();
    $this->actingAs($deleter);

    $archive->delete();

    // Verification que le fichier physique est purge du disque
    Storage::disk('public')->assertMissing($filePath);

    // Verification que la ligne de metadonnees reste conservee en soft delete avec le deleted_by
    $trashedArchive = Archive::withTrashed()->find($archive->id);
    expect($trashedArchive)->not->toBeNull();
    expect($trashedArchive->trashed())->toBeTrue();
    expect($trashedArchive->deleted_by)->toBe($deleter->id);
});

test('user model records audit fields on creation, update and soft delete', function () {
    $this->actingAs($this->user);

    $dept = Department::factory()->create(['name' => 'DI']);

    $newUser = User::create([
        'name' => 'New User Audit',
        'matricule' => 'MAT-AUDIT',
        'email' => 'audit@archidoc.cm',
        'department_id' => $dept->id,
        'password' => 'password',
    ]);

    expect($newUser->created_by)->toBe($this->user->id);

    $newUser->delete();

    $trashedUser = User::withTrashed()->find($newUser->id);
    expect($trashedUser->deleted_by)->toBe($this->user->id);
});
