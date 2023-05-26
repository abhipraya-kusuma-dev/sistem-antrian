@extends('layout.main')

@section('content')
<h1>Page 2</h1>
<div class="flex flex-col">
  @foreach($antrians as $antrian)
  <button class="btn" data-id="{{ $antrian->nomor_antrian }}">{{ $antrian->id }} - {{ $antrian->jenjang }}</button>
  @endforeach
</div>
<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
  const socket = io('127.0.0.1:3000')
  const btn = document.getElementsByClassName('btn')

  Array.from(btn).forEach(el => {
    el.addEventListener('click', function(e) {
      e.preventDefault()
      socket.emit('change antrian display', e.target.dataset.id)
    })
  })

</script>
@endsection
