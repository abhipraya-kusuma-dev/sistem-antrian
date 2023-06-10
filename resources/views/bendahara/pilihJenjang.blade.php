@extends('layout.main')

@section('content')
<div>

  @foreach($jenjang as $j)
  <a href="/bendahara/cek-karcis/{{ $j }}">{{ $j }}</a>
  @endforeach

</div>
@endsection
