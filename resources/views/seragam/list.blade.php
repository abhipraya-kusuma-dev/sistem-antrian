@extends('layout.main')

@section('content')
<div class="p-8">

  <div>
    <div class="note bg-blue-400 p-4 text-white">
      <h1 class="text-xl font-bold"><span class="text-yellow-300">NOTE:</span> Pilih Tanggal Pendaftaran (default tanggal hari ini)</h1>
    </div>

    <div class="flex justify-between items-center">
      <form class="mt-4">
        <input type="date" name="tanggal_pendaftaran" class="border-2 border-black px-4 py-1.5" value="{{ $tanggal_pendaftaran }}" />
        <button type="submit" class="bg-purple-700/80 text-white px-4 py-1.5 font-semibold">Pilih Tanggal</button>
      </form>
      {{-- Logout --}}
      <form action="/logout" method="post">
        @csrf
        <button type="submit" class="text-red-400 font-bold text-lg">Logout</button>
      </form>
    </div>
  </div>

  <nav class="flex space-x-2 mt-8">
    <a href="/seragam/antrian/belum?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'belum' ? 'text-blue-600 border-b-4 border-blue-600 font-bold' : 'text-blue-600/80' }}">
      Belum Terpanggil
    </a>
    <a href="/seragam/antrian/sudah?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'sudah' ? 'text-blue-600 font-bold border-b-4 border-blue-600' : 'text-blue-600/80' }}">
      Terpanggil
    </a>
    <a href="/seragam/antrian/lewati?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'lewati' ? 'text-blue-600 font-bold' : 'text-blue-600/80' }}">
      Dilewati
    </a>
  </nav>

  <h2 class="text-2xl mt-2 font-bold">Antrian</h2>

  <ul id="antrian-seragam-list" class="text-green-600 border-2 border-black grid grid-cols-4 gap-4 text-xl p-4 mt-4">
    @if(count($data))

    @foreach($data as $antrian)
    <li class="w-full">
      <a class="bg-green-600 text-white py-1.5 inline-block text-center w-full" href="/seragam/antrian/panggil/{{ $antrian->id }}">
        <b>{{ $antrian->nomor_antrian }}</b>
      </a>
    </li>
    @endforeach

    @else
    <li>Tidak ada data</li>
    @endif
  </ul>

</div>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
  const antrianSeragamContainerList = document.getElementById('antrian-seragam-list');
  const socket = io(`{{ env('SOCKET_IO_SERVER') }}`)

  function escapeHtml(string) {
    return String(string)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
  }

  const renderAntrianList = (updatedAntrianList) => {
    if(updatedAntrianList.length < 1) {
      return `<li>Tidak ada data</li>`;
    }

    const htmlList = updatedAntrianList.map((antrian) => {
      return `
          <li class="w-full">
            <a class="bg-green-400 text-white py-1.5 inline-block text-center w-full" href="/operator/antrian/panggil/${escapeHtml(antrian.id)}">
              <b>${escapeHtml(antrian.nomor_antrian)}</b>
            </a>
          </li>
      `
    });

    return htmlList.join('');
  }

  socket.on('new antrian created', function() {
    const baseUrl = `{{ asset('') }}`;
    
    const status = `{{ $status }}`;
    const tanggalPendaftaran = `{{ $tanggal_pendaftaran }}`;

    const query = `?tanggal_pendaftaran=${tanggalPendaftaran}&status=${status}`;

    fetch(baseUrl + 'api/antrian-seragam' + query)
      .then(res => res.json())
      .then(res => {
        const { semua_antrian } = res;

        antrianSeragamContainerList.innerHTML = renderAntrianList(semua_antrian);
      })
      .catch(err => {
        console.error(err); // Buat debuging aja
      })
  })
</script>
@endsection
