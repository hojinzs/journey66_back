@extends('layouts.admin')

@section('content')
    <div>
        ADMIN LOGIN
        <form method="POST" action={{ route('api.authenticate') }}>
            @csrf
            <input type="text" name="email" />
            <input type="password" name="password" />

            <button type="submit">로그인</button>
        </form>
    </div>
@endsection
