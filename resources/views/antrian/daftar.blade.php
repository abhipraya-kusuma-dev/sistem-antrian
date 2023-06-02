@extends('layout.main')

@section('content')
<div>

  @if(session('create-error'))
  <p class="hidden">{{ session('create-error') }}</p>
  @endif

  @if(session('create-success'))
  <p class="hidden">{{ session('create-success') }}</p>
  @endif


  <header class="flex p-6 items-center space-x-4 flex-col space-y-4">
    <img src="{{asset('wk.png')}}" class="w-20" alt="logo">
    <p class="text-[#1A508B] font-bold text-3xl uppercase">Antrean PPDB</p>
  </header>

  <main class="flex flex-col items-center space-y-10 p-4 ">

    <h1 class="text-5xl font-bold drop-shadow-lg">Pilih Jenjang Antrean</h1>

    <div class="flex flex-col space-y-8 text-center w-full text-4xl text-stroke text-stroke-white">
      @foreach($jenjang as $j)
      <a href="/antrian/daftar/konfirmasi/{{ $j }}" class="items-center flex justify-between py-3 px-10 font-semibold text-white uppercase rounded-full border-2 shadow-xl shadow-slate-400 border-black " style="background-color: {{ $warna[$loop->index] }};">
        <span>{{ $j }}</span>
        <span class="text-sm text-stroke-0 text-black">Jml Antre = 20</span>
      </a>
      <form></form>
      @endforeach
      <a href="/antrian/daftar/konfirmasi/bendahara" class="items-center flex justify-between py-3 px-10 font-semibold text-white uppercase rounded-full border-2 shadow-xl shadow-slate-400 border-black " style="background-color: {{ $warna[4] }};"><span>Bendahara</span><span class="text-sm text-stroke-0 text-black">Jml Antre = 20</span></a>
    </div>

  </main>

</div>
@endsection
