<section id="alur" class="py-20 md:py-32 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-4xl lg:text-5xl font-extrabold text-center mb-16 lg:mb-24 text-gray-900 leading-tight">
            Alur Kerja Cepat. <span class="text-mid">Semudah Tiga Langkah.</span>
        </h2>

        <div class="relative">
            <div class="hidden md:block absolute w-1 h-full bg-linear-to-b from-transparent via-yellow-400 to-transparen left-1/2 transform -translate-x-1/2 top-0"></div>

            {{-- --------------------------------- LANGKAH 1 --------------------------------- --}}
            @include('partials.stepFlow', ['step' => 1, 'reverse' => false])

            {{-- --------------------------------- LANGKAH 2 --------------------------------- --}}
            @include('partials.stepFlow', ['step' => 2, 'reverse' => true])

            {{-- --------------------------------- LANGKAH 3 --------------------------------- --}}
            @include('partials.stepFlow', ['step' => 3, 'reverse' => false])

        </div>
    </div>
</section>