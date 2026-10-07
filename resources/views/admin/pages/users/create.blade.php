@extends('admin.layout.app')

@section('title', 'Nouveau compte utilisateur — ArchiDoc DGB')
@section('meta_description', 'Création d\'un nouveau compte utilisateur dans ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-[1000px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête -->
  <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-700 mb-1">
        <a href="{{ route('users.index') }}" class="hover:underline">Comptes utilisateurs</a>
        <span>/</span>
        <span>Nouveau compte</span>
      </div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Créer un Compte Utilisateur</h1>
      <p class="mt-1 text-sm text-gray-500">Saisissez les informations d'identification, attribuez le rôle et le groupe d'accès.</p>
    </div>

    <div>
      <a href="{{ route('users.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Retour à la liste
      </a>
    </div>
  </div>

  <!-- Formulaire -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 sm:px-8">
      <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
        <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Fiche d'identification du nouvel utilisateur
      </h2>
    </div>

    <form method="POST" action="{{ route('users.store') }}" class="p-6 sm:p-8 space-y-6">
      @csrf

      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

        <!-- Nom complet -->
        <div class="sm:col-span-2">
          <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">
            Nom complet <span class="text-red-500">*</span>
          </label>
          <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Ex. NJOYA Paul Alain"
            class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
          @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Matricule (Manuel, Unique) -->
        <div>
          <label for="matricule" class="block text-sm font-semibold text-gray-800 mb-1">
            Matricule agent <span class="text-red-500">*</span>
          </label>
          <input type="text" id="matricule" name="matricule" value="{{ old('matricule') }}" required placeholder="Ex. MAT-2026-089"
            class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
          @error('matricule')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Téléphone -->
        <div>
          <label for="phone" class="block text-sm font-semibold text-gray-800 mb-1">
            Téléphone
          </label>
          <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+237 600 00 00 00"
            class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
          @error('phone')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Adresse email -->
        <div class="sm:col-span-2">
          <label for="email" class="block text-sm font-semibold text-gray-800 mb-1">
            Adresse email <span class="text-red-500">*</span>
          </label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="jean.dupont@entreprise.com"
            class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
          @error('email')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Rôle / Niveau d'accès -->
        <div>
          <label for="role_id" class="block text-sm font-semibold text-gray-800 mb-1">
            Niveau d'accès (Rôle) <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <select id="role_id" name="role_id" required onchange="handleRoleChange(this)"
              class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-3.5 pr-8 text-sm font-bold text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <option value="" disabled {{ old('role_id') ? '' : 'selected' }}>Sélectionnez un rôle...</option>
              @foreach($rolesList as $roleOption)
                <option value="{{ $roleOption->id }}" data-role-name="{{ strtolower($roleOption->name) }}" {{ (string) old('role_id') === (string) $roleOption->id ? 'selected' : '' }}>
                  {{ $roleOption->name }} — {{ $roleOption->description ?? 'Rôle utilisateur' }}
                </option>
              @endforeach
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
          </div>
          @error('role_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Direction Principale -->
        <div>
          <label for="department_id" class="block text-sm font-semibold text-gray-800 mb-1">
            Direction Principale <span id="dept_asterisk" class="text-red-500">*</span>
          </label>
          <div class="relative">
            <select id="department_id" name="department_id" required onchange="handleDepartmentChange(this.value)"
              class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-3.5 pr-8 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              @if(auth()->user()?->isSuper())
                <option value="">-- Aucune (Accès système global pour Super Privilégié) --</option>
              @endif
              @foreach($mainDepartments as $mDept)
                <option value="{{ $mDept->id }}" {{ (string) old('department_id', auth()->user()?->isSuper() ? '' : auth()->user()?->department_id) === (string) $mDept->id ? 'selected' : '' }}>
                  {{ $mDept->name }} — {{ $mDept->description }}
                </option>
              @endforeach
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
          </div>
          <p id="dept_help" class="mt-1 text-xs text-gray-500">
            @if(!auth()->user()?->isSuper())
              Compte restreint à votre Direction d'appartenance.
            @else
              Obligatoire pour les rôles Privilégié et Classique.
            @endif
          </p>
          @error('department_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Sous-Département / Service -->
        <div>
          <label for="sub_department_id" class="block text-sm font-semibold text-gray-800 mb-1">
            Sous-Département / Service <span id="sub_dept_asterisk" class="text-red-500">*</span>
          </label>
          <div class="relative">
            <select id="sub_department_id" name="sub_department_id" onchange="handleSubDepartmentChange(this)"
              class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-3.5 pr-8 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <option value="">-- Tous les services (Accès global à la Direction) --</option>
              @foreach($mainDepartments as $mDept)
                @if($mDept->children->isNotEmpty())
                  <optgroup label="Services rattachés à : {{ $mDept->name }}" data-parent-id="{{ $mDept->id }}">
                    @foreach($mDept->children as $sDept)
                      @if(auth()->user()?->isSuper() || !auth()->user()?->sub_department_id || (string)auth()->user()?->sub_department_id === (string)$sDept->id)
                        <option value="{{ $sDept->id }}" data-parent-id="{{ $mDept->id }}" {{ (string) old('sub_department_id', auth()->user()?->isSuper() ? '' : auth()->user()?->sub_department_id) === (string) $sDept->id ? 'selected' : '' }}>
                          {{ $sDept->name }} — {{ $sDept->description }}
                        </option>
                      @endif
                    @endforeach
                  </optgroup>
                @endif
              @endforeach
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
          </div>
          <p id="sub_dept_help" class="mt-1 text-xs text-gray-500">Obligatoire pour les utilisateurs ordinaires (Classique).</p>
          @error('sub_department_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Statut initial -->
        <div>
          <label for="statut" class="block text-sm font-semibold text-gray-800 mb-1">
            Statut du compte <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <select id="statut" name="statut" required
              class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-3.5 pr-8 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <option value="1" {{ old('statut', '1') === '1' ? 'selected' : '' }}>Actif (Autorisé à se connecter)</option>
              <option value="0" {{ old('statut') === '0' ? 'selected' : '' }}>Suspendu (Accès bloqué)</option>
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
          </div>
          @error('statut')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Mot de passe initial -->
        <div>
          <label for="password" class="block text-sm font-semibold text-gray-800 mb-1">
            Mot de passe initial <span class="text-red-500">*</span>
          </label>
          <input type="password" id="password" name="password" required placeholder="Minimum 8 caractères"
            class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
          @error('password')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

      </div>

      <script>
        function handleRoleChange(selectEl) {
          const selectedOption = selectEl && selectEl.options ? selectEl.options[selectEl.selectedIndex] : null;
          const roleName = selectedOption ? (selectedOption.getAttribute('data-role-name') || '') : (typeof selectEl === 'string' ? selectEl.toLowerCase() : '');

          const deptSelect = document.getElementById('department_id');
          const subDeptSelect = document.getElementById('sub_department_id');
          const deptAsterisk = document.getElementById('dept_asterisk');
          const subDeptAsterisk = document.getElementById('sub_dept_asterisk');

          const isSuper = roleName.includes('super');
          const isClassique = roleName.includes('classic') || roleName.includes('classique');

          if (isSuper) {
            deptSelect.removeAttribute('required');
            subDeptSelect.removeAttribute('required');
            deptAsterisk.style.display = 'none';
            subDeptAsterisk.style.display = 'none';
          } else if (isClassique) {
            deptSelect.setAttribute('required', 'required');
            subDeptSelect.setAttribute('required', 'required');
            deptAsterisk.style.display = 'inline';
            subDeptAsterisk.style.display = 'inline';
          } else {
            // Privilégié ou Rôles personnalisés
            deptSelect.setAttribute('required', 'required');
            subDeptSelect.removeAttribute('required');
            deptAsterisk.style.display = 'inline';
            subDeptAsterisk.style.display = 'none';
          }
        }

        function handleDepartmentChange(selectedParentId) {
          const subDeptSelect = document.getElementById('sub_department_id');
          if (!subDeptSelect) return;
          const optgroups = subDeptSelect.querySelectorAll('optgroup');

          optgroups.forEach((group) => {
            const parentId = group.getAttribute('data-parent-id');
            if (!selectedParentId || parentId === selectedParentId) {
              group.style.display = '';
              group.disabled = false;
            } else {
              group.style.display = 'none';
              group.disabled = true;
            }
          });

          const selectedOption = subDeptSelect.options[subDeptSelect.selectedIndex];
          if (selectedOption && selectedOption.value) {
            const optionParentId = selectedOption.getAttribute('data-parent-id');
            if (selectedParentId && optionParentId !== selectedParentId) {
              subDeptSelect.value = '';
            }
          } else if (!selectedParentId) {
            subDeptSelect.value = '';
          }
        }

        function handleSubDepartmentChange(subDeptSelect) {
          if (!subDeptSelect) return;
          const selectedOption = subDeptSelect.options[subDeptSelect.selectedIndex];
          if (!selectedOption || !selectedOption.value) return;

          const parentId = selectedOption.getAttribute('data-parent-id');
          const deptSelect = document.getElementById('department_id');
          if (deptSelect && parentId) {
            deptSelect.value = parentId;
            handleDepartmentChange(parentId);
          }
        }

        document.addEventListener('DOMContentLoaded', function() {
          const roleSelect = document.getElementById('role_id');
          const deptSelect = document.getElementById('department_id');
          const subDeptSelect = document.getElementById('sub_department_id');
          if (roleSelect && roleSelect.value) handleRoleChange(roleSelect);
          if (subDeptSelect && subDeptSelect.value) {
            handleSubDepartmentChange(subDeptSelect);
          } else if (deptSelect && deptSelect.value) {
            handleDepartmentChange(deptSelect.value);
          }
        });
      </script>

      </div>

      <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
        <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
          Annuler
        </a>
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Créer le compte utilisateur
        </button>
      </div>

    </form>
  </div>

</div>
@endsection
