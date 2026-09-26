<form method="post" action="{{ route('profile.update') }}" class="space-y-6 max-w-2xl">
  @csrf
  @method('patch')

  @if (session('status') === 'profile-updated')
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800" role="status">
      Vos informations de profil ont été mises à jour avec succès.
    </div>
  @endif

  <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

    <!-- Nom & Prénom -->
    <div class="sm:col-span-2">
      <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">
        Nom complet <span class="text-red-500">*</span>
      </label>
      <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
        class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
      @error('name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <!-- Matricule (lecture seule / saisie manuelle) -->
    <div>
      <label for="matricule" class="block text-sm font-semibold text-gray-800 mb-1">
        Matricule
      </label>
      <input id="matricule" name="matricule" type="text" value="{{ old('matricule', $user->matricule) }}" placeholder="Ex. MAT-0001"
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
      <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="+237 600 00 00 00"
        class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
      @error('phone')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <!-- E-mail -->
    <div class="sm:col-span-2">
      <label for="email" class="block text-sm font-semibold text-gray-800 mb-1">
        Adresse e-mail <span class="text-red-500">*</span>
      </label>
      <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
        class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
      @error('email')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <!-- Informations fixes non mutables en self-service -->
    <div>
      <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">
        Département / Groupe d'accès
      </label>
      <div class="rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm font-semibold text-gray-800">
        {{ $user->departement ?? 'CAB DGB' }}
      </div>
    </div>

    <div>
      <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">
        Niveau d'accès (Rôle)
      </label>
      <div class="rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm font-semibold text-brand-800">
        {{ ucfirst($user->roles ?? 'Classique') }}
      </div>
    </div>

  </div>

  <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
      Enregistrer les modifications
    </button>
  </div>
</form>
