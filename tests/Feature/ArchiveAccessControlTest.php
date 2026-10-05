<?php

use App\Models\Archive;
use App\Models\ArchiveType;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Create base roles with default permissions
    $this->classiqueRole = Role::firstOrCreate(
        ['name' => 'Classique'],
        ['permissions' => Role::defaultPermissionsFor('Classique')]
    );

    $this->privilegieRole = Role::firstOrCreate(
        ['name' => 'Privilégié'],
        ['permissions' => Role::defaultPermissionsFor('Privilégié')]
    );

    $this->superRole = Role::firstOrCreate(
        ['name' => 'Super Privilégié'],
        ['permissions' => Role::defaultPermissionsFor('Super Privilégié')]
    );
});

test('user without archive permissions is denied access (403)', function () {
    // User with custom permissions stripping all archive rights
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Archive' => [
                'read' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
                'download' => false,
            ],
        ],
    ]);

    $type = ArchiveType::factory()->create();
    $mainDept = Department::factory()->create(['parent_id' => null]);
    $subDept = Department::factory()->create(['parent_id' => $mainDept->id]);

    $archive = Archive::factory()->create([
        'department_id' => $mainDept->id,
        'sub_department_id' => $subDept->id,
        'archive_type_id' => $type->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)->get(route('archives.index'))->assertStatus(403);
    $this->actingAs($user)->get(route('archives.create'))->assertStatus(403);
    $this->actingAs($user)->get(route('archives.show', $archive->id))->assertStatus(403);
    $this->actingAs($user)->get(route('archives.download', $archive->id))->assertStatus(403);
});

test('storing an archive assigns creator user_id dynamically from auth user', function () {
    Storage::fake('public');

    $mainDept = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $subDept = Department::factory()->create(['name' => 'DI', 'parent_id' => $mainDept->id]);

    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $mainDept->id,
        'sub_department_id' => $subDept->id,
    ]);

    $type = ArchiveType::factory()->create();
    $file = UploadedFile::fake()->create('TEST_DOC.pdf', 200, 'application/pdf');

    $data = [
        'file' => $file,
        'format' => 'Document PDF',
        'archive_type_id' => $type->id,
        'description' => 'TEST DYNAMIC USER CREATOR',
        'date_doc' => '2026-03-01',
        'emplacement' => 'SALLE 1',
        'emplacement2' => 'Serveur NAS',
        'department_id' => $mainDept->id,
        'sub_department_id' => $subDept->id,
    ];

    $response = $this->actingAs($user)->postJson(route('archives.store'), $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('archives', [
        'description' => 'TEST DYNAMIC USER CREATOR',
        'user_id' => $user->id,
    ]);
});

test('classique role user cannot view archive outside their sub_department_id (structural scope restriction)', function () {
    $deptA = Department::factory()->create(['parent_id' => null]);
    $subDeptA = Department::factory()->create(['parent_id' => $deptA->id]);

    $deptB = Department::factory()->create(['parent_id' => null]);
    $subDeptB = Department::factory()->create(['parent_id' => $deptB->id]);

    $classiqueUser = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptA->id,
        'sub_department_id' => $subDeptA->id,
    ]);

    $type = ArchiveType::factory()->create();

    $archiveInSubDeptA = Archive::factory()->create([
        'department_id' => $deptA->id,
        'sub_department_id' => $subDeptA->id,
        'archive_type_id' => $type->id,
    ]);

    $archiveInSubDeptB = Archive::factory()->create([
        'department_id' => $deptB->id,
        'sub_department_id' => $subDeptB->id,
        'archive_type_id' => $type->id,
    ]);

    // Access to archive in own sub-department -> allowed
    $this->actingAs($classiqueUser)
        ->get(route('archives.show', $archiveInSubDeptA->id))
        ->assertStatus(200);

    // Access to archive outside sub-department -> 403 Forbidden
    $this->actingAs($classiqueUser)
        ->get(route('archives.show', $archiveInSubDeptB->id))
        ->assertStatus(403);
});

