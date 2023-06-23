@extends('layout.main')

@section('content')
<div class="p-4">


  <div class="flex justify-between mt-14">
    <div class=" flex space-y-4 flex-col justify-center w-full">
    <h1 class="text-2xl font-bold">Laporan</h1>
    <form class="flex w-1/2 flex-col border-2 space-y-4 border-black px-8 py-6">
      <label for="tanggal_pendaftaran_start" class="flex flex-col">Tanggal Mulai:
         <input id="tanggal_pendaftaran_start" type="date" name="tanggal_pendaftaran_start" value="{{ $tanggal_pendaftaran_start }}" class="outline-none border-2 border-black p-2" />
      </label>

      <label for="tanggal_pendaftaran_end" class="flex flex-col">Tanggal Akhir:
        <input id="tanggal_pendaftaran_end" type="date" name="tanggal_pendaftaran_end" value="{{ $tanggal_pendaftaran_end }}" class="outline-none border-2 border-black p-2" />
      </label>

      <button type="submit" class="py-2 px-4 bg-sky-600 font-semibold border-2 border-black hover:bg-sky-700 text-white mt-2">Pilih Tanggal</button>
    </form>
    <form action='/logout'class="border-2 border-black w-max" method='post'>
      @csrf
      <button type="submit" class="bg-red-600 py-1.5 px-8 text-white font-bold hover:text-red-700">Logout</button>
    </form>
    </div>
    <div class="flex flex-col items-center">
      <div class="w-96 aspect-square">
        <canvas id="myChart"></canvas>
      </div>

      <div class="flex space-x-4 font-semibold mt-10">
        <form action="/laporan/excel" method="post">
          @csrf
          <input type="hidden" name="tanggal_pendaftaran_start" value="{{ $tanggal_pendaftaran_start }}" class="outline-none border-2 border-black p-2" />
          <input type="hidden" name="tanggal_pendaftaran_end" value="{{ $tanggal_pendaftaran_end }}" class="outline-none border-2 border-black p-2" />
          <button type="submit" class="py-2 px-4 bg-green-600 hover:bg-green-700 text-white">Export Ke Excel</button>
        </form>
        <form action="/laporan/pdf" method="post">
          @csrf
          <button type="submit" class="py-2 px-4 bg-red-600 hover:bg-red-700 text-white">Export Ke Pdf</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('myChart')
    const data = {{ Js::from($data) }}

    const laporan = {
      labels: [
        'SD',
        'SMP',
        'SMA',
        'SMK',
        'BENDAHARA',
        'SERAGAM',
      ],
      datasets: [{
        label: 'Jumlah Antrian',
        data: [data.sd, data.smp, data.sma, data.smk, data.bendahara, data.seragam],
        backgroundColor: [
          'rgb(255, 99, 132)',
          'rgb(54, 162, 235)',
          'rgb(255, 205, 86)',
          'rgb(200, 162, 235)',
          'rgb(210, 123, 65)',
          'rgb(219, 73, 5)',
        ],
        hoverOffset: 4
      }],
    };

    new Chart(ctx, {
      type: 'pie',
      data: laporan
    })
  </script>
</div>
@endsection
