@extends('layout.main')

@section('content')
<div>
  @if(session('login-eror'))
  <p>{{session('login-eror')}}</p>
  @endif

  <form action="/login" method="post">
    @csrf

    <div>
      <label for="username">
        Username
        <input type="text" name="username" />
      </label>
    </div>

    <div>
      <label for="password">
        Password
        <input type="password" name="password" />
      </label>
    </div>

    <button type="submit">Login</button>

  </form>
</div>
@endsection
