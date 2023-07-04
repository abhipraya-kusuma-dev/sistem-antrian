@extends('layout.main')

@section('content')
<div id="daftar-container">

  @if(session('create-error'))
  <p id="message" class="fixed z-[10] rounded px-4 text-lg inset-x-4 top-4 bg-red-600 text-white font-bold py-2">{{ session('create-error') }}</p>
  @endif

  @if(session('create-success'))
  <p id="message" class="fixed z-[10] rounded px-4 text-lg inset-x-4 top-4 bg-green-600 text-white font-bold py-2">{{ session('create-success') }}</p>
  @endif

  <header class="flex p-6 items-center space-x-4 flex-col space-y-4">
    <img src="{{ asset('wk.png') }}" class="w-20" alt="logo">
    <p class="text-[#1A508B] font-bold text-3xl uppercase">Antrean PPDB</p>
  </header>

  <main class="flex flex-col items-center space-y-10  ">

    <h1 class="text-5xl font-bold drop-shadow-lg">Pilih Jenjang Antrean</h1>

    {{-- Menu tombol --}}
    <div class="grid grid-cols-5 gap-4 w-full px-4">
      @foreach($jenjang as $j)
      <a href="/antrian/daftar/konfirmasi/{{ $j }}" class="flex space-y-8 flex-col w-full justify-between rounded-md items-center p-8  text-white uppercase" style="background-color: {{ $warna[$loop->index] }};">
        <span class="text-4xl font-bold">{{ $j }}</span>
        <span class="text-xl text-stroke-0 font-bold"><span>Sisa antrean: </span>{{ count($antrian[$j]) }}</span>
      </a>
      @endforeach

    </div>
  </main>

</div>
<script>
  const daftarContainer = document.getElementById('daftar-container')
  if (daftarContainer.firstElementChild.id === 'message') {
    setTimeout(() => {
      daftarContainer.removeChild(daftarContainer.firstElementChild)
    }, 3000)
  }
</script>
@endsection
