@extends('main')

@section('content')
    <div class="h-screen relative">
        <img src="{{ asset('assets/img/verify_cover.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background">

        <div class="absolute right-0 top-0 w-1/3 mx-5 mt-[88px] mb-5 h-[calc(100%-88px-8px)] bg-white rounded-3xl">
            <div class="cc my-5 mt-[25%] text-center">
                <div class="text-3xl">Klik de link in je email!</div>


                <div class="text-sm absolute bottom-10 left-0 right-0">
                    <div class="text-gray-700 mt-10">
                        <form method="post" action="{{ route('verification.send') }}">
                            <button type="submit">Email kwijt? <span class="text-black">Verstuur opnieuw</span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
