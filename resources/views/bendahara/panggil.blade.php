@extends('layout.main')

@section('content')
<div>

  @if(session('antrian-mentok'))
  <p>{{ session('antrian-mentok') }}</p>
  @endif

  <h1>Panggil Peserta Bendahara</h1>
  <p>Nomor Antrian <b>{{ $bendahara->nomor_antrian}}</b></p>
  <p>Tanggal Pendaftaran <b>{{ $bendahara->tanggal_pendaftaran }}</b></p>
  <p>Status <b>{{ $antrian->terpanggil }}</b></p>

  <audio src="{{ asset($bendahara->audio_path) }}" hidden id="audio"></audio>
  <button type="button" id="panggil-btn" disabled="{{ $antrian->terpanggil === 'sudah' }}">Panggil</button>

  <form action="/bendahara/antrian/lanjut/" method="post">
    @csrf
    <input type="hidden" name="bendahara_id" value="{{ $bendahara->id }}" />
    <button type="submit">Lanjut Antrian</button>
  </form>

  <form action="/bendahara/antrian/lewati/" method="post">
    @csrf
    <input type="hidden" name="bendahara_id" value="{{ $bendahara->id }}" />
    <button type="submit">Lewati Antrian</button>
  </form>

  <form action="/bendahara/antrian/terpanggil" method="post">
    @method('PUT')
    @csrf
    <input type="hidden" name="bendahara_id" value="{{ $bendahara->id }}" />
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
