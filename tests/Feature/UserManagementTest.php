<?php

use App\Models\Department;
use App\Models\User;

test('super privileged user can view user list', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    User::factory(5)->create();

    $response = $this->actingAs($superUser)->get('/users');

    $response->assertStatus(200);
    $response->assertViewIs('admin.pages.users.index');
    $response->assertViewHasAll(['users', 'departments', 'roleOptions']);
});

test('user list can be filtered by search, role, department, and status', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $dept1 = Department::factory()->create(['name' => 'DI']);
    $dept2 = Department::factory()->create(['name' => 'DCOB']);

    $activeUser = User::factory()->create([
        'name' => 'Filtre Cible Active',
        'email' => 'cible.active@archidoc.cm',
        'matricule' => 'MAT-CIBLE-01',
        'roles' => 'privilégié',
        'department_id' => $dept1->id,
        'statut' => true,
    ]);

    $suspendedUser = User::factory()->create([
        'name' => 'Filtre Cible Suspendue',
        'email' => 'cible.suspendue@archidoc.cm',
        'matricule' => 'MAT-CIBLE-02',
        'roles' => 'classique',
        'department_id' => $dept2->id,
        'statut' => false,
    ]);

    // Test filter by search
    $responseSearch = $this->actingAs($superUser)->get('/users?search=MAT-CIBLE-01');
    $responseSearch->assertSee('Filtre Cible Active');
    $responseSearch->assertDontSee('Filtre Cible Suspendue');

    // Test filter by role
    $responseRole = $this->actingAs($superUser)->get('/users?roles=privilégié');
    $responseRole->assertSee('Filtre Cible Active');
    $responseRole->assertDontSee('Filtre Cible Suspendue');

    // Test filter by status (suspended)
    $responseStatus = $this->actingAs($superUser)->get('/users?statut=0');
    $responseStatus->assertSee('Filtre Cible Suspendue');
    $responseStatus->assertDontSee('Filtre Cible Active');
});

test('non-super privileged user receives 403 forbidden on user list', function () {
    $classiqueUser = User::factory()->create([
        'roles' => 'classique',
    ]);

    $response = $this->actingAs($classiqueUser)->get('/users');

    $response->assertStatus(403);
});

test('super privileged user can create a user account', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);
    $dept = Department::factory()->create(['name' => 'DI']);

    $userData = [
        'name' => 'Jean Dupont',
        'matricule' => 'MAT-9999',
        'email' => 'j.dupont@archidoc.cm',
        'phone' => '+237 655 44 33 22',
        'roles' => 'privilégié',
        'department_id' => $dept->id,
        'statut' => '1',
        'password' => 'password123',
    ];

    $response = $this->actingAs($superUser)->post('/users', $userData);

    $response->assertRedirect(route('users.index'));
    $newUser = User::where('email', 'j.dupont@archidoc.cm')->first();
    expect($newUser)->not->toBeNull();
    expect($newUser->matricule)->toBe('MAT-9999');
    expect($newUser->department_id)->toBe($dept->id);
    expect($newUser->isPrivileged())->toBeTrue();
});

test('super privileged user can update a user account', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $targetUser = User::factory()->create([
        'name' => 'Ancien Nom',
        'roles' => 'classique',
    ]);
    $newDept = Department::factory()->create(['name' => 'DCOB']);

    $response = $this->actingAs($superUser)->put("/users/{$targetUser->id}", [
        'name' => 'Nom Mis a Jour',
        'matricule' => $targetUser->matricule,
        'email' => $targetUser->email,
        'phone' => $targetUser->phone,
        'roles' => 'privilégié',
        'department_id' => $newDept->id,
        'statut' => '1',
    ]);

    $response->assertRedirect(route('users.index'));
    expect($targetUser->fresh()->name)->toBe('Nom Mis a Jour');
    expect($targetUser->fresh()->isPrivileged())->toBeTrue();
});

