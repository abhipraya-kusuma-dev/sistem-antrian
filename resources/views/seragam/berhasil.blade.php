@extends('layout.main')

@section('content')
<div class="karcis flex justify-center items-center h-screen">
  <div class="karcis-card border-b border-black py-4 px-10 flex flex-col items-center text-center" id="tytyd">
    <h1 class="text-xl font-bold text-black/80">ANTREAN PPDB</h1>
    <p class="text-xs">Sekolah Wijaya Kusuma <br>
      Jl. Bandengan Utara 80, <br> Penjaringan,
      Jakarta Utara, 14440</p>
    <div class="border-t-[5px] border-b-[5px] mt-2 border-black border-double border-spacing-10 w-full p-4">
      <h1 class="font-bold text-5xl text-black/80">{{ $antrian->nomor_antrian }}</h1>
      <P class="text-3xl font-bold text-black/70">LOKET <br> {{ strtoupper($antrian->jenjang) }}</P>
    </div>
    <p id="calender" class="mt-2 text-xs">Sabtu, 20 juni 2023 / 10:30</p>
    <p class="font-bold text-center mt-2 text-xs">"Karcis jangan sampai hilang ^_^"</p>
  </div>
</div>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script src="{{ asset('moment.js') }}"></script>
<script>
  const calender = document.getElementById('calender')

  function generateKalender(kalender) {
    const splitedKalender = kalender.split(' ');
    splitedKalender.splice(4, splitedKalender.length - 1);
    return splitedKalender.join(' ');
  }

  moment.locale('id');
  calender.textContent = generateKalender(moment().format('LLLL')) + ` {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }}`;

  window.print();

  const socket = io(`{{ env('SOCKET_IO_SERVER') }}`)

  socket.on('connect', () => {
    socket.emit('skip antrian', 'skip')
    socket.emit('new antrian created')
  })

  setTimeout(() => {
    // window.location.href = `{{ url('/bendahara/antrian/belum') }}`
    window.location.href = `{{ url('/antrian/daftar') }}`
  }, 5000)
</script>
@endsection
