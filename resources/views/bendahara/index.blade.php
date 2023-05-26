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
    <h1>Pilih Tanggal Pendaftaran (default nya hari ini)</h1>
    <form>
      <input type="date" name="tanggal_pendaftaran" value="{{ $tanggal_pendaftaran }}"/>
      <button type="submit">Pilih Tanggal</button>
    </form>
  </div>

  <h2>Belum Dipanggil</h2>

  <ul>
    @if(count($antrianBendahara['belumTerpanggil']))

    @foreach($antrianBendahara['belumTerpanggil'] as $antrian)
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

  <h2>Sudah Dipanggil</h2>
  <ul>
    @if(count($antrianBendahara['terpanggil']))

    @foreach($antrianBendahara['terpanggil'] as $antrian)
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
    @if(count($antrianBendahara['terlewati']))

    @foreach($antrianBendahara['terlewati'] as $antrian)
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

</div>
@endsection
