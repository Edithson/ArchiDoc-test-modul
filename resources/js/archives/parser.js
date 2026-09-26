/**
 * Module FilenameParser
 * Extraction et analyse intelligente des métadonnées issues du nom de fichier.
 */

const DICTIONNAIRE_NATURES = {
    'DECI': 'DECISIONS',
    'DECISION': 'DECISIONS',
    'DECISIONS': 'DECISIONS',
    'FD': 'FONDS DE DOSSIER',
    'FONDS-DE-DOSSIER': 'FONDS DE DOSSIER',
    'ESD': 'ETATS DE SOMMES DUES',
    'NOTE': 'NOTE DE SERVICE',
    'COMMUNIQUE': 'COMMUNIQUES',
    'COMMUNIQUES': 'COMMUNIQUES',
    'CONVOCATION': 'CONVOCATIONS',
    'CONVOCATIONS': 'CONVOCATIONS',
    'COURA': 'COURRIERS',
    'COURRIERS': 'COURRIERS',
    'ST': 'SOIT-TRANSMIS',
    'SOIT': 'SOIT-TRANSMIS',
    'INVITATION': 'INVITATIONS',
    'INVITATIONS': 'INVITATIONS',
    'INVITATAION': 'INVITATIONS',
    'ATTESTATION': 'ATTESTATIONS',
    'ATTESTATIONS': 'ATTESTATIONS',
    'MARCHE': 'MARCHE',
    'INSTRUCTIONS': 'INSTRUCTIONS',
    'INSTRUCTION': 'INSTRUCTIONS',
    'CONSTITUTION': 'CONSTITUTION',
    'LOI': 'LOI',
    'LAW': 'LOI',
    'LETTRE-DE-MISSION': 'LETTRE DE MISSION',
    'LETTRE': 'LETTRE DE MISSION',
    'MISSION': 'LETTRE DE MISSION',
    'ARRETE': 'ARRETE',
    'DECRET': 'DECRET',
    'DOSSIER-DU-PERSONNEL': 'DOSSIER DU PERSONNEL',
    'DOSSIER-DES-PENSIONS': 'DOSSIER DES PENSIONS',
    'RAPPORTS': 'RAPPORTS',
    'RAPPORT': 'RAPPORTS',
    'RAPORT': 'RAPPORTS',
    'FICHE': 'DOSSIER DU PERSONNEL',
    'CERTIF': 'DOSSIER DU PERSONNEL',
    'CERTIFICAT': 'CERTIFICATS',
    'CERTIFICATS': 'CERTIFICATS',
    'BE': "BONS D'ENGAGEMENT",
    'COMPTE': 'COMPTE-RENDU',
    'PV': 'PROCES-VERBAL',
    'BORDEREAU': 'BORDEREAUX',
    'BORDEREAUX': 'BORDEREAUX',
    'CESSATION': 'BORDEREAUX',
    'MEMO': 'MEMO',
    'DEMANDE': 'DEMANDES',
    'DEMANDES': 'DEMANDES',
    'DEMANE': 'DEMANDES',
};

/**
 * Détecte le séparateur le plus probable ('_', '-', ' ') dans le nom du fichier.
 * @param {string} nameSansExt 
 * @returns {string} Le séparateur détecté ('_', '-' ou ' ')
 */
export function detectSeparator(nameSansExt) {
    const candidates = ['_', '-', ' '];
    let bestSeparator = '_';
    let maxScore = -1;

    for (const sep of candidates) {
        const parts = nameSansExt.split(sep);
        if (parts.length < 2) continue;

        let score = 0;

        // Bonification si la dernière partie contient une date à 8 chiffres
        const lastPart = parts[parts.length - 1].trim();
        if (/^\d{8}$/.test(lastPart)) {
            score += 10;
        }

        // Bonification si la première partie correspond à un type d'archive connu
        const firstPart = parts[0].trim().toUpperCase();
        if (DICTIONNAIRE_NATURES[firstPart]) {
            score += 5;
        }

        // Ajout du nombre d'occurrences
        score += parts.length;

        if (score > maxScore) {
            maxScore = score;
            bestSeparator = sep;
        }
    }

    return bestSeparator;
}

