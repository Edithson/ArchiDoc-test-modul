<?php

use App\Models\Archive;
use App\Models\ArchiveLocation;
use App\Models\ArchiveType;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');

    // Create Departments
    $this->deptA = Department::factory()->create(['name' => 'Direction A']);
    $this->subDeptA1 = Department::factory()->create(['name' => 'Service A1', 'parent_id' => $this->deptA->id]);
    $this->subDeptA2 = Department::factory()->create(['name' => 'Service A2', 'parent_id' => $this->deptA->id]);

    $this->deptB = Department::factory()->create(['name' => 'Direction B']);
    $this->subDeptB1 = Department::factory()->create(['name' => 'Service B1', 'parent_id' => $this->deptB->id]);

    // Roles
    $this->roleSuper = Role::findByName('Super privilégié') ?? Role::factory()->create([
        'name' => 'Super privilégié',
        'permissions' => Role::defaultPermissionsFor('Super privilégié'),
    ]);

    $this->rolePrivilege = Role::findByName('Privilégié') ?? Role::factory()->create([
        'name' => 'Privilégié',
        'permissions' => Role::defaultPermissionsFor('Privilégié'),
    ]);

    $this->roleClassique = Role::findByName('Classique') ?? Role::factory()->create([
        'name' => 'Classique',
        'permissions' => Role::defaultPermissionsFor('Classique'),
    ]);

    // Create Test Users
    $this->userSuper = User::factory()->create([
        'role_id' => $this->roleSuper->id,
        'department_id' => $this->deptA->id,
        'sub_department_id' => $this->subDeptA1->id,
        'statut' => true,
    ]);

    $this->userPrivilegeA = User::factory()->create([
        'role_id' => $this->rolePrivilege->id,
        'department_id' => $this->deptA->id,
        'sub_department_id' => $this->subDeptA1->id,
        'statut' => true,
    ]);

    $this->userPrivilegeB = User::factory()->create([
        'role_id' => $this->rolePrivilege->id,
        'department_id' => $this->deptB->id,
        'sub_department_id' => $this->subDeptB1->id,
        'statut' => true,
    ]);

    $this->userClassiqueA1 = User::factory()->create([
        'role_id' => $this->roleClassique->id,
        'department_id' => $this->deptA->id,
        'sub_department_id' => $this->subDeptA1->id,
        'statut' => true,
    ]);

    $this->userClassiqueA2 = User::factory()->create([
        'role_id' => $this->roleClassique->id,
        'department_id' => $this->deptA->id,
        'sub_department_id' => $this->subDeptA2->id,
        'statut' => true,
    ]);

    // Types and Locations
    $this->archiveType = ArchiveType::factory()->create(['name' => 'Bordereau']);
    $this->archiveLocation = ArchiveLocation::factory()->create(['name' => 'Rayonnage A1']);
});

