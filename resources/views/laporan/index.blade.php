@extends('layout.main')

@section('content')
<div>

  <form action='/logout' method='post'>
    @csrf
    <button type="submit">Logout</button>
  </form>

  <h1>Laporan</h1>
  <form>
    <input type="date" name="tanggal_pendaftaran" value="{{ $tanggal_pendaftaran }}" />
    <button>Pilih Tanggal</button>
  </form>

  <div style="width: 300px; height: 300px; margin-top: 20px;">
    <canvas id="myChart"></canvas>
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