/**
 * Extrait et formate une date au format YYYY-MM-DD depuis une chaîne de 8 chiffres.
 * Supporte JJMMAAAA et AAAAMMJJ.
 * @param {string} dateStr 
 * @returns {string|null} Date formatée YYYY-MM-DD ou null si invalide
 */
export function parseEightDigitDate(dateStr) {
    if (!/^\d{8}$/.test(dateStr)) return null;

    const first4 = parseInt(dateStr.substring(0, 4), 10);
    const last4 = parseInt(dateStr.substring(4, 8), 10);

    let year, month, day;

    // Cas 1 : AAAAMMJJ (ex: 20241015)
    if (first4 >= 1900 && first4 <= 2100) {
        year = dateStr.substring(0, 4);
        month = dateStr.substring(4, 6);
        day = dateStr.substring(6, 8);
    } 
    // Cas 2 : JJMMAAAA (ex: 15102024)
    else if (last4 >= 1900 && last4 <= 2100) {
        day = dateStr.substring(0, 2);
        month = dateStr.substring(2, 4);
        year = dateStr.substring(4, 8);
    } 
    else {
        return null;
    }

    const mNum = parseInt(month, 10);
    const dNum = parseInt(day, 10);

    if (mNum < 1 || mNum > 12 || dNum < 1 || dNum > 31) {
        return null;
    }

    return `${year}-${month}-${day}`;
}

/**
 * Analyse complète du nom de fichier pour extraire les métadonnées de l'archive.
 * @param {string} filename 
 * @param {Array<string>} availableTypes Liste des types d'archives disponibles dans l'application
 * @returns {Object} { typeArchive, dateDoc, description, separator }
 */
export function parseFilename(filename, availableTypes = []) {
    if (!filename) return null;

    // Suppression de l'extension (.pdf, .png, .jpg, etc.)
    const nomSansExt = filename.replace(/\.[^/.]+$/, '').trim();
    if (!nomSansExt) return null;

    const separator = detectSeparator(nomSansExt);
    const parts = nomSansExt.split(separator).map((p) => p.trim()).filter(Boolean);

    let natureDocument = null;
    let dateDoc = null;

    if (parts.length > 0) {
        // Recherche du type d'archive (première partie)
        const prefixUpper = parts[0].toUpperCase();
        natureDocument = DICTIONNAIRE_NATURES[prefixUpper] || null;

        // Si non trouvé dans le dictionnaire, recherche exacte ou partielle dans availableTypes
        if (!natureDocument && availableTypes.length > 0) {
            const foundExact = availableTypes.find((t) => t.toUpperCase() === prefixUpper);
            if (foundExact) {
                natureDocument = foundExact;
            } else {
                const foundIncludes = availableTypes.find((t) => t.toUpperCase().startsWith(prefixUpper));
                if (foundIncludes) {
                    natureDocument = foundIncludes;
                }
            }
        }
    }

    // Recherche de la date (dernière partie ou avant-dernière)
    if (parts.length > 1) {
        const lastPart = parts[parts.length - 1];
        dateDoc = parseEightDigitDate(lastPart);

        if (!dateDoc && parts.length > 2) {
            dateDoc = parseEightDigitDate(parts[parts.length - 2]);
        }
    }

    // Formater la description (résumé de l'objet, limité à 250 chars)
    let description = nomSansExt.replace(/[-_]/g, ' ').replace(/\s+/g, ' ').trim();
    if (description.length > 250) {
        description = description.substring(0, 250).trim();
    }

    return {
        typeArchive: natureDocument,
        dateDoc: dateDoc,
        description: description,
        separator: separator,
        rawName: nomSansExt,
    };
}
