@extends('layout.main')

@section('content')
<div class="karcis flex justify-center items-center h-screen">
  <div class="karcis-card border-b border-black py-4 px-10 flex flex-col items-center text-center" id="tytyd">
    <h1 class="text-xl font-bold">ANTREAN PPDB</h1>
    <p>Sekolah wijaya kusuma <br>
      Jl. Bandengan utara 80, <br> Penjaringan,
      Jakarta Utara, 14440</p>
    <div class="border-t-[5px] border-b-[5px] mt-2 border-black border-double border-spacing-10 w-full p-4">
      <h1 class="font-bold text-5xl">{{ $antrian->nomor_antrian }}</h1>
      <P class="text-3xl font-bold">LOKET <br> {{ strtoupper($antrian->jenjang) }}</P>
    </div>
    <p id="calender" class="mt-2 text-xs">Sabtu, 20 juni 2023 / 10:30</p>
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
  calender.textContent = generateKalender(moment().format('LLLL'));

  window.print();

  const socket = io(`{{ env('SOCKET_IO_SERVER') }}`)
  socket.emit('skip antrian', 'skip')

  setTimeout(() => {
    window.location.href = `{{ url('/seragam/konfirmasi') }}`
  }, 5000)
</script>
@endsection