test('super privileged user can toggle user status to suspend and reactivate', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $targetUser = User::factory()->create([
        'statut' => true,
    ]);

    // Suspend
    $response = $this->actingAs($superUser)->post("/users/{$targetUser->id}/toggle-status");
    $response->assertRedirect();
    expect($targetUser->fresh()->statut)->toBeFalse();

    // Reactivate
    $response2 = $this->actingAs($superUser)->post("/users/{$targetUser->id}/toggle-status");
    $response2->assertRedirect();
    expect($targetUser->fresh()->statut)->toBeTrue();
});

test('user cannot suspend or delete their own connected account', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
        'statut' => true,
    ]);

    $responseToggle = $this->actingAs($superUser)->post("/users/{$superUser->id}/toggle-status");
    $responseToggle->assertSessionHas('error');
    expect($superUser->fresh()->statut)->toBeTrue();

    $responseDelete = $this->actingAs($superUser)->delete("/users/{$superUser->id}");
    $responseDelete->assertSessionHas('error');
    expect(User::find($superUser->id))->not->toBeNull();
});

test('super privileged user can create super user without department', function () {
    $superUser = User::factory()->create(['roles' => 'super privilégé']);

    $userData = [
        'name' => 'Super Admin Test',
        'matricule' => 'MAT-SUPER-01',
        'email' => 'supertest@archidoc.cm',
        'phone' => '+237 600000000',
        'roles' => 'super privilégé',
        'department_id' => null,
        'sub_department_id' => null,
        'statut' => '1',
        'password' => 'password123',
    ];

    $response = $this->actingAs($superUser)->post('/users', $userData);

    $response->assertRedirect(route('users.index'));
    $createdSuperUser = User::where('email', 'supertest@archidoc.cm')->first();
    expect($createdSuperUser)->not->toBeNull();
    expect($createdSuperUser->isSuper())->toBeTrue();
});

test('classique user creation requires both main and sub department', function () {
    $superUser = User::factory()->create(['roles' => 'super privilégé']);
    $mainDept = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $subDept = Department::factory()->create(['name' => 'DI', 'parent_id' => $mainDept->id]);

    // Missing sub_department_id
    $invalidData = [
        'name' => 'Classique Incomplet',
        'matricule' => 'MAT-CLASS-01',
        'email' => 'classique.inc@archidoc.cm',
        'roles' => 'classique',
        'department_id' => $mainDept->id,
        'sub_department_id' => null,
        'statut' => '1',
        'password' => 'password123',
    ];

    $responseFail = $this->actingAs($superUser)->post('/users', $invalidData);
    $responseFail->assertSessionHasErrors(['sub_department_id']);

    // Valid data with sub_department_id
    $validData = array_merge($invalidData, ['sub_department_id' => $subDept->id]);
    $responseSuccess = $this->actingAs($superUser)->post('/users', $validData);

    $responseSuccess->assertRedirect(route('users.index'));
    $createdClassiqueUser = User::where('email', 'classique.inc@archidoc.cm')->first();
    expect($createdClassiqueUser)->not->toBeNull();
    expect($createdClassiqueUser->department_id)->toBe($mainDept->id);
    expect($createdClassiqueUser->sub_department_id)->toBe($subDept->id);
});

test('user creation fails if sub_department does not belong to main department', function () {
    $superUser = User::factory()->create(['roles' => 'super privilégé']);
    $main1 = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $main2 = Department::factory()->create(['name' => 'DGI', 'parent_id' => null]);
    $sub2 = Department::factory()->create(['name' => 'DGE', 'parent_id' => $main2->id]);

    $data = [
        'name' => 'Incompatible User',
        'matricule' => 'MAT-INCOMP-01',
        'email' => 'incompat@archidoc.cm',
        'roles' => 'classique',
        'department_id' => $main1->id,
        'sub_department_id' => $sub2->id,
        'statut' => '1',
        'password' => 'password123',
    ];

    $response = $this->actingAs($superUser)->post('/users', $data);
    $response->assertSessionHasErrors(['sub_department_id']);
});
