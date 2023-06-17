@extends('layout.main')

@section('content')
<main>
  <!-- Display Antrian Seragam -->
  <div class=" space-x-4 p-8 flex items-center">
    <img src="{{ asset('wk.png') }}" class="w-[100px]">
    <div>
      <h1 class="font-bold text-3xl">Sekolah Wijaya Kusuma</h1>
      <p>Jl. Bandengan Utara 80, Penjaringan, Jakarta Utara, DKI Jakarta, NKRI Harga mati </p>
    </div>
  </div>
  <audio hidden id="audio"></audio>
  <div class="w-full flex space-x-4 px-4">
    <div class="border-2 border-black p-4 w-1/2 h-[calc(100vh-300px)]">
      <h1 class="text-xl font-bold">Antrian</h1>

      @if(!is_null($seragam))
      <h2 class="text-7xl" id="nomor_antrian">{{ $seragam->nomor_antrian }}</h2>
      @else
      <h2 class="text-2xl" id="nomor_antrian">Kosong</h2>
      @endif

      <p>Ruangan <span class="text-lg mt-2 font-bold">Seragam</span></p>
    </div>
    <div class="w-1/2 border-black border-2 p-4 flex flex-col justify-around space-y-8">
      <div class="border-2 border-black p-4 h-1/2 space-y-4">
        <h3 class="text-xl  underline font-bold">Terpanggil</h3>
        <ul class="grid grid-cols-4 gap-4">
          <li class="border-2 border-black p-2 bg-green-400 text-white font-bold text-center">M002</li>
          <li class="border-2 border-black p-2 bg-green-400 text-white font-bold text-center">M002</li>
          <li class="border-2 border-black p-2 bg-green-400 text-white font-bold text-center">M002</li>
          <li class="border-2 border-black p-2 bg-green-400 text-white font-bold text-center">M002</li>
          <li class="border-2 border-black p-2 bg-green-400 text-white font-bold text-center">M002</li>
        </ul>
      </div>
      <div class="border-2 border-black p-4 h-1/2 space-y-4">
        <h3 class="text-xl  underline font-bold">INFO GANNNNNN!!!!</h3>
        <p>Kegiatan MPLS (Masa Pengenalan Lingkungan Sekolah).Akan dimulai pada tanggal 10 s/d 12 juli 2023. </p>
        <p>Informasi lebih lanjut tentang kegiatan tersebut, silahkan hubungi:</p>
        <ul class="list-disc pl-4">
          <li>Pak Yurike(SMP): 0821 2269 0561 </li>
          <li>Bu Supartingsih(SMA): 0812 8096 5088</li>
          <li>Pak Syaiful Anwar (SMK): 0821 6984 5583</li>
        </ul>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
  const socket = io(`{{ env('SOCKET_IO_SERVER') }}`)

  const nomorAntrian = document.getElementById('nomor_antrian')
  const audio = document.getElementById('audio')

  function generateAntrianDisplay(antrian) {
    nomorAntrian.textContent = antrian.nomor_antrian
    audio.src = antrian.audio_path
  }

  socket.on('change antrian seragam display', (antrianDisplay) => {
    generateAntrianDisplay(antrianDisplay)
  })

  socket.on('play current antrian seragam audio', (antrianDisplay) => {
    console.log('hello audio')
    generateAntrianDisplay(antrianDisplay)
    socket.emit('change antrian seragam display loading', antrianDisplay)

    audio.play()

    const listener = audio.addEventListener('ended', () => {
      socket.emit('change antrian seragam display complete', antrianDisplay)
    })

    audio.removeEventListener('ended', listener)
  })
</script>
@endsection
