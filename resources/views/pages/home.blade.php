@extends('main')

@section('content')
    <div class="h-screen relative">
        <img src="{{ asset('assets/img/home_cover.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background">

        <div class="absolute right-0 top-0 w-1/3 mx-5 mt-[88px] mb-5 h-[calc(100%-88px-8px)] bg-white rounded-3xl">
            <div class="cc my-5 mt-[25%] text-center">
                <div class="text-3xl">Kerkalender API</div>
                <div class="text-sm">Online</div>
                @auth()
                    <div class="max-w-sm mx-auto mt-1">
                        <a href="/profile" type="submit" class="block w-full bg-black text-white py-3 mt-10 rounded-3xl">Mijn Profiel</a>
                    </div>
                @else
                    <div class="max-w-sm mx-auto mt-1">
                        <a href="/login" type="submit" class="block w-full bg-black text-white py-3 mt-10 rounded-3xl">Aanmelden</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection
