<form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-2xl">
  @csrf
  @method('put')

  @if (session('status') === 'password-updated')
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800" role="status">
      Votre mot de passe a été modifié avec succès.
    </div>
  @endif

  <div>
    <label for="update_password_current_password" class="block text-sm font-semibold text-gray-800 mb-1">
      Mot de passe actuel <span class="text-red-500">*</span>
    </label>
    <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" required
      class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
    @error('current_password', 'updatePassword')
      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
  </div>

  <div>
    <label for="update_password_password" class="block text-sm font-semibold text-gray-800 mb-1">
      Nouveau mot de passe <span class="text-red-500">*</span>
    </label>
    <input id="update_password_password" name="password" type="password" autocomplete="new-password" required
      class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
    @error('password', 'updatePassword')
      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
  </div>

  <div>
    <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-800 mb-1">
      Confirmer le mot de passe <span class="text-red-500">*</span>
    </label>
    <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
      class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
    @error('password_confirmation', 'updatePassword')
      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
  </div>

  <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
      Changer le mot de passe
    </button>
  </div>
</form>
