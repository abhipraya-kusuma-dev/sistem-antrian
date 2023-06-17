@extends('layout.main')

@section('content')
<div class="p-8">

  <div>
    <div class="note bg-blue-400 p-4 text-white">
      <h1 class="text-xl font-bold"><span class="text-yellow-300">NOTE:</span> Pilih Tanggal Pendaftaran (default tanggal hari ini)</h1>
    </div>

    <div class="flex justify-between items-center">
      <form class="mt-4">
        <input type="date" name="tanggal_pendaftaran" class="border-2 border-black px-4 py-1.5" value="{{ $tanggal_pendaftaran }}" />
        <button type="submit" class="bg-purple-700/80 text-white px-4 py-1.5 font-semibold">Pilih Tanggal</button>
      </form>
      {{-- Logout --}}
      <form action="/logout" method="post">
        @csrf
        <button type="submit" class="text-red-400 font-bold text-lg">Logout</button>
      </form>
    </div>
  </div>

  <nav class="flex space-x-2 mt-8">
    <a href="/seragam/antrian/belum?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'belum' ? 'text-blue-600 border-b-4 border-blue-600 font-bold' : 'text-blue-600/80' }}">
      Belum Terpanggil
    </a>
    <a href="/seragam/antrian/sudah?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'sudah' ? 'text-blue-600 font-bold border-b-4 border-blue-600' : 'text-blue-600/80' }}">
      Terpanggil
    </a>
    <a href="/seragam/antrian/lewati?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'lewati' ? 'text-blue-600 font-bold' : 'text-blue-600/80' }}">
      Dilewati
    </a>
  </nav>

  <h2 class="text-2xl mt-2 font-bold">Antrian</h2>

  <ul class="text-green-600 border-2 border-black grid grid-cols-4 gap-4 text-xl p-4 mt-4">
    @if(count($data))

    @foreach($data as $antrian)
    <li class="w-full">
      <a class="bg-green-600 text-white py-1.5 inline-block text-center w-full" href="/seragam/antrian/panggil/{{ $antrian->id }}">
        <b>{{ $antrian->nomor_antrian }}</b>
      </a>
    </li>
    @endforeach

    @else
    <li>Tidak ada data</li>
    @endif
  </ul>

</div>
@endsection
