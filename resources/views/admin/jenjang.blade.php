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
      <a href="/admin/antrian/panggil/{{ $antrian->id }}">
        <b>Nomor antrian {{ $antrian->nomor_antrian }}</b>
        Jenjang {{ $antrian->jenjang }}
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
        <b>Nomor antrian {{ $antrian->nomor_antrian }}</b>
        Jenjang {{ $antrian->jenjang }}
      </p>
    </li>
    @endforeach

    @else
    <li>Tidak ada data</li>
    @endif
  </ul>

</div>
@endsection
