<x-guest-layout>
  <form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <!-- Mensajes de error -->
    @if ($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div>
      <label for="email" class="block text-sm font-semibold text-gray-700">Correo electrónico</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
        class="mt-1 w-full rounded-xl border-gray-300 focus:ring-pink-400 focus:border-pink-400 transition @error('email') border-red-500 @enderror" />
      @error('email')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="password" class="block text-sm font-semibold text-gray-700">Contraseña</label>
      <input id="password" name="password" type="password" required
        class="mt-1 w-full rounded-xl border-gray-300 focus:ring-pink-400 focus:border-pink-400 transition @error('password') border-red-500 @enderror" />
      @error('password')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center justify-between text-sm">
      <label class="flex items-center gap-2 text-gray-600">
        <input type="checkbox" name="remember" class="rounded border-gray-300 text-pink-500 focus:ring-pink-300">
        Recuérdame
      </label>

      @if (Route::has('password.request'))
        <a class="text-pink-500 hover:text-pink-600 font-medium" href="{{ route('password.request') }}">
          ¿Olvidaste tu contraseña?
        </a>
      @endif
    </div>

    <button type="submit"
      class="w-full bg-pink-500 text-white py-2 rounded-xl font-semibold hover:bg-pink-600 transition transform hover:scale-[1.02] shadow-md">
      Iniciar sesión
    </button>

    <p class="text-center text-gray-600 text-sm mt-3">
      ¿Aún no tienes cuenta?
      <a href="{{ route('register') }}" class="text-pink-500 font-semibold hover:text-pink-600">
        Regístrate aquí
      </a>
    </p>
  </form>
</x-guest-layout>
