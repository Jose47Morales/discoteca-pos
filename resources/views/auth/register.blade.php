@extends('layouts.auth')

@section('content')
    <div class="flex mb-6 border-b border-gray-700">
        <a href="{{ route('login') }}" 
           class="w-1/2 py-2 text-center font-semibold text-gray-400 hover:text-purple-300">
            Iniciar Sesión
        </a>
        <button class="w-1/2 py-2 text-center font-semibold text-purple-400 border-b-2 border-purple-500">
            Registrarse
        </button>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm text-gray-300">Nombre</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                    <i class="fas fa-user"></i>
                </span>
                <input id="name" class="pr-3 py-2 rounded-lg bg-gray-800 text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:outlinenone w-full pl-10" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" 
                        placeholder="Introduce tu nombre" required autofocus/>
            </div>
            @error('name')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm text-gray-300">Correo Electrónico</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                    <i class="fas fa-envelope"></i>
                </span>
                <input id="email" class="pr-3 py-2 rounded-lg bg-gray-800 text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:outlinenone w-full pl-10" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" 
                        placeholder="Introduce tu correo electrónico" required autofocus/>
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
                <input id="password" class="pr-3 py-2 rounded-lg bg-gray-800 text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:outlinenone w-full pl-10" type="password" name="password" required autocomplete="new-password" placeholder="Introduce tu contraseña"/>
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 toggle-password cursor-pointer"
                      data-target="password" aria-hidden="true">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
            @error('password')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm text-gray-300">Confirmar Contraseña</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                    <i class="fas fa-lock"></i>
                </span>
                <input id="password_confirmation" class="pr-3 py-2 rounded-lg bg-gray-800 text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:outlinenone w-full pl-10" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirma tu contraseña"/>
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 toggle-password cursor-pointer"
                      data-target="password_confirmation" aria-hidden="true">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
        </div>

        <!-- Confirmar -->
        <button type="submit" class="w-full py-2 mt-4 text-center font-semibold text-white bg-purple-500 rounded-lg hover:bg-purple-600">
            Registrarse
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