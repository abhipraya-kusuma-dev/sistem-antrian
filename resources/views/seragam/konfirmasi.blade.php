@extends('layout.main')

@section('content')

@if(session('create-error'))
<p id="message" class="fixed z-[10] rounded px-4 text-lg inset-x-4 top-4 bg-red-600 text-white font-bold py-2">{{ session('create-error') }}</p>
@endif

@if(session('create-success'))
<p id="message" class="fixed z-[10] rounded px-4 text-lg inset-x-4 top-4 bg-green-600 text-white font-bold py-2">{{ session('create-success') }}</p>
@endif

<div class="h-screen flex items-center justify-center">
  <div class="p-4 border-2 border-black">
    <h1 class="text-lg">Nomor Antrian anda saat ini adalah</h1>
    <p class="font-bold text-center text-4xl">Nomor {{ $nomorAntrianSaatIni }}</p>

    <div class="flex mt-2 space-x-4">
      <form class="w-1/2" action="/seragam/daftar/proses" method="post">
        @csrf
        <input type="hidden" name="nomor_antrian" value="{{ $nomorAntrianSaatIni }}" />
        <button type="submit" class="active:bg-green-800 py-2 px-4 w-full bg-green-500/80 text-center text-white font-bold">Lanjutkan</button>
      </form>
    </div>
  </div>
</div>
@endsection
