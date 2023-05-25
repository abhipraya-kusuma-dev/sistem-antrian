@extends('layout.main')

@section('content')
<div class="p-4">

  <form action='/logout' method='post'>
    @csrf
    <button type="submit" class="text-red-600 font-bold hover:text-red-700">Logout</button>
  </form>

  <h1 class="text-2xl font-bold">Laporan</h1>

  <div class="flex justify-between mt-6">
    <form class="flex flex-col">
      <input type="date" name="tanggal_pendaftaran" value="{{ $tanggal_pendaftaran }}" class="outline-none border-2 border-black p-2" />
      <button type="submit" class="py-2 px-4 bg-sky-600 hover:bg-sky-700 text-white mt-2">Pilih Tanggal</button>
    </form>

    <div class="flex flex-col items-center">
      <div class="w-96 aspect-square">
        <canvas id="myChart"></canvas>
      </div>

      <div class="flex mt-10">
        <form action="/laporan/excel" method="post">
          @csrf
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
        'BENDAHARA'
      ],
      datasets: [{
        label: 'Jumlah Antrian',
        data: [data.sd, data.smp, data.sma, data.smk, data.bendahara],
        backgroundColor: [
          'rgb(255, 99, 132)',
          'rgb(54, 162, 235)',
          'rgb(255, 205, 86)',
          'rgb(200, 162, 235)',
          'rgb(210, 123, 65)',
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
