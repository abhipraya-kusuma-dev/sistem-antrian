@extends('layout.main')

@section('content')
<div>

  <div>
    <h1>Pilih Tanggal Pendaftaran (default nya hari ini)</h1>
    <form>
      <input type="date" name="tanggal_pendaftaran" value="{{ $tanggal_pendaftaran }}"/>
      <button type="submit">Pilih Tanggal</button>
    </form>
  </div>

  <h2>Belum Dipanggil</h2>

  <ul>
    @if(count($antrianPerJenjang['belumTerpanggil']))

    @foreach($antrianPerJenjang['belumTerpanggil'] as $antrian)
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

  <h2>Sudah Dipanggil</h2>
  <ul>
    @if(count($antrianPerJenjang['terpanggil']))

    @foreach($antrianPerJenjang['terpanggil'] as $antrian)
    <li>
      <p>
        <b>{{ $antrian->nomor_antrian }}</b>
      </p>
    </li>
    @endforeach

    @else
    <li>Tidak ada data</li>
    @endif
  </ul>

  <h2>Di Lewati</h2>
  <ul>
    @if(count($antrianPerJenjang['terlewati']))

    @foreach($antrianPerJenjang['terlewati'] as $antrian)
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
