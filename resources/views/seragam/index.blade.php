@extends('layout.main')

@section('content')
<main class="p-6 h-screen flex items-center justify-center">
  <!-- Display Antrian Seragam -->
  <audio hidden id="audio"></audio>

  <div>
    <h1 class="text-xl font-bold">Antrian</h1>

    @if(!is_null($seragam))
    <h2 class="text-2xl" id="nomor_antrian">{{ $seragam->nomor_antrian }}</h2>
    @else
    <h2 class="text-2xl" id="nomor_antrian">Kosong</h2>
    @endif

    <p>Loket <span class="text-lg mt-2 font-bold">Seragam</span></p>
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
