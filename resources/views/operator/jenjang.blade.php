@extends('layout.main')

@section('content')
<div class="p-8">

  <!-- Lanjut Ke Bendahara Message -->
  @if (session('create-success'))
  <p>{{ session('create-success') }}</p>
  @endif

  @if(session('create-error'))
  <p>{{ session('create-error') }}</p>
  @endif
  <!-- Lanjut Ke Bendahara Message End -->

  <div class="note bg-blue-400 p-4 text-white">
    <h1 class="text-xl font-bold"><span class="text-yellow-300">NOTE:</span> Pilih Tanggal Pendaftaran (default tanggal hari ini)</h1>
  </div>

  <div class="flex justify-between items-center">
    <form class="mt-4">
      <input type="date" name="tanggal_pendaftaran" class="border-2 border-black px-4 py-1.5" value="{{ $tanggal_pendaftaran }}" />
      <button type="submit" class="bg-purple-700/80 text-white px-4 py-1.5 font-semibold">Pilih Tanggal</button>
    </form>
    <form action="/logout" method="post">
      @csrf
      <button type="submit" class="text-red-400 font-bold text-lg">Logout</button>
    </form>
  </div>

  <nav class="flex space-x-2 mt-8">
    <a href="/operator/antrian/jenjang/{{ $jenjang }}/belum?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'belum' ? 'text-blue-600 border-b-4 border-blue-600 font-bold' : 'text-blue-600/80' }}">
      Belum Terpanggil
    </a>
    <a href="/operator/antrian/jenjang/{{ $jenjang }}/sudah?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'sudah' ? 'text-blue-600 font-bold border-b-4 border-blue-600' : 'text-blue-600/80' }}">
      Terpanggil
    </a>
    <a href="/operator/antrian/jenjang/{{ $jenjang }}/lewati?tanggal_pendaftaran={{ $tanggal_pendaftaran }}" class="{{ $status === 'lewati' ? 'text-blue-600 font-bold' : 'text-blue-600/80' }}">
      Dilewati
    </a>
  </nav>

  <h2 class="text-2xl mt-2 font-bold">Antrian</h2>

  <ul id="antrian-list" class="text-green-600 border-2 border-black grid grid-cols-4 gap-4 text-xl p-4 mt-4">
    @if(count($semua_antrian))

    @foreach($semua_antrian as $antrian)
    <li class="w-full">
      <a class="bg-green-400 text-white py-1.5 inline-block text-center w-full" href="/operator/antrian/panggil/{{ $antrian->id }}">
        <b>{{ $antrian->nomor_antrian }}</b>
      </a>
    </li>
    @endforeach

    @else
    <li>Tidak ada data</li>
    @endif
  </ul>

  <div class="mt-2" id="next-page">
    {{ $semua_antrian->appends($_GET)->onEachSide(2)->links() }}
  </div>

</div>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
  const antrianListContainer = document.getElementById('antrian-list');
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

    const htmlList = updatedAntrianList.data.map((antrian) => {
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


      socket.on("new antrian created", function () {
  const updateCardContainer = async () => {
    try {
      const currentUrl = window.location.href; // exact page user is on

      const res = await fetch(currentUrl);
      const html = await res.text();

      // Create a dummy DOM to parse the returned HTML
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');

      // Find the updated antrian list from the fetched HTML
      const newAntrianList = doc.querySelector('#antrian-list');
      const nextPage = doc.querySelector('#next-page');

      // Replace the current list
      const antrianListContainer = document.getElementById('antrian-list');
      antrianListContainer.innerHTML = newAntrianList.innerHTML;
      const nextPageContainer = document.getElementById('next-page');
      nextPageContainer.innerHTML = nextPage.innerHTML;

    } catch (error) {
      console.error('Failed to update antrian list:', error);
    }
  };

  updateCardContainer();
});
</script>
@endsection