test('archive location creation assigns dynamic created_by from auth user', function () {
    $user = User::factory()->create([
        'role_id' => $this->superRole->id,
    ]);

    $response = $this->actingAs($user)->post(route('archive-locations.store'), [
        'name' => 'Emplacement Test dynamic',
        'type' => 1,
        'description' => 'Description test',
    ]);

    $response->assertRedirect(route('archive-locations.index'));
    $this->assertDatabaseHas('archive_locations', [
        'name' => 'Emplacement Test dynamic',
        'created_by' => $user->id,
    ]);
});

test('archive type creation assigns dynamic created_by from auth user', function () {
    $user = User::factory()->create([
        'role_id' => $this->superRole->id,
    ]);

    $response = $this->actingAs($user)->post(route('archive-types.store'), [
        'name' => 'TYPE_DYN_TEST',
        'description' => 'Type test dynamic creator',
        'dua' => 10,
    ]);

    $response->assertRedirect(route('archive-types.index'));
    $this->assertDatabaseHas('archive_types', [
        'name' => 'TYPE_DYN_TEST',
        'created_by' => $user->id,
    ]);
});

test('department creation assigns dynamic created_by from auth user', function () {
    $user = User::factory()->create([
        'role_id' => $this->superRole->id,
    ]);

    $response = $this->actingAs($user)->post(route('departments.store'), [
        'name' => 'Direction Dynamique Test',
        'description' => 'Departement test',
    ]);

    $response->assertRedirect(route('departments.index'));
    $this->assertDatabaseHas('departments', [
        'name' => 'Direction Dynamique Test',
        'created_by' => $user->id,
    ]);
});

test('user with department and subdepartment cannot create archive in a different department or subdepartment', function () {
    Storage::fake('public');

    $deptA = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $subDeptA = Department::factory()->create(['name' => 'DI', 'parent_id' => $deptA->id]);

    $deptB = Department::factory()->create(['name' => 'DGI', 'parent_id' => null]);
    $subDeptB = Department::factory()->create(['name' => 'DGE', 'parent_id' => $deptB->id]);

    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptA->id,
        'sub_department_id' => $subDeptA->id,
    ]);

    $type = ArchiveType::factory()->create();
    $file = UploadedFile::fake()->create('RESTRICTED_DOC.pdf', 100, 'application/pdf');

    // Attempting to post into Dept B & SubDept B -> 422 Unprocessable Entity
    $data = [
        'file' => $file,
        'format' => 'Document PDF',
        'archive_type_id' => $type->id,
        'description' => 'UNAUTHORIZED DEPT CREATION',
        'date_doc' => '2026-03-01',
        'emplacement' => 'SALLE A',
        'emplacement2' => 'NAS B',
        'department_id' => $deptB->id,
        'sub_department_id' => $subDeptB->id,
    ];

    $response = $this->actingAs($user)->postJson(route('archives.store'), $data);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['department_id']);
});

test('user with department but no subdepartment can create archive in their department and any of its subdepartments', function () {
    Storage::fake('public');

    $deptA = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $subDeptA1 = Department::factory()->create(['name' => 'DI', 'parent_id' => $deptA->id]);

    $deptB = Department::factory()->create(['name' => 'DGI', 'parent_id' => null]);

    $user = User::factory()->create([
        'role_id' => $this->privilegieRole->id,
        'department_id' => $deptA->id,
        'sub_department_id' => null,
    ]);

    $type = ArchiveType::factory()->create();
    $file = UploadedFile::fake()->create('DEPT_ONLY_DOC.pdf', 100, 'application/pdf');

    // Valid: Dept A and its SubDept A1 -> allowed 201
    $validData = [
        'file' => $file,
        'format' => 'Document PDF',
        'archive_type_id' => $type->id,
        'description' => 'VALID DEPT CREATION',
        'date_doc' => '2026-03-01',
        'emplacement' => 'SALLE A',
        'emplacement2' => 'NAS A',
        'department_id' => $deptA->id,
        'sub_department_id' => $subDeptA1->id,
    ];

    $response = $this->actingAs($user)->postJson(route('archives.store'), $validData);
    $response->assertStatus(201);

    // Invalid: Attempting to post in Dept B -> fails 422
    $invalidData = [
        'file' => UploadedFile::fake()->create('INVALID.pdf', 100, 'application/pdf'),
        'format' => 'Document PDF',
        'archive_type_id' => $type->id,
        'description' => 'INVALID DEPT CREATION',
        'date_doc' => '2026-03-01',
        'emplacement' => 'SALLE B',
        'emplacement2' => 'NAS B',
        'department_id' => $deptB->id,
    ];

    $response2 = $this->actingAs($user)->postJson(route('archives.store'), $invalidData);
    $response2->assertStatus(422);
});

