@extends('layout.main')

@section('content')
<div>

  @if(session('antrian-mentok'))
  <p>{{ session('antrian-mentok') }}</p>
  @endif

  <h1>Panggil Peserta</h1>
  <p>Nomor Antrian <b>{{ $antrian->nomor_antrian}}</b></p>
  <p>Jenjang <b>{{ $antrian->jenjang}}</b></p>
  <p>Tanggal Pendaftaran <b>{{ $antrian->tanggal_pendaftaran }}</b></p>
  <p>Status <b>{{ $antrian->terpanggil }}</b></p>

  <audio src="{{ asset($antrian->audio_path) }}" hidden id="audio"></audio>
  @if($antrian->terpanggil === 'sudah')
  <button type="button" id="panggil-btn" disabled>Panggil</button>
  @else
  <button type="button" id="panggil-btn">Panggil</button>
  @endif

  <form action="/operator/antrian/lanjut/" method="post">
    @csrf
    <input type="hidden" name="antrian_id" value="{{$antrian->id }}" />
    <button type="submit">Lanjut Antrian</button>
  </form>

  <form action="/operator/antrian/lewati/" method="post">
    @csrf
    <input type="hidden" name="antrian_id" value="{{$antrian->id }}" />
    <button type="submit">Lewati Antrian</button>
  </form>

  <form action="/operator/antrian/terpanggil" method="post">
    @method('PUT')
    @csrf
    <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
    <input type="hidden" name="antrian_jenjang" value="{{ $antrian->jenjang }}" />
    <button type="submit" onclick="return confirm('Yakin? gk bisa di un-panggil lho ini')">Sudah Terpanggil</button>
  </form>

  <form action="/operator/antrian/lanjut/bendahara" method="post">
    @csrf
    <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
    <input type="hidden" name="antrian_jenjang" value="{{ $antrian->jenjang }}" />
    <button type="submit">Lanjut Ke Bendahara</button>
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
