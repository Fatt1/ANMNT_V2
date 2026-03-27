@extends('layouts.store', ['title' => 'Register'])

@section('content')
<div class="max-w-md mx-auto shop-card p-6 mt-10">
    <h2 class="text-3xl font-semibold mb-6">Register</h2>
    
    @if($errors->any())
        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm p-2 border" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm p-2 border" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="text" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm p-2 border" required>
            <p class="text-xs text-red-500 mt-1">Warning: Password will be stored in plain text.</p>
        </div>

        <div class="flex items-center justify-between mb-4">
            <button type="submit" class="shop-btn w-full">Sign Up</button>
        </div>
        <div class="text-center text-sm">
            Already have an account? <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-900 font-semibold">Login here</a>
        </div>
    </form>
</div>
@endsection
