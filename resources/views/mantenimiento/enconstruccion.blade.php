@extends('layouts.app')
@section('title', 'En construccion')
@section('contend')
<div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-r from-yellow-400 to-orange-500 text-black text-center p-4">
    <div class="flex flex-col items-center">
        <svg class="w-32 h-32 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 12l1.5 1.5m-1.5-1.5L4 10.5m0 1.5h2m8-1.5l1.5 1.5m-1.5-1.5L14 10.5m0 1.5h2M12 4v16m-3 0h6m-6-4h6m-6-4h6m-6-4h6M3 3l18 18">
            </path>
        </svg>
        <h1 class="text-5xl font-extrabold mt-4">¡Estamos trabajando!</h1>
        <p class="text-lg mt-2 max-w-xl">
            Nuestra página está en construcción. Pronto estará lista con novedades para ti.
        </p>
        <div class="mt-6 flex space-x-4">
            <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 10l4.553-4.553a2.121 2.121 0 00-3-3L12 7l-4.553-4.553a2.121 2.121 0 00-3 3L9 10m6 4l4.553 4.553a2.121 2.121 0 01-3 3L12 17l-4.553 4.553a2.121 2.121 0 01-3-3L9 14">
                    </path>
                </svg>
            </div>
            <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.428 15.341A8 8 0 114.573 8.66M13 10H3m10 4H3m8-8H3m8 12H3">
                    </path>
                </svg>
            </div>
        </div>
    </div>
</div>
@endsection
