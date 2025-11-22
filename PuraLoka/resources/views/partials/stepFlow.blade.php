@php
$order = $reverse ? 'md:flex-row-reverse' : 'md:flex-row';
$content_padding = $reverse ? 'md:pl-24 md:text-right' : 'md:pr-24 md:text-left';
$image_justify = $reverse ? 'md:justify-start' : 'md:justify-end';
@endphp

<div class="md:flex {{ $order }} md:items-center md:justify-between mb-20 lg:mb-24 relative z-20 group">

    {{-- Lingkaran Angka Absolut (Hanya terlihat di desktop) --}}
    <div class="hidden md:flex absolute left-1/2 top-60 transform -translate-x-1/2 -translate-y-1/2 
                w-20 h-20 rounded-full bg-white ring-4 ring-emerald-600 shadow-xl 
                items-center justify-center text-emerald-600 z-30 transition duration-300 group-hover:ring-8">
        <span class="text-3xl font-extrabold">{{ $step }}</span>
    </div>

    <div class="md:w-1/2 text-center {{ $content_padding }} mb-8 md:mb-0 bg-white md:bg-transparent p-4 md:p-0 rounded-xl">
        <div class="flex items-center justify-center w-20 h-20 mx-auto rounded-full bg-emerald-600 text-white shadow-xl mb-4 md:hidden">
            <span class="text-3xl font-extrabold">{{ $step }}</span>
        </div>

        <h3 class="text-3xl font-bold mt-4 mb-3 text-gray-900">
            @if ($step == 1) Unggah Aset Anda
            @elseif ($step == 2) Berikan Prompt/Deskripsi
            @else Dapatkan Hasil AI
            @endif
        </h3>
        <p class="text-gray-700 text-lg">
            @if ($step == 1) Pilih gambar atau foto yang ingin Anda proses (baik untuk Video, Generasi, atau Restorasi).
            @elseif ($step == 2) Tuliskan instruksi atau gaya kreatif yang Anda inginkan. Semakin spesifik, semakin baik hasilnya.
            @else AI kami akan memproses permintaan Anda, dan hasilnya siap Anda unduh dalam hitungan detik.
            @endif
        </p>
    </div>

    <div class="md:w-1/2 flex justify-center {{ $image_justify }}">
        <!-- <img src="{{ asset('assets/images/placeholder-' . $step . '.jpeg') }}" alt="Langkah {{ $step }}"  -->
        <!-- <img src="{{ asset('assets/images/test.jpeg') }}" alt="Langkah {{ $step }}" -->
        <img src="/assets/images/test.jpeg" alt="Langkah {{ $step }}"
            class="w-full max-w-sm lg:max-w-md h-auto rounded-2xl shadow-2xl ring-4 ring-emerald-100 transition-transform duration-500 hover:scale-[1.03] cursor-pointer">
    </div>

    @if ($step < 3)
        <div class="md:hidden flex justify-center pt-8">
        <svg class="w-10 h-10 text-emerald-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
</div>
@endif
</div>