@extends('layouts.admin')

@section('content')
    <div>
        ADMIN LOGIN
        <form method="POST" action={{ route('admin.authenticate') }}>
            @csrf
            <div>
                <label for="email">email</label>
                <input type="text" name="email" placeholder="email"/>
            </div>
            <div>
                <label for="password">password</label>
                <input type="password" name="password" placeholder="password"/>
            </div>
            <div>
                <input type="checkbox" name="auto" />
                <label for="auto">Auto Login</label>
            </div>
            <div>
                <button type="submit">로그인</button>
            </div>
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
