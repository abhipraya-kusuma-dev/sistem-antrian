@extends('layout.main')

@section('content')
<div>

  @if(session('create-error'))
  <p>{{ session('create-error') }}</p>
  @endif

  @if(session('create-success'))
  <p>{{ session('create-success') }}</p>
  @endif

  <h1>Daftar Antrian Jenjang :</h1>
  <ul>

    @foreach($jenjang as $j)
    <li>
      <a href="/antrian/daftar/konfirmasi/{{ $j }}">{{ $j }}</a>
    </li>
    @endforeach

  </ul>

</div>
@endsection
