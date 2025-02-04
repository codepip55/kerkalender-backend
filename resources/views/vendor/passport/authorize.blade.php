@extends('main')

@section('content')

<div class="h-screen relative">
    <img src="{{ asset('assets/img/login_cover.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background">

    <div class="absolute right-0 top-0 w-1/3 mx-5 mt-[88px] mb-5 h-[calc(100%-88px-8px)] bg-white rounded-3xl">
        <div class="cc my-5 mt-[25%] text-center">
            <div class="text-3xl">Autorisatieverzoek</div>
            <div class="text-[13px] text-gray-700">{{ $client->name }} wil toegang tot je account.</div>

            @if (count($scopes) > 0)
                <div class="mt-24">
                    <p>Deze applicatie zal in staat zijn om:</p>
                    <ul>
                        @foreach ($scopes as $scope)
                            <li class="text-sm">{{ $scope->description }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="mt-52 flex justify-center space-x-4">
                <form action="{{ route('passport.authorizations.approve') }}" method="POST" class="w-full max-w-xs">
                    @csrf
                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <div class="w-full">
                        <button type="submit" class="w-full bg-black text-white py-3 rounded-3xl">Accepteren</button>
                    </div>
                </form>

                <form action="{{ route('passport.authorizations.deny') }}" method="POST" class="w-full max-w-xs">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <div class="w-full">
                        <button type="submit" class="w-full bg-[#f3f3f3] text-black py-3 rounded-3xl">Weigeren</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
