@extends('layout.main')

@section('content')
<header class="flex p-3 justify-between shadow-md items-center">

  <div class="flex items-center space-x-4">
    <img src="wk.png" class="w-20" alt="wk.png">
    <div>
      <h1 class="text-3xl font-semibold text-[#1A508B]">ANTREAN SWK</h1>
      <p>Jalan Bandengan Utara 80 14440 Jakarta Daerah Khusus Ibukota Jakarta</p>
    </div>
  </div>

  {{-- Jam --}}
  <div class="flex flex-col items-center">
    <h1 class="text-4xl font-bold">{{$waktu}}</h1>
    <p>{{$tanggal}}</p>
  </div>

</header>


<main class="p-6">

  <div class="flex justify-around">
    <div class="flex w-1/2 ">
      <div class="flex flex-col w-full text-center space-y-6 text-white">
        <div class="bg-blue-400 w-full p-2 rounded-md">
          <h1 class="text-xl font-semibold">Antrian</h1>
        </div>
        <div class="bg-blue-400 p-6 rounded-md">
          <p class="text-9xl font-bold">005</p>
        </div>
        <div class="bg-blue-400 p-2 rounded-md">
          <p class="text-xl uppercase font-semibold">Loket 1</p>
        </div>
      </div>
    </div>

    {{-- Video --}}
    <div class="">
      <iframe width="560" height="315" src="https://www.youtube.com/embed/EZX96uHXCeE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
  </div>

  <div class="grid grid-cols-5 gap-4 mt-4">
    @foreach ($loket as $l)
    <div style="background-color: {{$warna[$loop->index]}};" class=" rounded-md text-center  space-y-8 py-4 text-white">
      <p class="text-xl font-semibold">Antrian</p>
      <p class="text-5xl font-bold">003</p>
      <hr>
      <p class="-translate-y-6 font-semibold uppercase">{{$l}}</p>
    </div>
    @endforeach
  </div>

</main><hr class="border-2 border-black">

<footer class="flex justify-between p-4 items-center">
  <p>Copy Right Wijayakusuma 2023</p>
  <div class="grid grid-cols-2 gap-4">
    <a href="https://instagram.com/sekolah_wijayakusuma?igshid=MmJiY2I4NDBkZg==" target="_blank">Instagram</a>
    <a href="https://www.tiktok.com/@sekolah_wijayakusuma?_t=8cbLfbj2UJL&_r=1" target="_blank">Tiktok</a>
    <a href="https://sekolahwijayakusuma.sch.id/" target="_blank">Website</a>
    <a href="https://youtube.com/@sekolahwijayakusumajakut" target="_blank">Youtube</a>
  </div>
</footer>

@endsection
