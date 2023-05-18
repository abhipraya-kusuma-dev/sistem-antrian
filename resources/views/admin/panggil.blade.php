@extends('layout.main')

@section('content')
<div>

  <h1>Panggil Peserta</h1>
  <p>Nomor Antrian <b>{{ $antrian->nomor_antrian}}</b></p>
  <p>Jenjang <b>{{ $antrian->jenjang}}</b></p>
  <p>Tanggal Pendaftaran <b>{{ $antrian->tanggal_pendaftaran }}</b></p>

  <audio src="{{ asset($antrian->audio_path) }}" hidden id="audio"></audio>
  <button type="button" id="panggil-btn">Panggil</button>

  <form action="/admin/antrian/terpanggil" method="post">
    @method('PUT')
    @csrf
    <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
    <input type="hidden" name="antrian_jenjang" value="{{ $antrian->jenjang }}" />
    <button type="submit" onclick="return confirm('Yakin? gk bisa di un-panggil lho ini')">Sudah Terpanggil</button>
  </form>

</div>
<script>
  const panggilBtn = document.getElementById('panggil-btn')
  const audio = document.getElementById('audio')

  panggilBtn.addEventListener('click', () => {
    audio.play()
  })
</script>
@endsection
