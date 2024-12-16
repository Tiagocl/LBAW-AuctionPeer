@extends('layouts.app')

@section('content')
<section class="max-w-full mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg flex space-x-8">
    <!-- profile info -->
    <div class="flex-none w-48">
        <div class="bg-gray-300 w-32 h-32 rounded-full mx-auto mb-4"
            style="background-image: url('{{ asset("storage/" . $user->profile_picture) }}'); background-size: cover;">
        </div>
        <div class="text-center">
            <h2 class="text-lg font-semibold">{{ $user->username }}</h2>
            <div class="flex justify-center items-center mt-4">
                @php
                    $rating = round($user->ratingsReceived->avg('score') ?? 0);
                @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} text-lg">&#9733;</span>
                @endfor
            </div>
            <p class="text-sm text-gray-400 mt-2">Accession Date: {{ $user->created_at->format('d.m.Y') }}</p>
        </div>
    </div>
    @endauth
</section>
@endsection
