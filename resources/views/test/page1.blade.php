@extends('layout.main')

@section('content')
<h1>Page 1</h1>
<div>
  nomor antrian : <span id="span-antrian"></span>
</div>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
  const socket = io('127.0.0.1:3000')
  const spanAntrian = document.getElementById('span-antrian')

  socket.on('change antrian display', (nomorAntrian) => {
    spanAntrian.textContent = nomorAntrian
  })
</script>
@endsection
