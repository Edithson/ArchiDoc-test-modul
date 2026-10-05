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
        abort_if(! $request->user()?->hasPermission('Piece', 'read'), 403, "Accès non autorisé aux pièces d'intégration.");

        $query = Piece::withCount('files');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('obligatory')) {
            $obligatoryVal = $request->input('obligatory');
            if ($obligatoryVal === '1' || $obligatoryVal === '0') {
                $query->where('obligatory', (bool) (int) $obligatoryVal);
            }
        }

        $pieces = $query->orderBy('obligatory', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.pieces.index', [
            'pieces' => $pieces,
            'search' => $request->input('search'),
            'obligatoryFilter' => $request->input('obligatory'),
        ]);
    }

    /**
     * Show the form for creating a new integration piece.
     */
    public function create(): View
    {
        abort_if(! request()->user()?->hasPermission('Piece', 'create'), 403, 'Accès non autorisé à la création de pièces.');

        return view('admin.pages.pieces.create');
    }

    /**
     * Store a newly created integration piece in storage.
     */
    public function store(StorePieceRequest $request): RedirectResponse
    {
        abort_if(! $request->user()?->hasPermission('Piece', 'create'), 403, 'Accès non autorisé à la création de pièces.');

        $piece = Piece::create($request->validated());

        return redirect()->route('pieces.index')
            ->with('success', "La pièce d'intégration « {$piece->name} » a été ajoutée avec succès !");
    }

    /**
     * Show the form for editing the specified integration piece.
     */
    public function edit(Piece $piece): View
    {
        abort_if(! request()->user()?->hasPermission('Piece', 'update'), 403, 'Accès non autorisé à la modification de pièces.');

        return view('admin.pages.pieces.edit', [
            'piece' => $piece,
        ]);
    }

    /**
     * Update the specified integration piece in storage.
     */
    public function update(UpdatePieceRequest $request, Piece $piece): RedirectResponse
    {
        abort_if(! $request->user()?->hasPermission('Piece', 'update'), 403, 'Accès non autorisé à la modification de pièces.');

        $piece->update($request->validated());

        return redirect()->route('pieces.index')
            ->with('success', "La pièce d'intégration « {$piece->name} » a été mise à jour !");
    }

    /**
     * Remove the specified integration piece from storage.
     * Enforces functional dependency check on pivot/file relations.
     */
    public function destroy(Piece $piece): RedirectResponse
    {
        abort_if(! request()->user()?->hasPermission('Piece', 'delete'), 403, 'Accès non autorisé à la suppression de pièces.');

        $filesCount = $piece->files()->count();

        if ($filesCount > 0) {
            return redirect()->route('pieces.index')
                ->with('error', "Impossible de supprimer la pièce « {$piece->name} » car elle est actuellement associée à {$filesCount} dossier(s) du personnel. Veuillez d'abord retirer les pièces enregistrées sous ce libellé.");
        }

        $name = $piece->name;
        $piece->delete();

        return redirect()->route('pieces.index')
            ->with('success', "La pièce « {$name} » a été supprimée avec succès.");
    }
}
