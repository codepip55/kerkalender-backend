@extends('main')

@section('content')
    <div class="h-screen relative">
        <img src="{{ asset('assets/img/register_cover.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background">

        <div class="absolute right-0 top-0 w-1/3 mx-5 mt-[88px] mb-5 h-[calc(100%-88px-8px)] bg-white rounded-3xl">
            <div class="cc my-5 mt-[25%] text-center">
                <div class="text-3xl">Welkom!</div>
                <div class="text-[13px] text-gray-700">Vul a.u.b. je gegevens in.</div>
                <form class="mt-10" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="max-w-sm mx-auto mt-10">
                        <div class="mb-5 relative">
                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                placeholder=" "
                                class="peer w-full border-b border-black focus:outline-none p-2 text-black placeholder-transparent"
                            />
                            <label
                                for="first_name"
                                class="absolute left-2 -top-3 text-gray-500 text-sm transition-all peer-placeholder-shown:top-3
                                       peer-placeholder-shown:text-base peer-placeholder-shown:text-black peer-focus:-top-3
                                       peer-focus:text-sm peer-focus:text-black"
                            >
                                Voornaam
                            </label>
                        </div>

                        <div class="mb-5 relative">
                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                placeholder=" "
                                class="peer w-full border-b border-black focus:outline-none p-2 text-black placeholder-transparent"
                            />
                            <label
                                for="last_name"
                                class="absolute left-2 -top-3 text-gray-500 text-sm transition-all peer-placeholder-shown:top-3
                                       peer-placeholder-shown:text-base peer-placeholder-shown:text-black peer-focus:-top-3
                                       peer-focus:text-sm peer-focus:text-black"
                            >
                                Achternaam
                            </label>
                        </div>

                        <div class="mb-5 relative">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder=" "
                                class="peer w-full border-b border-black focus:outline-none p-2 text-black placeholder-transparent"
                            />
                            <label
                                for="email"
                                class="absolute left-2 -top-3 text-gray-500 text-sm transition-all peer-placeholder-shown:top-3
                                       peer-placeholder-shown:text-base peer-placeholder-shown:text-black peer-focus:-top-3
                                       peer-focus:text-sm peer-focus:text-black"
                            >
                                Email
                            </label>
                        </div>

                        <div class="mb-5 relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder=" "
                                class="peer w-full border-b border-black focus:outline-none p-2 pr-10 text-black placeholder-transparent"
                            />
                            <label
                                for="password"
                                class="absolute left-2 -top-3 text-gray-500 text-sm transition-all peer-placeholder-shown:top-3
                                       peer-placeholder-shown:text-base peer-placeholder-shown:text-black peer-focus:-top-3
                                       peer-focus:text-sm peer-focus:text-black"
                            >
                                Wachtwoord
                            </label>
                            <span id="togglePassword" class="absolute right-2 top-3 text-gray-500 cursor-pointer">
                                <i id='password_icon' class="fa-solid fa-eye"></i>
                            </span>
                        </div>

                        <div class="mb-5 relative">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder=" "
                                class="peer w-full border-b border-black focus:outline-none p-2 pr-10 text-black placeholder-transparent"
                            />
                            <label
                                for="password_confirmation"
                                class="absolute left-2 -top-3 text-gray-500 text-sm transition-all peer-placeholder-shown:top-3
                                       peer-placeholder-shown:text-base peer-placeholder-shown:text-black peer-focus:-top-3
                                       peer-focus:text-sm peer-focus:text-black"
                            >
                                Wachtwoord bevestigen
                            </label>
                            <span id="toggleConfirmPassword" class="absolute right-2 top-3 text-gray-500 cursor-pointer">
                                <i id='confirm_icon' class="fa-solid fa-eye"></i>
                            </span>

                            @error('email')
                            <div class="text-red-500 text-xs mt-2 text-left">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="max-w-sm mx-auto mt-1">
                        <button type="submit" class="w-full bg-black text-white py-3 mt-10 rounded-3xl">Registreren</button>
                    </div>
                </form>

                <div class="text-sm absolute bottom-10 left-0 right-0">
                    <div class="text-gray-700 mt-10"><a href="/login">Al een account? <span class="text-black">Aanmelden</span></a></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            const icon = document.getElementById('password_icon');
            icon.classList.toggle('fa-eye-slash');
            icon.classList.toggle('fa-eye');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmPasswordField = document.getElementById('confirm_password');
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);
            const icon = document.getElementById('confirm_icon');
            icon.classList.toggle('fa-eye-slash');
            icon.classList.toggle('fa-eye');
        });
    </script>
@endsection
