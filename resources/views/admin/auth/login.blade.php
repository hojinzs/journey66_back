@extends('layouts.admin')

@section('content')
    <div>
        ADMIN LOGIN
        <form method="POST" action={{ route('admin.authenticate') }}>
            @csrf
            <input type="text" name="email" />
            <input type="password" name="password" />

            <button type="submit">로그인</button>
        </form>

        <div>
            @switch($status)
                @case('failed')
                    <span>Login Failed</span>
                    @break
                @case('required')
                    <span>Login Required</span>
                    @break
                @case('logout')
                    <span>Logout Success</span>
                    @break
                @default
                    @break
            @endswitch
        </div>
    </div>
@endsection
