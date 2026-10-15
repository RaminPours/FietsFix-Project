{{-- CSS opent het menu bij hover, toetsenbordfocus of een tik op mobiel. --}}
<div class="ff-shop-menu relative">
    <button type="button" aria-controls="webshop-menu" class="ff-nav-link cursor-pointer {{ request()->routeIs('winkel.*') ? 'ff-nav-active' : 'ff-nav-inactive' }}">
        FietsFix Webshop
    </button>
    {{-- De padding houdt de ruimte tussen knop en menu bereikbaar voor de muis. --}}
    <div id="webshop-menu" class="ff-shop-dropdown relative z-50 w-64 pt-2 sm:absolute sm:left-0 sm:top-full">
    <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-lg">
        <a href="{{ route('winkel.index') }}" class="ff-nav-link ff-nav-inactive block">Bekijk de webshop</a>
        <p class="px-4 pb-2 pt-4 text-xs font-bold uppercase tracking-widest text-teal-700">Fietsen</p>
        <ul>
            {{-- Links staat de databasecategorie; rechts staat de tekst in het menu. --}}
            @foreach (['E-bike' => 'Elektrische fietsen', 'Stadsfiets' => 'Stadsfietsen', 'Bakfiets' => 'Bakfietsen'] as $categorie => $naam)
                <li>
                    <a href="{{ route('winkel.index', ['categorie' => $categorie]) }}" class="ff-nav-link ff-nav-inactive block">
                        {{ $naam }}
                    </a>
                </li>
            @endforeach
        </ul>
        <p class="mt-3 border-t border-slate-200 px-4 pb-2 pt-4 text-xs font-bold uppercase tracking-widest text-teal-700">Accessoires</p>
        <ul>
            <li><a href="{{ route('winkel.accessoires') }}" class="ff-nav-link ff-nav-inactive block">Alle accessoires</a></li>
            @foreach (config('winkel.accessoires') as $categorie)
                <li>
                    <a href="{{ route('winkel.accessoires', ['categorie' => $categorie]) }}" class="ff-nav-link ff-nav-inactive block">
                        {{ $categorie }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    </div>
</div>
