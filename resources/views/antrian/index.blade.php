@extends('layout.main')

@section('content')
<header class="flex py-6 px-8 justify-between items-center">
  <div class="space-x-4 flex items-center">
    <img src="wk.png" class="w-20" alt="logo-image">
    <h1 class="text-[#1a5088] text-6xl font-bold">Antrian PPDB</h1>
  </div>
  <div id="hamburger">
    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-14 h-14 text-[#1a5088] cursor-pointer">
      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
    </svg>
  </div>
  {{-- item-hamburger --}}
  <div class="fixed rounded-md border-2 border-solid border-black space-y-8 px-10 font-bold text-lg hidden right-4 top-[10%] translate-y-[50%] bg-white flex-col p-6" id="menu">
    <a href="" class="flex space-x-4 items-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
      </svg><span>Laporan</span>
    </a>
    <a href="" class="flex space-x-4 items-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
      </svg><span>Logout</span>
    </a>
  </div>
</header>

{{-- Nomor antrian tiap jenjang --}}
<main class="mt-10 p-4">
  <div class="grid grid-cols-5 gap-4">
    @foreach ($jenjang as $j)
      <div style="background-color: {{$warna[$loop->index]}};" class=" flex flex-col rounded-md items-center justify-start pb-20 space-y-4 py-4 border-2 border-black border-solid">
        <h2 class="text-3xl font-bold uppercase">{{$j}}</h2>
        <p class="text-7xl uppercase">k01</p>
      </div>
    @endforeach
  </div>
  {{-- text berjalan --}}
  <div class="w-full p-10">
    <marquee class=" text-3xl font-bold">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quod, placeat provident modi enim eaque aut quia est adipisci rem error, a, optio accusamus nulla! Sunt adipisci delectus libero perspiciatis. Corrupti quos quibusdam consectetur obcaecati optio? Eius laboriosam, illum doloribus, ipsa adipisci autem assumenda veniam vitae saepe doloremque, sapiente nostrum ex.</marquee>
  </div>
</main>

<footer class="flex space-x-20 bg-[#1a5088] absolute inset-x-0 p-4 text-white font-semibold bottom-0">
  <a href="">Instagram</a>
  <a href="">Tiktok</a>
  <a href="">Webiste SWK</a>
</footer>
<script>
  const hamburger = document.getElementById('hamburger');
  const menu = document.getElementById('menu');

  hamburger.addEventListener('click', function(){
    if (menu.classList.contains('hidden')) {
      menu.classList.remove('hidden');
      menu.classList.add('flex');
    }
    else {
      menu.classList.remove('flex');
      menu.classList.add('hidden');
    }
  });
</script>
@endsection
