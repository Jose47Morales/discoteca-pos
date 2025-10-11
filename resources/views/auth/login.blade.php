@extends('layouts.auth')

@section('content')
    <div class="flex mb-6 border-b border-gray-700">
        <button class="w-1/2 py-2 text-center font-semibold text-purple-400 border-b-2 border-purple-500">
            Iniciar Sesión
        </button>
        <a href="{{ route('register') }}" 
           class="w-1/2 py-2 text-center font-semibold text-gray-400 hover:text-purple-300">
            Registrarse
        </a>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm text-gray-300">Correo</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                    <i class="fas fa-envelope"></i>
                </span>
                <input id="email" class="pr-3 py-2 rounded-lg bg-gray-800 text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:outlinenone w-full pl-10" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                        placeholder="Introduce tu correo" required autofocus/>
            </div>
            @error('email')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm text-gray-300">Contraseña</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                    <i class="fas fa-lock"></i>
                </span>
                <input id="password" class="pr-3 py-2 rounded-lg bg-gray-800 text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:outlinenone w-full pl-10" type="password" name="password" required autocomplete="current-password" placeholder="Introduce tu contraseña"/>
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 toggle-password cursor-pointer"
                      data-target="password" aria-hidden="true">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
            @error('password')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="w-full py-2 mt-4 text-center font-semibold text-white bg-purple-500 rounded-lg hover:bg-purple-600">
            Iniciar
        </button>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-password').forEach(function (el) {
                el.addEventListener('click', function () {
                    const targetId = this.dataset.target;
                    const input = document.getElementById(targetId);

                    if (!input) return;
                    input.type = input.type === 'password' ? 'text' : 'password';
                    
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            });
        });
    </script>
    @endpush

@endsection