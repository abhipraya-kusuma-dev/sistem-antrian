@extends('layout.main')

@section('content')
<main>
  <!-- Display Antrian Seragam -->
  <div class=" space-x-4 p-8 flex items-center">
    <img src="{{ asset('wk.png') }}" class="w-[100px]">
    <div>
      <h1 class="font-bold text-3xl">Sekolah Wijaya Kusuma</h1>
      <p>Jl. Bandengan Utara 80, Penjaringan, Jakarta Utara, DKI Jakarta</p>
    </div>
  </div>

  <audio hidden id="intro" src="{{ asset('/audio/intro.mp3') }}"></audio>
  <audio hidden id="audio"></audio>
  <audio hidden id="outro" src="{{ asset('/audio/outro.mp3') }}"></audio>

  <div class="w-full flex space-x-4 px-4">
    <div class="border-2 border-black bg-[#9376E0] text-white p-4 w-1/2 h-[calc(100vh-230px)] flex flex-col justify-center items-center ">
      <h1 class="text-5xl font-black">Antrian</h1>

      @if(!is_null($seragam))
      <h2 class="text-7xl font-bold text-[#F6FFA6]" id="nomor_antrian">{{ $seragam->nomor_antrian }}</h2>
      @else
      <h2 class="text-9xl font-bold" id="nomor_antrian">Kosong</h2>
      @endif

      <p class="text-5xl font-bold mt-4">Ruangan <span class="t-2 font-bold text-white">Ukur Seragam</span></p>
    </div>
    <div class="w-1/2 border-black border-2 p-4 flex flex-col space-y-4 bg-[#FFB84C]">
      <div class="border-2 border-black p-4 h-1/2 space-y-4 bg-white">
        <h3 class="text-xl underline font-bold">Terpanggil</h3>
        <ul id="list-terpanggil" class="grid grid-cols-4 gap-4 text-lg">
          @if(count($terpanggil))
          @foreach($terpanggil as $antrian_terpanggil)
          <li class="border-2 border-black p-2 bg-green-400 text-black font-bold text-center">{{ $antrian_terpanggil->nomor_antrian }}</li>
          @endforeach
          @else
          <li class="border-2 border-black p-2 bg-green-400 text-black font-bold text-center">Kosong</li>
          @endif
        </ul>
      </div>
      <div class="border-2 font-semibold rounded-lg border-black py-2 bg-white text-slate-700 text-black px-4 h-1/2 text-lg">
        <h3 class="text-lg  underline font-bold text-[#B70404]">INFO:</h3>
        <p>Kegiatan MPLS (Masa Pengenalan Lingkungan Sekolah). Akan dimulai pada tanggal 12 s/d 14 juli 2023. </p>
        <p>Informasi lebih lanjut tentang kegiatan tersebut, silahkan hubungi:</p>
        <ul class="list-disc px-4 py-2 ">
          <li>Pak Yurike (SMP): 0821 2269 0561 </li>
          <li>Bu Supartingsih (SMA): 0812 8096 5088</li>
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

  const intro = document.getElementById('intro')
  intro.volume = 0.4

  const audio = document.getElementById('audio')

  const outro = document.getElementById('outro')
  outro.volume = 0.4

  function generateAntrianDisplay(antrian) {
    nomorAntrian.textContent = antrian.nomor_antrian
    audio.src = antrian.audio_path
  }

  socket.on('change antrian seragam display', (antrianDisplay) => {
    generateAntrianDisplay(antrianDisplay)
  })

  socket.on('play current antrian seragam audio', (antrianDisplay) => {
    generateAntrianDisplay(antrianDisplay)
    socket.emit('change antrian seragam display loading', antrianDisplay)

    intro.play()

    const introListener = intro.addEventListener('ended', () => {
      audio.play()
    })

    const audioListener = audio.addEventListener('ended', () => {
      outro.play()
    })

    const outroListener = outro.addEventListener('ended', () => {
      socket.emit('change antrian display complete', antrianDisplay)
    })

    intro.removeEventListener('ended', introListener)
    audio.removeEventListener('ended', audioListener)
    outro.removeEventListener('ended', outroListener)
  })

  const listTerpanggil = document.getElementById('list-terpanggil')

  const updateListTerpanggil = async () => {
    const req = await fetch(`{{ route('list_terpanggil') }}`)
    const res = await req.json()

    listTerpanggil.innerHTML = ''

    if (res.length) {
      res.forEach((antrian, idx) => {
        listTerpanggil.innerHTML += `
        <li class="border-2 border-black p-2 bg-green-400 text-white font-bold text-center">${antrian.nomor_antrian}</li>
      `
      })
    }

  }

  socket.on('skip antrian', async (skip) => {
    await updateListTerpanggil()
  })
</script>
@endsection
