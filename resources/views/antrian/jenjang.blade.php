@extends('layout.main')

@section('content')
<div>

  <h1>Belum Dipanggil</h1>

  <ul>
    @if(count($antrianPerJenjang['belumTerpanggil']))

    @foreach($antrianPerJenjang['belumTerpanggil'] as $antrian)
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

  <h1>Sudah Dipanggil</h1>
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
