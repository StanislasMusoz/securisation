@extends('layouts.app')

@section('content')
    <h1>Login</h1>
    <form action="{{ route('login2') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
@endsection
