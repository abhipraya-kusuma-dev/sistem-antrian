@extends('layout.main')

@section('content')
@if(session('antrian-mentok'))
  <p>{{ session('antrian-mentok') }}</p>
  @endif
<div class="w-full p-8 flex justify-center space-x-10 h-screen items-center">

  <div class="flex flex-col space-y-8">
  <h1 class="text-xl font-bold uppercase">Panggil Peserta</h1>
  <div class="my-2 border-2 border-black py-8 px-8 space-y-4 w-[600px]">
    <p class="flex justify-between text-xl uppercase">Nomor Antrian <b>{{ $antrian->nomor_antrian}}</b></p>
    <p class="flex justify-between text-xl uppercase">Jenjang <b>{{ $antrian->jenjang}}</b></p>
    <p class="flex justify-between text-xl uppercase">Tanggal daftaran <b>{{ $antrian->tanggal_pendaftaran }}</b></p>
    <p class="flex justify-between text-xl uppercase">Status <b>{{ $antrian->terpanggil }}</b></p>
  </div>
  <a href="/operator/antrian/jenjang/{{ $antrian->jenjang }}/belum" class="bg-blue-600 text-white w-max rounded-full hover:underline py-2 px-4 font-semibold">Kembali Ke Menu Tadi</a>

  </div>

  @if($antrian->terpanggil === 'sudah')
  <button class="hidden" type="button" id="panggil-btn">Panggil</button>
  <form action="/operator/antrian/lanjut/" class="hidden" method="post">
    @csrf
    <input type="hidden" name="antrian_id" value="{{$antrian->id }}" />
    <button type="submit" id="lanjut-btn" class="disabled:text-black/60 text-green-600 font-bold">Antrian Selanjutnya</button>
  </form>
  <form action="/operator/antrian/lewati/" class="hidden" method="post">
    @csrf
    <input type="hidden" name="antrian_id" value="{{$antrian->id }}" />
    <button type="submit" id="lewati-btn" class="disabled:text-black/60 text-green-600 font-bold">Lewati Antrian</button>
  </form>

  <form action="/operator/antrian/terpanggil" class="hidden" method="post">
    @method('PUT')
    @csrf
    <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
    <input type="hidden" name="antrian_jenjang" value="{{ $antrian->jenjang }}" />
    <button type="submit" id="terpanggil-btn" onclick="return confirm('Yakin? gk bisa di un-panggil lho ini')" class="disabled:text-black/60 text-green-600 font-bold">Antrian Sudah Terpanggil</button>
  </form>
  @else
  <div class="flex flex-col -translate-y-8 space-y-4">

  <button type="button" id="panggil-btn" class="disabled:text-black/60 hover:border-b-2 hover:border-white w-[650px] text-white border-2 border-black font-bold py-3 px-4 bg-red-600 hover:bg-red-800 ">Panggil</button>

  <div class="flex space-x-4">
    <form action="/operator/antrian/lanjut/" class="block" method="post">
      @csrf
      <input type="hidden" name="antrian_id" value="{{$antrian->id }}" />
      <button type="submit" id="lanjut-btn" class="disabled:text-black/60 bg-green-600 text-white font-bold border-2 border-black rounded-full py-2 px-6">Antrian Selanjutnya</button>
    </form>

    <form action="/operator/antrian/lewati/" class="block" method="post">
      @csrf
      <input type="hidden" name="antrian_id" value="{{$antrian->id }}" />
      <button type="submit" id="lewati-btn" class="disabled:text-black/60 bg-green-600 text-white font-bold border-2 border-black rounded-full py-2 px-6">Lewati Antrian</button>
    </form>

    <form action="/operator/antrian/terpanggil" class="block" method="post">
      @method('PUT')
      @csrf
      <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
      <input type="hidden" name="antrian_jenjang" value="{{ $antrian->jenjang }}" />
      <button type="submit" id="terpanggil-btn" onclick="return confirm('Yakin? gk bisa di un-panggil lho ini')" class="disabled:text-black/60 bg-green-600 text-white font-bold border-2 border-black rounded-full py-2 px-6">Antrian Sudah Terpanggil</button>
    </form>
  </div>

  <div>

    <form action="/operator/antrian/lanjut/bendahara" method="post">
      @csrf
      <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
      <input type="hidden" name="nomor_antrian" value="{{ $antrian->nomor_antrian }}" />
      <input type="hidden" name="antrian_jenjang" value="{{ $antrian->jenjang }}" />
      <button type="submit" id="lanjut-bendahara-btn" class="disabled:text-black/60 bg-blue-600 text-white font-bold py-2 px-6 rounded-full border-2 border-black">Lanjut Ke Bendahara</button>
    </form>
    @endif

  </div>

  </div>

</div>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
  const panggilBtn = document.getElementById('panggil-btn')
  const lanjutBtn = document.getElementById('lanjut-btn')
  const lewatiBtn = document.getElementById('lewati-btn')
  const terpanggilBtn = document.getElementById('terpanggil-btn')
  const lanjutBendaharaBtn = document.getElementById('lanjut-bendahara-btn')

  const antrian = {{ Js::from($antrian) }}

  const socket = io(`{{ env('SOCKET_IO_SERVER') }}`)

  panggilBtn.addEventListener('click', () => {
    socket.emit('play current antrian audio', antrian)
  })

  lewatiBtn.addEventListener('click', () => {
    socket.emit('skip antrian', 'skip')
  })

  terpanggilBtn.addEventListener('click', () => {
    socket.emit('skip antrian', 'skip')
  })

  lanjutBendaharaBtn.addEventListener('click', () => {
    socket.emit('skip antrian', 'skip')
  })

  // socket.emit('change antrian display', antrian)

  socket.on('change antrian display loading', (antrian) => {
    panggilBtn.setAttribute('disabled', 'true')
    lanjutBtn.setAttribute('disabled', 'true')
    lewatiBtn.setAttribute('disabled', 'true')
    terpanggilBtn.setAttribute('disabled', 'true')
    lanjutBendaharaBtn.setAttribute('disabled', 'true')
  })

  socket.on('change antrian display complete', (antrian) => {
    panggilBtn.removeAttribute('disabled')
    lanjutBtn.removeAttribute('disabled')
    lewatiBtn.removeAttribute('disabled')
    terpanggilBtn.removeAttribute('disabled')
    lanjutBendaharaBtn.removeAttribute('disabled')
  })
</script>
@endsection