test('super privilégé user is unconstrained when creating archives in any department', function () {
    Storage::fake('public');

    $deptAny = Department::factory()->create(['name' => 'DGI', 'parent_id' => null]);
    $subDeptAny = Department::factory()->create(['name' => 'DGE', 'parent_id' => $deptAny->id]);

    $superUser = User::factory()->create([
        'role_id' => $this->superRole->id,
        'department_id' => null,
        'sub_department_id' => null,
    ]);

    $type = ArchiveType::factory()->create();
    $file = UploadedFile::fake()->create('SUPER_DOC.pdf', 100, 'application/pdf');

    $data = [
        'file' => $file,
        'format' => 'Document PDF',
        'archive_type_id' => $type->id,
        'description' => 'SUPER USER UNCONSTRAINED CREATION',
        'date_doc' => '2026-03-01',
        'emplacement' => 'SALLE X',
        'emplacement2' => 'NAS X',
        'department_id' => $deptAny->id,
        'sub_department_id' => $subDeptAny->id,
    ];

    $response = $this->actingAs($superUser)->postJson(route('archives.store'), $data);
    $response->assertStatus(201);
});

test('classique user sees only individual consultation history tied to their account', function () {
    $deptA = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $subDeptA = Department::factory()->create(['name' => 'DI', 'parent_id' => $deptA->id]);

    $classiqueUser1 = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptA->id,
        'sub_department_id' => $subDeptA->id,
    ]);

    $classiqueUser2 = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptA->id,
        'sub_department_id' => $subDeptA->id,
    ]);

    // Log for user 1
    $logUser1 = activity('archives')
        ->causedBy($classiqueUser1)
        ->event('archive.consultation')
        ->log('Consultation par user 1');

    // Log for user 2
    $logUser2 = activity('archives')
        ->causedBy($classiqueUser2)
        ->event('archive.consultation')
        ->log('Consultation par user 2');

    // User 1 sees only their own log
    $response = $this->actingAs($classiqueUser1)->get(route('activity-logs.archives-consultations'));
    $response->assertStatus(200);
    $response->assertSee('Consultation par user 1');
    $response->assertDontSee('Consultation par user 2');

    // User 1 attempting to get JSON show of user 2's log returns 403
    $this->actingAs($classiqueUser1)->get(route('activity-logs.show', $logUser2->id))->assertStatus(403);
});

test('privilegie user sees consultation history for subdepartments within their main department', function () {
    $deptA = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $subDeptA1 = Department::factory()->create(['name' => 'DI', 'parent_id' => $deptA->id]);

    $deptB = Department::factory()->create(['name' => 'DGI', 'parent_id' => null]);
    $subDeptB1 = Department::factory()->create(['name' => 'DGE', 'parent_id' => $deptB->id]);

    $privilegieUser = User::factory()->create([
        'role_id' => $this->privilegieRole->id,
        'department_id' => $deptA->id,
        'sub_department_id' => null,
    ]);

    $agentInDeptA = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptA->id,
        'sub_department_id' => $subDeptA1->id,
    ]);

    $agentInDeptB = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptB->id,
        'sub_department_id' => $subDeptB1->id,
    ]);

    // Log from Agent A
    activity('archives')
        ->causedBy($agentInDeptA)
        ->event('archive.consultation')
        ->log('Consultation Agent Dept A');

    // Log from Agent B
    $logB = activity('archives')
        ->causedBy($agentInDeptB)
        ->event('archive.consultation')
        ->log('Consultation Agent Dept B');

    // Privilégié user sees Agent A's log but not Agent B's log
    $response = $this->actingAs($privilegieUser)->get(route('activity-logs.archives-consultations'));
    $response->assertStatus(200);
    $response->assertSee('Consultation Agent Dept A');
    $response->assertDontSee('Consultation Agent Dept B');

    // Privilégié user attempting to view Agent B's log details returns 403
    $this->actingAs($privilegieUser)->get(route('activity-logs.show', $logB->id))->assertStatus(403);
});
