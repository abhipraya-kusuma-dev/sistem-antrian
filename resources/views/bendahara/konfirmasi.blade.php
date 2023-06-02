@extends('layout.main')

@section('content')
<div>
  <h1>Konfirmasi Pendaftaran Antrian</h1>
  <p>
    <b>Nomor antrian anda saat ini adalah {{ $nomorAntrianSaatIni }}</b> di antrian bendahara
  </p>

  <form action="/bendahara/daftar/proses" method="post">
    @csrf
    <input type="hidden" value="{{ $nomorAntrianSaatIni }}" name="nomor_antrian" />
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
