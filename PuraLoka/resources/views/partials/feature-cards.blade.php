<section id="fitur-ai" class="py-20 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-5xl font-extrabold text-center mb-16 text-gray-900">
            Tools <span class="text-mid">AI</span>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-8 mx-auto">

            @foreach($ai_tools as $tool)
            <a href="{{ url('/' . $tool['id']) }}"
                class="group block p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.03] border-4 border-transparent hover:border-mid bg-white">

                <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-yellow-100 text-mid">
                    {!! $tool['icon_svg'] !!}
                </div>

                <h3 class="text-2xl font-bold mt-2 mb-3 text-gray-900 group-hover:text-mid">
                    {{ $tool['title'] }}
                </h3>

                <p class="text-gray-600">
                    {{ $tool['description'] }}
                </p>
            </a>
            @endforeach

        </div>
    </div>
</section>