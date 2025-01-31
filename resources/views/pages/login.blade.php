@extends('main')

@section('content')
    <div class="h-screen relative">
        <!-- Fullscreen Background Image -->
        <img src="{{ asset('assets/img/login_cover.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background">

        <!-- Right-Side Login Section (1/3 width, full height) -->
        <div class="absolute right-0 top-0 w-1/3 mx-5 mt-[88px] mb-5 h-[calc(100%-88px-8px)] bg-white rounded-3xl">
            <div class="cc my-5 mt-[25%] text-center">
                <div class="text-3xl">Welkom terug!</div>
                <div class="text-[13px] text-gray-700">Vul a.u.b. je gegevens in.</div>
                <form class="mt-24">
                    @csrf
                    <div class="max-w-sm mx-auto mt-10">
                        <div class="mb-4 relative">
                            <input
                                type="email"
                                id="email"
                                placeholder=" "
                                class="peer w-full border-b border-black focus:outline-none p-2 text-black placeholder-transparent"
                            />
                            <label
                                for="email"
                                class="absolute left-2 top-0 text-gray-500 text-base transition-all peer-placeholder-shown:top-3
                                       peer-placeholder-shown:text-base peer-placeholder-shown:text-black peer-focus:-top-3
                                       peer-focus:text-sm peer-focus:text-black"
                            >
                                Email
                            </label>
                        </div>

                        <div class="mb-4 relative">
                            <input
                                type="password"
                                id="password"
                                placeholder=" "
                                class="peer w-full border-b border-black focus:outline-none p-2 pr-10 text-black placeholder-transparent"
                            />
                            <label
                                for="email"
                                class="absolute left-2 top-0 text-gray-500 text-base transition-all peer-placeholder-shown:top-3
                                       peer-placeholder-shown:text-base peer-placeholder-shown:text-black peer-focus:-top-3
                                       peer-focus:text-sm peer-focus:text-black"
                            >
                                Password
                            </label>
                            <span class="absolute right-2 top-3 text-gray-500 cursor-pointer">
                                <i class="fa-solid fa-eye"></i>
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded border-black accent-black" checked/>
                                <span>Onthoud mij voor 30 dagen</span>
                            </label>
                            <a href="#" class="text-gray-500 hover:text-gray-700">Wachtwoord vergeten?</a>
                        </div>
                    </div>

                    <div class="max-w-sm mx-auto mt-1">
                        <button type="submit" class="w-full bg-black text-white py-3 mt-10 rounded-3xl">Aanmelden</button>
                    </div>
                </form>

                <div class="text-sm absolute bottom-10 left-0 right-0">
                    <div class="text-gray-700 mt-10 cursor-pointer">Nog geen account? <span class="text-black">Registreer Nu</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection
