<x-guest-layout>
  <form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <div class="text-center mb-4">
      <h2 class="text-2xl font-bold text-pink-600">Crear cuenta</h2>
      <p class="text-sm text-gray-500">Regístrate para acceder al sistema</p>
    </div>

    <!-- Nombre -->
    <div>
      <label for="name" class="block text-sm font-semibold text-gray-700">Nombre completo</label>
      <input id="name" name="name" type="text" required autofocus
        class="mt-1 w-full rounded-xl border-gray-300 focus:ring-pink-400 focus:border-pink-400 transition" />
    </div>

    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-semibold text-gray-700">Correo electrónico</label>
      <input id="email" name="email" type="email" required
        class="mt-1 w-full rounded-xl border-gray-300 focus:ring-pink-400 focus:border-pink-400 transition" />
    </div>

    <!-- Password -->
    <div>
      <label for="password" class="block text-sm font-semibold text-gray-700">Contraseña</label>
      <input id="password" name="password" type="password" required
        class="mt-1 w-full rounded-xl border-gray-300 focus:ring-pink-400 focus:border-pink-400 transition" />
    </div>

    <!-- Confirmar Password -->
    <div>
      <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirmar contraseña</label>
      <input id="password_confirmation" name="password_confirmation" type="password" required
        class="mt-1 w-full rounded-xl border-gray-300 focus:ring-pink-400 focus:border-pink-400 transition" />
    </div>

    <!-- Botón -->
    <button type="submit"
      class="w-full bg-pink-500 text-white py-2 rounded-xl font-semibold hover:bg-pink-600 transition transform hover:scale-[1.02] shadow-md">
      Registrarme
    </button>

    <!-- Link a login -->
    <p class="text-center text-gray-600 text-sm mt-3">
      ¿Ya tienes una cuenta?
      <a href="{{ route('login') }}" class="text-pink-500 font-semibold hover:text-pink-600">
        Inicia sesión aquí
      </a>
    </p>
  </form>
</x-guest-layout>