test('1. Large PDF uploads using actual files from public/media_test', function () {
    $pdfPath = public_path('media_test/cours-sql-sh-.pdf');
    if (! file_exists($pdfPath)) {
        $this->markTestSkipped('media_test sample PDF not found');
    }

    $uploadedFile = new UploadedFile(
        $pdfPath,
        'cours-sql-sh-.pdf',
        'application/pdf',
        null,
        true
    );

    $response = $this->actingAs($this->userSuper)->post(route('archives.store'), [
        'file' => $uploadedFile,
        'format' => 'PDF',
        'description' => 'Archive Test SQL PDF de Formation',
        'date_doc' => '2026-01-15',
        'emplacement' => 'Bâtiment Principal A',
        'emplacement2' => 'Salle des Archives n°2',
        'archive_type_id' => $this->archiveType->id,
        'department_id' => $this->deptA->id,
        'sub_department_id' => $this->subDeptA1->id,
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('archives', [
        'description' => 'Archive Test SQL PDF de Formation',
    ]);

    $archive = Archive::where('description', 'Archive Test SQL PDF de Formation')->first();
    expect($archive->filepath)->not->toBeEmpty();
});

test('2. Exhaustive Role-Based Access Control on Settings and Administration', function () {
    // Super Privilégié can access and update settings
    $this->actingAs($this->userSuper)->get(route('settings.index'))->assertOk();

    // Privilégié can read settings but CANNOT update settings -> 403
    $this->actingAs($this->userPrivilegeA)->get(route('settings.index'))->assertOk();
    $this->actingAs($this->userPrivilegeA)->post(route('settings.update'), ['app_name' => 'Hacked App'])->assertForbidden();

    // Classique CANNOT access settings -> 403
    $this->actingAs($this->userClassiqueA1)->get(route('settings.index'))->assertForbidden();

    // Super Privilégié can view roles
    $this->actingAs($this->userSuper)->get(route('roles.index'))->assertOk();

    // Classique CANNOT view roles or users -> 403
    $this->actingAs($this->userClassiqueA1)->get(route('roles.index'))->assertForbidden();
    $this->actingAs($this->userClassiqueA1)->get(route('users.index'))->assertForbidden();
});

test('3. Cross-Department IDOR Prevention on Archives', function () {
    // Create archive in Dept B
    $archiveB = Archive::factory()->create([
        'department_id' => $this->deptB->id,
        'sub_department_id' => $this->subDeptB1->id,
        'created_by' => $this->userPrivilegeB->id,
    ]);

    // Privilégié from Dept A attempts to view archive from Dept B -> 403
    $this->actingAs($this->userPrivilegeA)->get(route('archives.show', $archiveB))->assertForbidden();

    // Classique from Dept A attempts to update archive from Dept B -> 403
    $this->actingAs($this->userClassiqueA1)->put(route('archives.update', $archiveB), [
        'description' => 'Hacked Title',
        'format' => 'PDF',
        'date_doc' => '2026-01-01',
        'emplacement' => 'E1',
        'emplacement2' => 'E2',
        'archive_type_id' => $this->archiveType->id,
        'department_id' => $this->deptB->id,
    ])->assertForbidden();

    // Classique from Dept A attempts to delete archive from Dept B -> 403
    $this->actingAs($this->userClassiqueA1)->delete(route('archives.destroy', $archiveB))->assertForbidden();

    // Super Privilégié CAN access archive from Dept B -> 200
    $this->actingAs($this->userSuper)->get(route('archives.show', $archiveB))->assertOk();
});

test('4. Cross-Subdepartment Scoping for Classique Users', function () {
    // Create archive in SubDept A2
    $archiveA2 = Archive::factory()->create([
        'department_id' => $this->deptA->id,
        'sub_department_id' => $this->subDeptA2->id,
        'created_by' => $this->userClassiqueA2->id,
    ]);

    // Classique in SubDept A1 CANNOT view archive in SubDept A2 -> 403
    $this->actingAs($this->userClassiqueA1)->get(route('archives.show', $archiveA2))->assertForbidden();

    // Privilégié in Dept A CAN view archive in SubDept A2 -> 200
    $this->actingAs($this->userPrivilegeA)->get(route('archives.show', $archiveA2))->assertOk();
});

test('5. Privilege Escalation Prevention on User Creation', function () {
    // Privilégié A attempts to create a Super Privilégié user -> Validation Error 422
    $response = $this->actingAs($this->userPrivilegeA)->post(route('users.store'), [
        'name' => 'Attacker User',
        'matricule' => 'MAT-ATTACK-01',
        'email' => 'attacker@dgb.cm',
        'role_id' => $this->roleSuper->id,
        'department_id' => $this->deptA->id,
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'statut' => 1,
    ]);

    $response->assertSessionHasErrors(['role_id']);
    $this->assertDatabaseMissing('users', ['email' => 'attacker@dgb.cm']);
});

test('6. Cross-Department User Management Prevention', function () {
    // Privilégié A attempts to edit user from Dept B -> 403
    $this->actingAs($this->userPrivilegeA)->get(route('users.edit', $this->userPrivilegeB))->assertForbidden();

    // Privilégié A attempts to toggle status of user from Dept B -> 403
    $this->actingAs($this->userPrivilegeA)->post(route('users.toggle-status', $this->userPrivilegeB))->assertForbidden();
});

test('7. Performance Benchmark: Query execution and response time on Dashboard', function () {
    DB::enableQueryLog();
    $startTime = microtime(true);

    $response = $this->actingAs($this->userSuper)->get(route('archives.index'));

    $endTime = microtime(true);
    $queries = DB::getQueryLog();
    $durationMs = ($endTime - $startTime) * 1000;

    $response->assertOk();
    expect($durationMs)->toBeLessThan(2000);
});
