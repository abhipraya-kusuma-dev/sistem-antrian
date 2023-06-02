@extends('layout.main')

@section('content')
<div>

  @if(session('create-error'))
  <p>{{ session('create-error') }}</p>
  @endif

  @if(session('create-success'))
  <p>{{ session('create-success') }}</p>
  @endif


  <header class="flex p-6 items-center space-x-4">
    <img src="{{asset('wk.png')}}" class="w-20" alt="logo">
    <p class="text-[#1A508B] font-bold text-5xl uppercase">Antrean PPDB</p>
  </header>

  <main class="flex flex-col items-center  mt-20">
    <h1 class="text-5xl font-bold">Daftar Antrean Jenjang </h1>
  <ul>

    @foreach($jenjang as $j)
    <li>
      <a href="/antrian/daftar/konfirmasi/{{ $j }}" class="rounded-lg" style="background-color: {{ $warna[$loop->index] }};">{{ $j }}</a>
    </li>
    @endforeach
    <li>
      <a href="/antrian/daftar/konfirmasi/bendahara" class="rounded-lg" style="background-color: {{ $warna[4] }};">Bendahara</a>
    </li>

  </ul>
  </main>

</div>
@endsection
