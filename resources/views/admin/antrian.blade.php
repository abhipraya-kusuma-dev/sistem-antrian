@extends('layout.main')

@section('content')
<div>
  @if(session('update-error'))
  <p>{{ session('update-error') }}</p>
  @endif

  @if(session('update-success'))
  <p>{{ session('update-success') }}</p>
  @endif

  <h1>Pilih Jenjang</h1>
  <ul>

    @foreach($jenjang as $j)
    <li>
      <a href="/admin/antrian/jenjang/{{ $j }}">{{ $j }}</a>
    </li>
    @endforeach

  </ul>
</div>
@endsection
