@extends('layout.main')

@section('content')
<div>

  @if(session('create-error'))
  <p class="hidden">{{ session('create-error') }}</p>
  @endif

  @if(session('create-success'))
  <p class="hidden">{{ session('create-success') }}</p>
  @endif


  <header class="flex p-6 items-center space-x-4">
    <img src="{{asset('wk.png')}}" class="w-20" alt="logo">
    <p class="text-[#1A508B] font-bold text-5xl uppercase">Antrean PPDB</p>
  </header>

  <main class="flex flex-col items-center space-y-4 p-4 mt-20">
    <h1 class="text-5xl font-bold">Daftar Antrean Jenjang </h1>
    <div class="flex flex-col space-y-4 text-center w-full">
      @foreach($jenjang as $j)
    <a href="/antrian/daftar/konfirmasi/{{ $j }}" class="py-3 px-4 font-semibold text-white text-xl uppercase" style="background-color: {{ $warna[$loop->index] }};">
      {{ $j }}</a>
      <a href="/antrian/daftar/konfirmasi/antrian/bendahara" class="py-3 px-4 font-semibold text-white text-xl uppercase" style="background-color: {{ $warna }};"></a>
    @endforeach
    </div>
  </main>

</div>
@endsection
