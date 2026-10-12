@props(['image', 'eyebrow', 'title', 'description'])

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ asset('images/' . $image) }}" alt="" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/75 to-slate-900/40">
    </div>
    <div class="relative mx-auto max-w-7xl px-6 py-20 sm:py-28 lg:px-8">
        <div class="max-w-2xl text-white">
            <p class="mb-5 text-sm font-bold uppercase tracking-widest text-teal-200">
                {{ $eyebrow }}
            </p>
            <h1 class="text-4xl font-bold leading-tight tracking-tight sm:text-6xl">
                {{ $title }}
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-200">
                {{ $description }}
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
