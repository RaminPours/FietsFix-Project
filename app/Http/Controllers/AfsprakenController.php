<?php

namespace App\Http\Controllers;

use App\Models\Afspraken;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AfsprakenController extends Controller
{
    public function index()
    {
        return view('afspraken.index');
    }

    public function create()
    {
        // Haal de eerder ingevulde velden op, zodat de gebruiker niets opnieuw hoeft in te vullen.
        $selectie = session()->getOldInput();

        // Controleer eerst of de vestiging en datum geldig zijn.
        $beschikbaarheidGeladen = Validator::make($selectie, $this->beschikbaarheidRegels())->passes();

        $bezetteTijden = [];
        $volgeboekt = false;

        if ($beschikbaarheidGeladen) {
            // Zoek de afspraken voor deze vestiging op deze datum.
            $bezetteTijden = Afspraken::where('vestiging', $selectie['vestiging'])
                ->whereDate('datum', $selectie['datum'])
                ->pluck('tijd')
                ->map(fn ($tijd) => substr($tijd, 0, 5))
                ->all();

            // De dag is vol, tenzij we minstens één vrij tijdstip vinden.
            $volgeboekt = empty(array_diff(Afspraken::tijdstippen(), $bezetteTijden));
        }

        // Geef de resultaten door aan het formulier.
        return view('afspraken.create', [
            'bezetteTijden' => $bezetteTijden,
            'beschikbaarheidGeladen' => $beschikbaarheidGeladen,
            'volgeboekt' => $volgeboekt,
        ]);
    }

    public function contact()
    {
        return view('afspraken.contact');
    }

    public function fietsonderhoud()
    {
        return view('afspraken.fietsonderhoud');
    }

    public function Merken()
    {
        return view('afspraken.Merken');
    }

    public function store(Request $request)
    {
        // Deze knop vraagt alleen beschikbare tijden op en maakt nog geen afspraak.
        if ($request->input('actie') === 'beschikbaarheid') {
            $request->validate($this->beschikbaarheidRegels());

            // Ga terug naar het formulier en bewaar de ingevulde velden met withInput().
            return redirect()->route('afspraken.create')->withInput($request->only([
                'vestiging', 'datum', 'tijd', 'naam', 'email', 'telefoonnummer',
                'fietstype', 'fietsmerk', 'probleem',
            ]));
        }

        // Bij 'Afspraak inplannen' moeten alle gegevens geldig zijn.
        $validated = $request->validate([
            'naam' => ['required', 'string', 'max:250'],
            'email' => ['required', 'email', 'max:250'],
            'telefoonnummer' => ['required', 'string', 'max:20'],
            'vestiging' => ['required', 'string', Rule::in(config('vestigingen.locaties'))],
            'fietstype' => ['required', 'string', 'max:250'],
            'fietsmerk' => ['required', 'string', 'max:250'],
            'probleem' => ['required', 'string', 'max:500'],
            'datum' => ['required', 'date', 'after_or_equal:today'],
            'tijd' => ['required', 'date_format:H:i', Rule::in(Afspraken::tijdstippen())],
        ], [
            'vestiging.required' => 'Kies een vestiging voor uw afspraak.',
            'vestiging.in' => 'Kies een geldige vestiging.',
            'tijd.in' => 'Kies een tijd tussen 08:00 en 17:00, in stappen van een kwartier.',
        ]);

        $bezetMelding = 'Dit tijdstip zit vol bij de gekozen vestiging. Kies een andere datum of tijd.';

        // Controleer opnieuw: iemand kan dit tijdstip ondertussen hebben geboekt.
        $tijdstipBezet = Afspraken::where('vestiging', $validated['vestiging'])
            ->whereDate('datum', $validated['datum'])
            ->whereTime('tijd', $validated['tijd'].':00')
            ->exists();

        if ($tijdstipBezet) {
            throw ValidationException::withMessages(['tijd' => $bezetMelding]);
        }

        // Koppel de afspraak aan de ingelogde gebruiker en voeg seconden toe aan de tijd.
        $validated['user_id'] = Auth::id();
        $validated['tijd'] .= ':00';

        try {
            // Sla de afspraak op in de database.
            Afspraken::create($validated);
        } catch (UniqueConstraintViolationException $exception) {
            // De database voorkomt ook dubbele boekingen als twee mensen tegelijk boeken.
            throw ValidationException::withMessages(['tijd' => $bezetMelding]);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Uw afspraak is succesvol gemaakt.');
    }

    public function dashboard()
    {
        // Laat alleen de afspraken van de ingelogde gebruiker zien, nieuwste eerst.
        $afspraken = Afspraken::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('dashboard', compact('afspraken'));
    }

    public function delete(int $id)
    {
        // Zoek alleen in de eigen afspraken, zodat je geen afspraak van een ander kunt verwijderen.
        $afspraak = Afspraken::whereKey($id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $afspraak->delete();

        return redirect()
            ->route('afspraken.index')
            ->with('success', 'De afspraak is verwijderd.');
    }

    private function beschikbaarheidRegels(): array
    {
        return [
            'vestiging' => ['required', 'string', Rule::in(config('vestigingen.locaties'))],
            'datum' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ];
    }
}
