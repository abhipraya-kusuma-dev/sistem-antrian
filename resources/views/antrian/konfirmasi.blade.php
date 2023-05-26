@extends('layout.main')

@section('content')
<div>
  <h1>Konfirmasi Pendaftaran Antrian</h1>
  <p>
    <b>Nomor antrian anda saat ini adalah {{ $nomorAntrianSaatIni }}</b> dengan jenjang {{ $jenjang }}
  </p>

  <form action="/antrian/daftar/proses" method="post">
    @csrf
    <input type="hidden" value="{{ $nomorAntrianSaatIni }}" name="nomor_antrian" />
    <input type="hidden" value="{{ $jenjang }}" name="jenjang" />
    <button type="submit" id="btn">Iya bang udah bener kok</button>
  </form>

  <a href="/antrian/daftar">Salah pencet bang, Balik lagi coba</a>
</div>
<script>
  const btn = document.getElementById('btn')
  btn.addEventListener('click', function() {
    btn.disabled = true
  })
</script>
@endsection
