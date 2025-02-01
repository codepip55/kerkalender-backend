@extends('main')

@section('content')
    <div class="h-screen relative">
        <!-- Fullscreen Background Image -->
        <img src="{{ asset('assets/img/login_cover.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background">

        <!-- Right-Side Login Section (1/3 width, full height) -->
        <div class="absolute right-0 top-0 w-1/3 mx-5 mt-[88px] mb-5 h-[calc(100%-88px-8px)] bg-white rounded-3xl">
            <div class="cc my-5 mt-[25%] text-center">
                <div class="text-3xl">Wachtwoord wijzigen</div>
                <form class="mt-24" action="{{ route('change-password') }}" method="POST">
                    @csrf
                    <div class="max-w-sm mx-auto mt-10">
                        <div class="mb-4 relative">
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

                            @error('email')
                            <div class="text-red-500 text-xs mt-2 text-left">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="max-w-sm mx-auto mt-1">
                        <button type="submit" class="w-full bg-black text-white py-3 mt-10 rounded-3xl">Versturen</button>
                    </div>

                    @if(session('status'))
                    <div class="text-black text-sm mt-10 text-center">{{ session('status') }}</div>
                    @endif

                </form>

                <div class="text-sm absolute bottom-10 left-0 right-0">
                    <div class="text-gray-700 mt-10"><a href="/login">Weet je 'em toch wel? <span class="text-black">Aanmelden</span></a></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            const icon = document.getElementById('toggleIcon');
            icon.classList.toggle('fa-eye-slash');
            icon.classList.toggle('fa-eye');
        });
    </script>
@endsection
