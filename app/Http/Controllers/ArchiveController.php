<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchiveRequest;
use App\Http\Requests\UpdateArchiveRequest;
use App\Models\Archive;
use Illuminate\Contracts\View\View;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.pages.archives.create', [
            'formats' => $this->getFormats(),
            'archiveTypes' => $this->getArchiveTypes(),
            'emplacementsPhysiques' => $this->getEmplacementsPhysiques(),
            'emplacementsVirtuels' => $this->getEmplacementsVirtuels(),
            'groupesAcces' => $this->getGroupesAcces(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArchiveRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Archive $archive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Archive $archive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArchiveRequest $request, Archive $archive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Archive $archive)
    {
        //
    }

    /**
     * Get the list of document formats.
     *
     * @return array<int, string>
     */
    protected function getFormats(): array
    {
        return [
            'Document PDF',
            'Image',
            'Document Papier',
        ];
    }

    /**
     * Get the list of archive types.
     *
     * @return array<int, string>
     */
    protected function getArchiveTypes(): array
    {
        return [
            'ARRETE',
            'ATTESTATION',
            'AUTRES TYPES DE DOCUMENTS',
            "BONS D'ENGAGEMENT",
            'BORDEREAUX',
            "CARNETS D'ENGAGEMENT",
            'CERTIFICATS',
            'CIRCULAIRE',
            'COMMUNIQUES',
            'COMPTE ADMINISTRATIF',
            "COMPTE D'EMPLOI",
            'COMPTE-RENDU',
            'CONSTITUTION',
            'CONVOCATIONS',
            'COURRIERS',
            'DECISIONS',
            'DECRET',
            'ETATS DE SOMMES DUES',
            'FONDS DE DOSSIER',
            'INVITATIONS',
            'LETTRE CIRCULAIRE',
            'LETTRE DE MISSION',
            'LOI',
            'MEMO',
            'MEMOIRES DE DEPENSE',
            'MESSAGE-FAX',
            'MESSAGE-PORTE',
            'NOTE',
            'NOTE DE SERVICE',
            'ORDONNANCES',
            'PROCES-VERBAL',
            'SOIT-TRANSMIS',
        ];
    }

    /**
     * Get the list of physical locations.
     *
     * @return array<string, string>
     */
    protected function getEmplacementsPhysiques(): array
    {
        return [
            'FOUDA' => "FOUDA — Centre d'excellence DGB",
            'DGB' => 'DGB — Direction Générale du Budget',
            'IMPRIMERIE NATIONALE' => 'Imprimerie Nationale',
        ];
    }

    /**
     * Get the list of virtual locations.
     *
     * @return array<string, string>
     */
    protected function getEmplacementsVirtuels(): array
    {
        return [
            'Serveur' => 'Serveur',
        ];
    }

    /**
     * Get access groups.
     *
     * @return array<int, array{sigle: string, nom: string}>
     */
    protected function getGroupesAcces(): array
    {
        return [
            ['sigle' => 'CAB DGB', 'nom' => 'Cabinet DGB'],
            ['sigle' => 'DCOB', 'nom' => "Division du Contrôle Budgétaire, de l'Audit et de la Qualité de la Dépense"],
            ['sigle' => 'DDPP', 'nom' => 'Direction de la Dépense du Personnel et des Pensions'],
            ['sigle' => 'DI', 'nom' => 'Division Informatique'],
            ['sigle' => 'DPB', 'nom' => 'Division de la Préparation du Budget'],
            ['sigle' => 'DPC', 'nom' => 'Division de Participation et Contribution'],
            ['sigle' => 'DREF', 'nom' => 'Division de la Réforme Budgétaire'],
            ['sigle' => 'PUBLIC', 'nom' => 'Public'],
            ['sigle' => 'S-DAG', 'nom' => 'Sous-Direction des Affaires Générales'],
            ['sigle' => 'S-DCF', 'nom' => 'Sous-Direction du Contrôle Financier'],
            ['sigle' => 'SGCCC', 'nom' => 'Service de Gestion des Crédits des Chapitres Communs'],
            ['sigle' => 'SGDB', 'nom' => 'Service de Gestion des Documents Budgétaires'],
            ['sigle' => 'SO', 'nom' => "Service d'Ordre"],
        ];
    }
}
