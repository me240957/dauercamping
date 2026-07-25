<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BenutzerController extends Controller
{
    // Nur Admins dürfen diese Seiten aufrufen
    private function nurAdmin(): void
    {
        abort_if(!auth()->user()?->isAdmin(), 403, 'Nur Administratoren haben Zugriff auf die Benutzerverwaltung.');
    }

    public function index()
    {
        $this->nurAdmin();

        $benutzer = User::orderBy('name')->get();

        return view('benutzer.index', compact('benutzer'));
    }

    public function create()
    {
        $this->nurAdmin();

        return view('benutzer.create');
    }

    public function store(Request $request)
    {
        $this->nurAdmin();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,verwalter,leser',
            'aktiv'    => 'boolean',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'aktiv'    => $request->boolean('aktiv', true),
        ]);

        return redirect()->route('benutzer.index')
            ->with('success', 'Benutzer „' . $validated['name'] . '" wurde angelegt.');
    }

    public function edit(User $benutzer)
    {
        $this->nurAdmin();

        return view('benutzer.edit', compact('benutzer'));
    }

    public function update(Request $request, User $benutzer)
    {
        $this->nurAdmin();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($benutzer->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:admin,verwalter,leser',
            'aktiv'    => 'boolean',
        ]);

        // Schutz: eigene Rolle und aktiv-Status nicht selbst ändern
        $istEigenesKonto = $benutzer->id === auth()->id();

        $benutzer->name  = $validated['name'];
        $benutzer->email = $validated['email'];

        if (!$istEigenesKonto) {
            $benutzer->role  = $validated['role'];
            $benutzer->aktiv = $request->boolean('aktiv', true);
        }

        if (!empty($validated['password'])) {
            $benutzer->password = Hash::make($validated['password']);
        }

        $benutzer->save();

        return redirect()->route('benutzer.index')
            ->with('success', 'Benutzer „' . $benutzer->name . '" wurde aktualisiert.');
    }

    public function destroy(User $benutzer)
    {
        $this->nurAdmin();

        // Kein Selbst-Löschen
        abort_if($benutzer->id === auth()->id(), 403, 'Du kannst dein eigenes Konto nicht löschen.');

        $name = $benutzer->name;
        $benutzer->delete();

        return redirect()->route('benutzer.index')
            ->with('success', 'Benutzer „' . $name . '" wurde gelöscht.');
    }
}
