document.addEventListener('DOMContentLoaded', () => {
    /* Données dynamiques injectées depuis le contrôleur */
    const ARCHIVE_TYPES = (window.ArchiDoc && window.ArchiDoc.archiveTypes) || [];

    /* Element DOMs */
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file');
    const dropzonePrompt = document.getElementById('dropzone-prompt');
    const selectedFileBadge = document.getElementById('selected-file-badge');
    const fileNameDisplay = document.getElementById('file-name-display');
    const fileSizeDisplay = document.getElementById('file-size-display');
    const fileIconContainer = document.getElementById('file-icon-container');
    const btnRemoveFile = document.getElementById('btn-remove-file');
    const fileError = document.getElementById('file-error');

    const formatSelect = document.getElementById('format');

    /* Aperçu DOMs */
    const previewEmptyState = document.getElementById('preview-empty-state');
    const previewPdfContainer = document.getElementById('preview-pdf-container');
    const previewPdf = document.getElementById('preview-pdf');
    const previewImageContainer = document.getElementById('preview-image-container');
    const previewImage = document.getElementById('preview-image');
    const previewUnsupportedState = document.getElementById('preview-unsupported-state');
    const previewStatusBadge = document.getElementById('preview-status-badge');
    const previewToolbar = document.getElementById('preview-toolbar');
    const previewFilename = document.getElementById('preview-filename');
    const btnOpenNewTab = document.getElementById('btn-open-new-tab');

    let currentObjectURL = null;

    /* Helper taille fichier */
    function formatBytes(bytes, decimals = 1) {
        if (bytes === 0) return '0 Octets';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Octets', 'Ko', 'Mo', 'Go'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    /* Nettoyage de l'aperçu */
    function clearPreview() {
        if (currentObjectURL) {
            URL.revokeObjectURL(currentObjectURL);
            currentObjectURL = null;
        }

        if (previewPdf) previewPdf.src = '';
        if (previewImage) previewImage.src = '';
        if (btnOpenNewTab) btnOpenNewTab.href = '#';

        if (previewEmptyState) previewEmptyState.classList.remove('hidden');
        if (previewPdfContainer) previewPdfContainer.classList.add('hidden');
        if (previewImageContainer) previewImageContainer.classList.add('hidden');
        if (previewUnsupportedState) previewUnsupportedState.classList.add('hidden');
        if (previewToolbar) previewToolbar.classList.add('hidden');

        if (previewStatusBadge) {
            previewStatusBadge.textContent = 'En attente';
            previewStatusBadge.className =
                'inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-600';
        }

        if (dropzonePrompt) dropzonePrompt.classList.remove('hidden');
        if (selectedFileBadge) selectedFileBadge.classList.add('hidden');
    }

    /* Mise à jour de l'aperçu lors du choix de fichier */
    function handleFileSelected(file) {
        if (!file) {
            clearPreview();
            return;
        }

        if (currentObjectURL) {
            URL.revokeObjectURL(currentObjectURL);
            currentObjectURL = null;
        }

        currentObjectURL = URL.createObjectURL(file);

        // Affichage du badge fichier
        if (dropzonePrompt) dropzonePrompt.classList.add('hidden');
        if (selectedFileBadge) selectedFileBadge.classList.remove('hidden');
        if (fileNameDisplay) fileNameDisplay.textContent = file.name;
        if (fileSizeDisplay) fileSizeDisplay.textContent = `${formatBytes(file.size)} · Fichier prêt`;
        if (fileError) {
            fileError.textContent = '';
            fileError.classList.add('hidden');
        }

        if (previewFilename) previewFilename.textContent = file.name;
        if (btnOpenNewTab) btnOpenNewTab.href = currentObjectURL;

        const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
        const isImage = file.type.startsWith('image/') || /\.(png|jpe?g|gif|webp|bmp|svg)$/i.test(file.name);

        if (previewEmptyState) previewEmptyState.classList.add('hidden');

        if (isPdf) {
            if (fileIconContainer) {
                fileIconContainer.textContent = 'PDF';
                fileIconContainer.className =
                    'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-700 font-bold text-xs uppercase';
            }
            if (previewStatusBadge) {
                previewStatusBadge.textContent = 'Document PDF';
                previewStatusBadge.className =
                    'inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 border border-red-200';
            }

            if (previewPdfContainer) previewPdfContainer.classList.remove('hidden');
            if (previewImageContainer) previewImageContainer.classList.add('hidden');
            if (previewUnsupportedState) previewUnsupportedState.classList.add('hidden');

            if (previewPdf) previewPdf.src = currentObjectURL;

            // Auto-sélection du format
            if (formatSelect && !formatSelect.value) {
                formatSelect.value = 'Document PDF';
            }
        } else if (isImage) {
            if (fileIconContainer) {
                fileIconContainer.textContent = 'IMG';
                fileIconContainer.className =
                    'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs uppercase';
            }
            if (previewStatusBadge) {
                previewStatusBadge.textContent = 'Image';
                previewStatusBadge.className =
                    'inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200';
            }

            if (previewImageContainer) previewImageContainer.classList.remove('hidden');
            if (previewPdfContainer) previewPdfContainer.classList.add('hidden');
            if (previewUnsupportedState) previewUnsupportedState.classList.add('hidden');

            if (previewImage) previewImage.src = currentObjectURL;

            // Auto-sélection du format
            if (formatSelect && !formatSelect.value) {
                formatSelect.value = 'Image';
            }
        } else {
            if (fileIconContainer) {
                fileIconContainer.textContent = 'DOC';
                fileIconContainer.className =
                    'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-700 font-bold text-xs uppercase';
            }
            if (previewStatusBadge) {
                previewStatusBadge.textContent = 'Fichier';
                previewStatusBadge.className =
                    'inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-700';
            }

            if (previewUnsupportedState) previewUnsupportedState.classList.remove('hidden');
            if (previewPdfContainer) previewPdfContainer.classList.add('hidden');
            if (previewImageContainer) previewImageContainer.classList.add('hidden');
        }

        if (previewToolbar) previewToolbar.classList.remove('hidden');
    }

    /* Evénements Dropzone et File Input */
    if (dropzone && fileInput) {
        dropzone.addEventListener('click', (e) => {
            if (e.target.closest('#btn-remove-file')) return;
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files && fileInput.files[0]) {
                handleFileSelected(fileInput.files[0]);
            }
        });

        /* Drag & Drop */
        ['dragenter', 'dragover'].forEach((eventName) => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-brand-600', 'bg-brand-50/50', 'ring-2', 'ring-brand-600/20');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-brand-600', 'bg-brand-50/50', 'ring-2', 'ring-brand-600/20');
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                handleFileSelected(files[0]);
            }
        });
    }

    /* Retirer le fichier */
    if (btnRemoveFile) {
        btnRemoveFile.addEventListener('click', (e) => {
            e.stopPropagation();
            if (fileInput) fileInput.value = '';
            clearPreview();
        });
    }

    /* ------------------------------------------------------------------ */
    /* Combobox "Type d'archives" avec autocomplétion                     */
    /* ------------------------------------------------------------------ */
    const typeInput = document.getElementById('typearchive');
    const typeListbox = document.getElementById('typearchive-listbox');
    const typeHint = document.getElementById('typearchive-hint');
    let activeIndex = -1;
    let currentMatches = [];

    function renderTypeOptions(query) {
        const q = query.trim().toLowerCase();
        currentMatches =
            q === ''
                ? ARCHIVE_TYPES.slice()
                : ARCHIVE_TYPES.filter((t) => t.toLowerCase().includes(q));

        if (currentMatches.length === 0) {
            typeListbox.innerHTML =
                '<li class="px-3 py-2 text-sm text-gray-400">Aucun type existant ne correspond.</li>';
        } else {
            typeListbox.innerHTML = currentMatches
                .map(
                    (t, i) =>
                        `<li role="option" id="type-opt-${i}" data-value="${t.replace(
                            /"/g,
                            '&quot;'
                        )}" class="cursor-pointer px-3 py-1.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700">${t}</li>`
                )
                .join('');
        }
        activeIndex = -1;
        updateHint(query);
    }

    function updateHint(query) {
        if (!typeHint) return;
        const trimmed = query.trim();
        if (trimmed === '') {
            typeHint.classList.add('hidden');
            return;
        }
        const exact = ARCHIVE_TYPES.some((t) => t.toLowerCase() === trimmed.toLowerCase());
        if (!exact) {
            typeHint.textContent = "Nouveau type — sera proposé à l'ajout dans la liste.";
            typeHint.classList.remove('hidden');
        } else {
            typeHint.classList.add('hidden');
        }
    }

    function openTypeListbox() {
        if (!typeListbox || !typeInput) return;
        typeListbox.classList.remove('hidden');
        typeInput.setAttribute('aria-expanded', 'true');
    }

    function closeTypeListbox() {
        if (!typeListbox || !typeInput) return;
        typeListbox.classList.add('hidden');
        typeInput.setAttribute('aria-expanded', 'false');
        activeIndex = -1;
    }

    function highlightActive() {
        if (!typeListbox) return;
        const items = typeListbox.querySelectorAll('li[role="option"]');
        items.forEach((li, i) => {
            if (i === activeIndex) {
                li.classList.add('bg-brand-50', 'text-brand-700');
                li.scrollIntoView({ block: 'nearest' });
            } else {
                li.classList.remove('bg-brand-50', 'text-brand-700');
            }
        });
    }

    if (typeInput && typeListbox) {
        typeInput.addEventListener('input', () => {
            renderTypeOptions(typeInput.value);
            openTypeListbox();
        });
        typeInput.addEventListener('focus', () => {
            renderTypeOptions(typeInput.value);
            openTypeListbox();
        });
        typeInput.addEventListener('keydown', (e) => {
            const items = typeListbox.querySelectorAll('li[role="option"]');
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (typeListbox.classList.contains('hidden')) {
                    renderTypeOptions(typeInput.value);
                    openTypeListbox();
                    return;
                }
                activeIndex = Math.min(activeIndex + 1, items.length - 1);
                highlightActive();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
                highlightActive();
            } else if (e.key === 'Enter') {
                if (activeIndex >= 0 && currentMatches[activeIndex]) {
                    e.preventDefault();
                    typeInput.value = currentMatches[activeIndex];
                    closeTypeListbox();
                    updateHint(typeInput.value);
                }
            } else if (e.key === 'Escape') {
                closeTypeListbox();
            }
        });

        typeListbox.addEventListener('click', (e) => {
            const li = e.target.closest('li[role="option"]');
            if (li && li.dataset.value) {
                typeInput.value = li.dataset.value;
                closeTypeListbox();
                updateHint(typeInput.value);
                typeInput.focus();
            }
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#typearchive') && !e.target.closest('#typearchive-listbox')) {
                closeTypeListbox();
            }
        });
    }

    /* Compteur de caractères — Objet de l'archive */
    const descriptionInput = document.getElementById('description');
    const descriptionCount = document.getElementById('description-count');
    if (descriptionInput && descriptionCount) {
        descriptionInput.addEventListener('input', () => {
            const len = descriptionInput.value.length;
            descriptionCount.textContent = `${len}/30`;
            descriptionCount.classList.toggle('text-red-500', len >= 30);
            descriptionCount.classList.toggle('text-amber-600', len >= 25 && len < 30);
            descriptionCount.classList.toggle('text-gray-400', len < 25);
        });
    }

    /* Date signature : restriction max à aujourd'hui */
    const dateInput = document.getElementById('date_doc');
    if (dateInput) {
        dateInput.max = new Date().toISOString().split('T')[0];
    }

    /* Validation et soumission du formulaire */
    const form = document.getElementById('archive-form');
    const successPanel = document.getElementById('success-panel');

    const REQUIRED_FIELDS = [
        { id: 'format', message: 'Veuillez sélectionner un format de document.' },
        { id: 'typearchive', message: "Veuillez indiquer un type d'archives." },
        { id: 'description', message: "Veuillez renseigner l'objet de l'archive." },
        { id: 'date_doc', message: 'Veuillez indiquer la date de signature.' },
        { id: 'emplacement', message: "Veuillez sélectionner l'emplacement physique." },
        { id: 'emplacement2', message: "Veuillez sélectionner l'emplacement virtuel." },
        { id: 'departement', message: "Veuillez sélectionner un groupe d'accès." },
    ];

    function clearErrors() {
        REQUIRED_FIELDS.forEach((f) => {
            const el = document.getElementById(f.id);
            const err = document.getElementById(`${f.id}-error`);
            if (el) {
                el.classList.remove('border-red-400', 'focus:border-red-500', 'focus:ring-red-500/30');
                el.removeAttribute('aria-invalid');
            }
            if (err) {
                err.textContent = '';
                err.classList.add('hidden');
            }
        });
        if (fileError) {
            fileError.textContent = '';
            fileError.classList.add('hidden');
        }
    }

    function showError(id, message) {
        const el = document.getElementById(id);
        const err = document.getElementById(`${id}-error`);
        if (el) {
            el.classList.add('border-red-400', 'focus:border-red-500', 'focus:ring-red-500/30');
            el.setAttribute('aria-invalid', 'true');
        }
        if (err) {
            err.textContent = message;
            err.classList.remove('hidden');
        }
    }

    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            clearErrors();
            if (successPanel) successPanel.classList.add('hidden');

            let firstInvalid = null;
            let valid = true;

            /* Vérification fichier */
            if (fileInput && (!fileInput.files || fileInput.files.length === 0)) {
                valid = false;
                if (fileError) {
                    fileError.textContent = 'Veuillez charger un fichier PDF ou une Image.';
                    fileError.classList.remove('hidden');
                }
                if (!firstInvalid) firstInvalid = dropzone;
            }

            REQUIRED_FIELDS.forEach((f) => {
                const el = document.getElementById(f.id);
                if (el && (!el.value || !el.value.trim())) {
                    valid = false;
                    showError(f.id, f.message);
                    if (!firstInvalid) firstInvalid = el;
                }
            });

            if (!valid) {
                if (firstInvalid) {
                    if (firstInvalid.scrollIntoView) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    if (firstInvalid.focus) firstInvalid.focus();
                }
                return;
            }

            if (successPanel) {
                successPanel.classList.remove('hidden');
                successPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                successPanel.focus();
            }
        });

        form.addEventListener('reset', () => {
            window.setTimeout(() => {
                clearErrors();
                clearPreview();
                if (successPanel) successPanel.classList.add('hidden');
                if (descriptionCount) {
                    descriptionCount.textContent = '0/30';
                    descriptionCount.className = 'shrink-0 text-xs text-gray-400';
                }
                if (typeHint) typeHint.classList.add('hidden');
            }, 0);
        });
    }
});
