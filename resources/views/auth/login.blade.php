@extends('layout.main')

@section('content')
<header class="flex space-y-4 p-6 flex-col w-full fixed items-center">
  <img src="{{ asset('wk.png') }}" class="w-16 md:w-20" alt="logo-wk">
  <h1 class="text-2xl md:text-4xl  font-semibold uppercase text-[#1a5088] -translate-y-1">Antrean SWK</h1>
</header>
<div class="h-screen flex justify-center items-center ">
  <div class="border-2 border-solid border-black w-max py-2 mt-4 px-10 rounded-md shadow-lg">

    @if(session('login-eror'))
    <p>{{session('login-eror')}}</p>
    @endif

    <form action="/login" method="post" class="space-y-4 py-4">
      @csrf

      <h1 class="text-3xl text-center underline font-bold text-[#1a5088]">Login</h1>
      <div>
        <label for="username" class="flex flex-col">
          <span class="font-semibold">Username</span>

          @error('username')
          <p class="text-red-400 font-bold">{{$message}}</p>
          @enderror
          <input type="text" name="username" id="username" placeholder="Masukan Username..." class="mt-1 p-1.5 border-2 border-slate-500 rounded-md" />
        </label>
      </div>

      <div>
        <label for="password" class="flex flex-col">
          <span class="font-semibold">Password</span>

          @error('password')
          <p class="text-red-400 font-bold">{{$message}}</p>
          @enderror
          <input type="password" name="password" id="password" placeholder="Masukan Password..." class="mt-1 p-1.5 border-2 border-slate-500 rounded-md" />
        </label>
      </div>

      <button type="submit" class="py-2 w-full bg-green-600 text-lg font-semibold rounded-md text-white">Login</button>
    </form>
  </div>
</div>
@endsection
