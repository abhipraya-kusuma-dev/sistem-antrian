@extends('layout.main')

@section('content')
<div class="h-screen flex items-center justify-center">
  <div class="p-4 border-2 border-black">
    <h1 class="text-lg">Nomor Antrian anda saat ini adalah</h1>
    <p class="font-bold text-center">Nomor {{ $nomorAntrianSaatIni }}</p>

    <div class="flex mt-2">
      <a href="/antrian/daftar" class="active:bg-red-800 py-2 px-4 w-1/2 bg-red-500/80 text-center text-white font-bold">Nanti Aja Deh</a>
      <form class="w-1/2" action="/bendahara/daftar/proses" method="post">
        @csrf
        <input type="hidden" name="nomor_antrian" value="{{ $nomorAntrianSaatIni }}" />
        <button type="submit" class="active:bg-green-800 py-2 px-4 w-full bg-green-500/80 text-center text-white font-bold">Lanjutkan</button>
      </form>
    </div>
  </div>
</div>
@endsection
