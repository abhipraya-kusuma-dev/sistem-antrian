@extends('layout.main')

@section('content')
<div>
  <ul>

    @foreach($jenjang as $j)
    <li>
      <a href="/antrian/jenjang/{{ $j }}">{{ $j }}</a>
    </li>
    @endforeach

  </ul>
</div>
@endsection
