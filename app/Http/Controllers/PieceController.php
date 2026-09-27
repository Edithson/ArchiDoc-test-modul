<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePieceRequest;
use App\Http\Requests\UpdatePieceRequest;
use App\Models\Piece;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PieceController extends Controller
{
    /**
     * Display a listing of integration pieces.
     */
    public function index(Request $request): View
    {
        $query = Piece::withCount('files');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $pieces = $query->orderBy('obligatory', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.pieces.index', [
            'pieces' => $pieces,
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Show the form for creating a new integration piece.
     */
    public function create(): View
    {
        return view('admin.pages.pieces.create');
    }

    /**
     * Store a newly created integration piece in storage.
     */
    public function store(StorePieceRequest $request): RedirectResponse
    {
        $piece = Piece::create($request->validated());

        return redirect()->route('pieces.index')
            ->with('success', "La pièce d'intégration « {$piece->name} » a été ajoutée avec succès !");
    }

    /**
     * Show the form for editing the specified integration piece.
     */
    public function edit(Piece $piece): View
    {
        return view('admin.pages.pieces.edit', [
            'piece' => $piece,
        ]);
    }

    /**
     * Update the specified integration piece in storage.
     */
    public function update(UpdatePieceRequest $request, Piece $piece): RedirectResponse
    {
        $piece->update($request->validated());

        return redirect()->route('pieces.index')
            ->with('success', "La pièce d'intégration « {$piece->name} » a été mise à jour !");
    }

    /**
     * Remove the specified integration piece from storage.
     */
    public function destroy(Piece $piece): RedirectResponse
    {
        $name = $piece->name;
        $piece->delete();

        return redirect()->route('pieces.index')
            ->with('success', "La pièce « {$name} » a été supprimée avec succès.");
    }
}
