@extends('layout.main')

@section('content')
<main class="p-6 h-screen flex items-center justify-center">
  <!-- Display Antrian Seragam -->
  <div>
    <h1 class="text-xl font-bold">Antrian</h1>

    @if(!is_null($seragam))
    <h2 class="text-2xl">{{ $seragam->nomor_antrian }}</h2>
    @else
    <h2 class="text-2xl">Kosong</h2>
    @endif

    <p>Loket <span class="text-lg mt-2 font-bold">Seragam</span></p>
  </div>
</main>
@endsection
