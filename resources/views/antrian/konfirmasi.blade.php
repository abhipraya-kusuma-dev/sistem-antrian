@extends('layout.main')

@section('content')
    <div class="h-screen flex items-center justify-center">
        <div class="space-y-6 p-4 border-2 border-black">
            <div>
                <p class="font-bold text-center text-4xl uppercase">Nomor {{ $nomorAntrianSaatIni }}</p>
                <p class="uppercase text-center"><span> {{ $tanggal }}</span></p>
            </div>
            <p class="uppercase font-semibold flex justify-between">Jenjang: <span> {{ $jenjang }}</span></p>
            <div class="flex mt-2 space-x-4">
                <a href="/antrian/daftar"
                    class="active:bg-red-800 py-2 px-4 w-1/2 bg-red-500/80 text-center text-white font-bold">Kembali</a>
                <form class="w-1/2" action="/antrian/daftar/proses" method="post">
                    @csrf
                    <input type="hidden" name="nomor_antrian" value="{{ $nomorAntrianSaatIni }}" />
                    <input type="hidden" name="jenjang" value="{{ $jenjang }}" />
                    <button type="submit" id="antrian-baru"
                        class="active:bg-green-800 py-2 px-4 w-full bg-green-500/80 text-center text-white font-bold">Lanjutkan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
<script src="{{ asset('moment.js') }}"></script>
<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
    const antrianBaru = document.getElementById("antrian-baru");
    const socket = io(`{{ env('SOCKET_IO_SERVER') }}`)

    antrianBaru.addEventListener('click', function() {
        socket.emit("new antrian created")
    })
</script>
