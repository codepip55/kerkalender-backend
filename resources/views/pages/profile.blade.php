@extends('main')

@section('content')
    <div class="h-screen relative">
        <img src="{{ asset('assets/img/profile_cover.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background">

        <div class="absolute right-0 top-0 w-1/3 mx-5 mt-[88px] mb-5 h-[calc(100%-88px-8px)] bg-white rounded-3xl">
            <div class="cc my-5 mt-[25%] text-center">
                <div class="text-3xl">Mijn Profiel</div>
                <div class="text-sm">Mijn Gegevens</div>
                <div class="mt-10">
                    <div class="text-left text-sm">
                        <div class="mb-4">Naam: {{ Auth::user()->name }}</div>
                        <div class="mb-4">Email: {{ Auth::user()->email }}</div>
                        <div class="mb-4">Geregistreerd op: {{ date('l. d F Y h:m', strtotime(Auth::user()->created_at)) }}</div>
                        <div class="mb-4 text-gray-700"><a href="/change-password">Wachtwoord wijzigen</a></div>
                    </div>
                    <div class="max-w-sm mx-auto mt-1">
                        <a href="/logout" type="submit" class="block w-full bg-black text-white py-3 mt-10 rounded-3xl">Uitloggen</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
