@extends('layout.main')

@section('content')
    @if (session('antrian-mentok'))
        <p class="m-6 py-4 px-6 text-white font-semibold bg-green-400 flex justify-between items-center" id="closeButton">
            {{ session('antrian-mentok') }}
            <span onclick="closeButtonClicked()" class="cursor-pointer text-2xl" id="closeButton">&times;</span>
        </p>
    @endif

    <div class="w-full p-8 flex justify-center space-x-10 h-screen items-center">

        <div class="flex flex-col space-y-8">
            <h1 class="text-xl font-bold uppercase">Panggil Peserta</h1>
            <div class="my-2 border-2 border-black py-8 px-8 space-y-4 w-[600px]">
                <p class="flex justify-between text-xl uppercase">Nomor Antrian <b>{{ $antrian->nomor_antrian }}</b></p>
                <p class="flex justify-between text-xl uppercase">Jenjang <b>{{ $antrian->jenjang }}</b></p>
                <p class="flex justify-between text-xl uppercase">Tanggal daftaran
                    <b>{{ $antrian->tanggal_pendaftaran }}</b></p>
                <p class="flex justify-between text-xl uppercase">Status <b>{{ $antrian->terpanggil }}</b></p>
            </div>
            <a href="/operator/antrian/jenjang/{{ $antrian->jenjang }}/belum"
                class="bg-blue-600 text-white w-max rounded-full hover:underline py-2 px-4 font-semibold">Kembali Ke Menu
                Tadi</a>

        </div>

        @if ($antrian->terpanggil === 'sudah')
            <button class="hidden" type="button" id="panggil-btn">Panggil</button>
            <form action="/operator/antrian/lanjut/" class="hidden" method="post">
                @csrf
                <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
                <button type="submit" id="lanjut-btn" class="disabled:text-black/60 text-green-600 font-bold">Antrian
                    Selanjutnya</button>
            </form>
            <form action="/operator/antrian/lewati/" class="hidden" method="post">
                @csrf
                <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
                <button type="submit" id="lewati-btn" class="disabled:text-black/60 text-green-600 font-bold">Lewati
                    Antrian</button>
            </form>

            <form action="/operator/antrian/terpanggil" class="hidden" method="post">
                @method('PUT')
                @csrf
                <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
                <input type="hidden" name="antrian_jenjang" value="{{ $antrian->jenjang }}" />
                <button type="submit" id="terpanggil-btn" onclick="return confirm('Yakin? gk bisa di un-panggil lho ini')"
                    class="disabled:text-black/60 text-green-600 font-bold">Antrian Sudah Terpanggil</button>
            </form>
        @else
            <div class="flex flex-col -translate-y-8 space-y-4">

                <button type="button" id="panggil-btn"
                    class="disabled:text-black/60 hover:border-b-2 hover:border-white w-[650px] text-white border-2 border-black font-bold py-3 px-4 bg-red-600 hover:bg-red-800 ">Panggil</button>

                <div class="flex space-x-4">
                    <!-- <form action="/operator/antrian/lanjut/" class="block" method="post"> -->
                    <!--   @csrf -->
                    <!--   <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" /> -->
                    <!--   <button type="submit" id="lanjut-btn" class="disabled:text-black/60 bg-green-600 text-white font-bold border-2 border-black rounded-full py-2 px-6">Antrian Selanjutnya</button> -->
                    <!-- </form> -->

                    @if (!in_array($antrian->terpanggil, ['lewati', 'sudah']))
                        <form action="/operator/antrian/lewati/" class="block" method="post">
                            @csrf
                            <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
                            <button type="submit" id="lewati-btn"
                                class="disabled:text-black/60 bg-green-600 text-white font-bold border-2 border-black rounded-full py-2 px-6">Lewati
                                Antrian</button>
                        </form>
                    @endif

                    <form action="/operator/antrian/terpanggil" class="block" method="post">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
                        <input type="hidden" name="antrian_jenjang" value="{{ $antrian->jenjang }}" />
                        <button type="submit" id="terpanggil-btn"
                            onclick="return confirm('Yakin? gk bisa di un-panggil lho ini')"
                            class="disabled:text-black/60 bg-green-600 text-white font-bold border-2 border-black rounded-full py-2 px-6">Antrian
                            Sudah Terpanggil</button>
                    </form>
                </div>

                <div>

                    {{-- <form action="/operator/antrian/lanjut/seragam" method="post">
        @csrf
        <input type="hidden" name="antrian_id" value="{{ $antrian->id }}" />
        <input type="hidden" name="nomor_antrian" value="{{ $antrian->nomor_antrian }}" />
        <input type="hidden" name="antrian_jenjang" value="{{ $antrian->jenjang }}" />
        <button type="submit" id="lanjut-seragam-btn"
          class="disabled:text-black/60 bg-blue-600 text-white font-bold py-2 px-6 rounded-full border-2 border-black">Lanjut
          Ke Seragam</button>
      </form> --}}
        @endif

    </div>

    </div>

    </div>

    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
    // All your JavaScript goes here


        const panggilBtn = document.getElementById('panggil-btn')
        const lewatiBtn = document.getElementById('lewati-btn')
        const terpanggilBtn = document.getElementById('terpanggil-btn')
        // const lanjutSeragamBtn = document.getElementById('lanjut-seragam-btn')

        const antrian = {{ Js::from($antrian) }}

        const socket = io(`{{ env('SOCKET_IO_SERVER') }}`)

        panggilBtn.addEventListener('click', () => {
            socket.emit('play current antrian audio', antrian)
        })

        if (lewatiBtn) {
            lewatiBtn.addEventListener('click', () => {
                socket.emit('skip antrian', 'skip')
            })
        }

        terpanggilBtn.addEventListener('click', () => {
            socket.emit('skip antrian', 'skip')
        })

        // lanjutSeragamBtn.addEventListener('click', () => {
        //   socket.emit('skip antrian', 'skip')
        // })

        // socket.emit('change antrian display', antrian)
socket.on("change antrian display loading", (antrian) => {
    if (lewatiBtn) lewatiBtn.setAttribute('disabled', 'true');
    if (terpanggilBtn) terpanggilBtn.setAttribute('disabled', 'true');
    if (panggilBtn) panggilBtn.setAttribute('disabled', 'true');
    console.log("loading...");
});

socket.on("change antrian display complete", (antrian) => {
    if (panggilBtn) panggilBtn.removeAttribute('disabled');
    if (lewatiBtn) lewatiBtn.removeAttribute('disabled');
    if (terpanggilBtn) terpanggilBtn.removeAttribute('disabled');
    console.log("done loading");
});


        // close button
        function closeButtonClicked() {
            // Menyembunyikan elemen yang ingin ditutup
            var closeButton = document.getElementById("closeButton");
            closeButton.style.display = "none";
        }
        });
    </script>
@endsection
