@extends('layout.main')

@section('content')
<div>

  <div>
    <h1 class="text-2xl font-bold">Pilih Tanggal Pendaftaran (default nya hari ini)</h1>
    <form class="mt-2">
      <input type="date" name="tanggal_pendaftaran" value="{{ $tanggal_pendaftaran }}" />
      <button type="submit">Pilih Tanggal</button>
    </form>
  </div>

  <nav class="flex space-x-2">
    <a href="/operator/antrian/jenjang/{{ $jenjang }}/belum?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'belum' ? 'text-blue-600 font-bold' : 'text-blue-600/80' }}">
      Belum Terpanggil
    </a>
    <a href="/operator/antrian/jenjang/{{ $jenjang }}/sudah?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'sudah' ? 'text-blue-600 font-bold' : 'text-blue-600/80' }}">
      Terpanggil
    </a>
    <a href="/operator/antrian/jenjang/{{ $jenjang }}/lewati?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'lewati' ? 'text-blue-600 font-bold' : 'text-blue-600/80' }}">
      Dilewati
    </a>
  </nav>

  <h2 class="text-lg mt-2 font-bold">Antrian</h2>

  <ul class="text-green-600">
    @if(count($semua_antrian))

    @foreach($semua_antrian as $antrian)
    <li>
      <a href="/operator/antrian/panggil/{{ $antrian->id }}">
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
