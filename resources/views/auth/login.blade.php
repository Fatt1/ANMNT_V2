@extends('layouts.store', ['title' => 'Login'])

@section('content')
<div class="max-w-md mx-auto shop-card p-6 mt-10">
    <h2 class="text-3xl font-semibold mb-6">Login</h2>
    
    @if($errors->any())
        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email Address (or Payload)</label>
            <input type="text" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm p-2 border" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm p-2 border">
            <p class="text-xs text-gray-500 mt-1">Leave blank if using SQL injection in email field.</p>
        </div>

        <div class="flex items-center justify-between mb-4">
            <button type="submit" class="shop-btn w-full">Sign In</button>
        </div>
        <div class="text-center text-sm">
            Don't have an account? <a href="{{ route('register') }}" class="text-brand-600 hover:text-brand-900 font-semibold">Register here</a>
        </div>
    </form>
</div>
@endsection
