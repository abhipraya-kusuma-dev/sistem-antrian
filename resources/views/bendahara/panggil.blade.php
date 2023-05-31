@extends('layout.main')

@section('content')
<div>

  @if(session('antrian-mentok'))
  <p>{{ session('antrian-mentok') }}</p>
  @endif

  <a href="/bendahara/antrian/belum" class="text-blue-600 hover:underline">Kembali Ke Menu Tadi</a>

  <h1 class="text-lg font-bold">Panggil Peserta Bendahara</h1>
  <div class="my-2">
    <p>Nomor Antrian <b>{{ $bendahara->nomor_antrian}}</b></p>
    <p>Tanggal Pendaftaran <b>{{ $bendahara->tanggal_pendaftaran }}</b></p>
    <p>Status <b>{{ $bendahara->terpanggil }}</b></p>
  </div>

  <audio src="{{ asset($bendahara->audio_path) }}" hidden id="audio"></audio>

  @if($bendahara->terpanggil === 'sudah')
  <button class="disabled:text-black/60" type="button" id="panggil-btn" disabled>Panggil</button>
  @else
  <button type="button" id="panggil-btn" class="disabled:text-black/60 text-red-600 font-bold">Panggil</button>
  @endif

  <form action="/bendahara/antrian/lanjut/" method="post">
    @csrf
    <input type="hidden" name="bendahara_id" value="{{ $bendahara->id }}" />
    <button type="submit" class="text-green-600 font-bold">Antrian Selanjutnya</button>
  </form>

  <form action="/bendahara/antrian/lewati/" method="post">
    @csrf
    <input type="hidden" name="bendahara_id" value="{{ $bendahara->id }}" />
    <button type="submit" class="text-green-600 font-bold">Lewati Antrian</button>
  </form>

  <form action="/bendahara/antrian/terpanggil" method="post">
    @method('PUT')
    @csrf
    <input type="hidden" name="bendahara_id" value="{{ $bendahara->id }}" />
    <button type="submit" onclick="return confirm('Yakin? gk bisa di un-panggil lho ini')" class="text-green-600 font-bold">Antrian Sudah Terpanggil</button>
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
