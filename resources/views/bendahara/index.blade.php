@extends('layout.main')

@section('content')
<div>

  @if(session('update-error'))
  <p>{{ session('update-error') }}</p>
  @endif

  @if(session('update-success'))
  <p>{{ session('update-success') }}</p>
  @endif

  <div>
    <h1 class="text-2xl font-bold">Pilih Tanggal Pendaftaran (default nya hari ini)</h1>
    <form class="mt-2">
      <input type="date" name="tanggal_pendaftaran" value="{{ $tanggal_pendaftaran }}"/>
      <button type="submit">Pilih Tanggal</button>
    </form>
  </div>

  <nav class="flex space-x-2">
    <a href="/bendahara/antrian/belum?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'belum' ? 'text-blue-600 font-bold' : 'text-blue-600/80' }}">
      Belum Terpanggil
    </a>
    <a href="/bendahara/antrian/sudah?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'sudah' ? 'text-blue-600 font-bold' : 'text-blue-600/80' }}">
      Terpanggil
    </a>
    <a href="/bendahara/antrian/lewati?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'lewati' ? 'text-blue-600 font-bold' : 'text-blue-600/80' }}">
      Dilewati
    </a>
  </nav>

  <h2 class="text-lg mt-2 font-bold">Antrian</h2>

  <ul class="text-green-600">
    @if(count($semua_antrian))

    @foreach($semua_antrian as $antrian)
    <li>
      <a href="/bendahara/antrian/panggil/{{ $antrian->id }}">
        <b>{{ $antrian->nomor_antrian }}</b>
      </a>
    </li>
    @endforeach

    @else
    <li>Tidak ada data</li>
    @endif
  </ul>

  <div class="mt-2">
    {{ $semua_antrian->appends($_GET)->onEachSide(2)->links() }}
  </div>

</div>
@endsection
