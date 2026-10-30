<?php

namespace Database\Seeders;

use App\Models\ArchiveType;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArchiveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds for coherent, realistic enterprise archive types.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'CONTRAT & CONVENTION',
                'description' => 'Contrats commerciaux, conventions de partenariat, baux et accords contractuels signés.',
                'dua' => 10,
            ],
            [
                'name' => 'FACTURE & PIECE COMPTABLE',
                'description' => 'Factures clients et fournisseurs, justificatifs de paiement et bordereaux comptables.',
                'dua' => 10,
            ],
            [
                'name' => 'NOTE DE SERVICE',
                'description' => 'Notes de service internes, consignes d\'organisation et directives opérationnelles.',
                'dua' => 5,
            ],
            [
                'name' => 'PROCES-VERBAL',
                'description' => 'Procès-verbaux de comités de direction, conseils d\'administration et assemblées.',
                'dua' => 30,
            ],
            [
                'name' => 'RAPPORT D\'ACTIVITE',
                'description' => 'Rapports d\'activité annuels, bilans d\'exploitation et états d\'avancement périodiques.',
                'dua' => 10,
            ],
            [
                'name' => 'DECISION DE DIRECTION',
                'description' => 'Décisions de gouvernance, délégations de signature et actes formels de gestion.',
                'dua' => 20,
            ],
            [
                'name' => 'COURRIER & CORRESPONDANCE',
                'description' => 'Courriers officiels départ et arrivée, correspondances institutionnelles et administratives.',
                'dua' => 5,
            ],
            [
                'name' => 'DOSSIER TECHNIQUE & PROJET',
                'description' => 'Cahiers des charges, spécifications d\'ingénierie, livrables et plans directeurs.',
                'dua' => 10,
            ],
            [
                'name' => 'AUDIT & CONTROLE',
                'description' => 'Rapports d\'audit interne et externe, contrôles de gestion et revues de conformité.',
                'dua' => 10,
            ],
            [
                'name' => 'CIRCULAIRE ADMINISTRATIVE',
                'description' => 'Circulaires d\'application, guides de procédures internes et instructions réglementaires.',
                'dua' => 10,
            ],
            [
                'name' => 'COMPTE-RENDU DE REUNION',
                'description' => 'Comptes-rendus synthétiques des réunions d\'équipe, comités de projet et points hebdomadaires.',
                'dua' => 5,
            ],
            [
                'name' => 'BON DE COMMANDE & LIVRAISON',
                'description' => 'Bons de commande signés, ordres de service et bons de livraison de prestations.',
                'dua' => 5,
            ],
            [
                'name' => 'FICHE DE PAIE & REMUNERATION',
                'description' => 'Bulletins de salaire récapitulatifs, états des rémunérations et déclarations sociales.',
                'dua' => 30,
            ],
            [
                'name' => 'ATTESTATION & CERTIFICAT',
                'description' => 'Attestations administratives, certificats de conformité et attestations de service fait.',
                'dua' => 5,
            ],
            [
                'name' => 'CONVOCATION OFFICIELLE',
                'description' => 'Convocations formelles aux réunions statutaires, comités et assemblées générales.',
                'dua' => 3,
            ],
            [
                'name' => 'LETTRE DE MISSION',
                'description' => 'Ordres et lettres de mission pour interventions sur site, missions opérationnelles et d\'audit.',
                'dua' => 5,
            ],
            [
                'name' => 'DOSSIER DU PERSONNEL',
                'description' => 'Actes d\'embauche, contrats de travail, avenants et pièces de gestion des carrières.',
                'dua' => 50,
            ],
            [
                'name' => 'BORDEREAU D\'EXPEDITION',
                'description' => 'Bordereaux d\'envoi, récépissés d\'expédition et fiches de transmission de courriers.',
                'dua' => 3,
            ],
            [
                'name' => 'DEVIS & OFFRE COMMERCIALE',
                'description' => 'Devis estimatifs, propositions commerciales validées et réponses aux consultations.',
                'dua' => 5,
            ],
            [
                'name' => 'DOCUMENT JURIDIQUE & LITIGE',
                'description' => 'Actes juridiques, correspondances contentieuses, statuts sociaux et transactions.',
                'dua' => 30,
            ],
            [
                'name' => 'MEMORANDUM STRATEGIQUE',
                'description' => 'Notes d\'analyse stratégique, notes d\'orientation et synthèses exécutives.',
                'dua' => 5,
            ],
            [
                'name' => 'AUTRES DOCUMENTS ADMINISTRATIFS',
                'description' => 'Documents administratifs divers non classifiés dans les nomenclatures spécifiques.',
                'dua' => 5,
            ],
        ];

        $userId = User::value('id');
        if (! $userId) {
            $user = User::factory()->create();
            $userId = $user->id;
        }

        foreach ($types as $type) {
            ArchiveType::firstOrCreate(
                ['name' => $type['name']],
                [
                    'description' => $type['description'],
                    'dua' => $type['dua'],
                    'created_by' => $userId,
                ]
            );
        }
    }
}
