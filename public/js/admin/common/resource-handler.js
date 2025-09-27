document.addEventListener('DOMContentLoaded', function () {

    // CSRF token'ını tüm AJAX istekleri için ayarlayalım
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    /**
     * Tüm AJAX isteklerini yöneten genel bir yardımcı fonksiyon.
     * Hata yakalama ve bildirim gösterme işlemlerini merkezi olarak yapar.
     * @param {string} url - İstek yapılacak URL.
     * @param {object} options - Fetch API seçenekleri (method, body, vb.).
     * @returns {Promise|null} - Başarılı olursa JSON verisi, hata olursa null döner.
     */
    async function fetchRequest(url, options) {
        options.headers = {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            ...options.headers
        };
        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Bir sunucu hatası oluştu.');
            }
            return await response.json();
        } catch (error) {
            console.error('Fetch Hatası:', error);
            iziToast.error({ title: 'Hata!', message: error.message, position: 'topRight' });
            return null;
        }
    }

    // --- 1. SÜRÜKLE-BIRAK İLE SIRALAMA ---
    const sortableList = document.getElementById('sortable-list');
    if (sortableList) {
        new Sortable(sortableList, {
            handle: '.handle-cell',
            animation: 150,
            onUpdate: function () {
                const order = Array.from(sortableList.querySelectorAll('tr')).map((row, index) => ({
                    id: row.dataset.id,
                    position: index
                }));
                const url = sortableList.dataset.updateUrl;
                const formData = new FormData();
                order.forEach((item, index) => {
                    formData.append(`order[${index}][id]`, item.id);
                    formData.append(`order[${index}][position]`, item.position);
                });

                fetchRequest(url, { method: 'POST', body: formData })
                    .then(data => {
                        if (data && data.success) {
                            iziToast.success({ title: 'Başarılı!', message: data.message || 'Sıralama güncellendi.', position: 'topRight' });
                        }
                    });
            }
        });
    }

    // --- 2. DURUM DEĞİŞTİRME (SWITCH) ---
    // Bu kod, .status-switch sınıfına sahip tüm checkbox'ları dinler.
    document.addEventListener('change', function(e) {
        if (e.target.matches('.status-switch')) {
            const id = e.target.dataset.id;
            const model = e.target.dataset.model;
            const status = e.target.checked;
            const url = `/admin/${model}/${id}/status`;

            const formData = new FormData();
            formData.append('status', status ? '1' : '0');
            formData.append('_method', 'PATCH');

            fetchRequest(url, { method: 'POST', body: formData })
                .then(data => {
                    if (data && data.success) {
                        iziToast.success({ title: 'Başarılı!', message: 'Durum güncellendi.', position: 'topRight' });
                    }
                });
        }
    });

    // --- 3. TEKLİ SİLME ONAYI (FORM İLE) ---
    // Bu kod, .delete-form sınıfına sahip tüm form gönderimlerini yakalar.
    document.addEventListener('submit', function(e) {
        if (e.target.matches('.delete-form')) {
            e.preventDefault();
            const form = e.target;
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });

    // --- 4. TOPLU İŞLEMLER (SEÇİLENLERİ SİL) ---
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    function toggleBulkDeleteBtn() {
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const anyChecked = Array.from(rowCheckboxes).some(c => c.checked);
        if (bulkDeleteBtn) {
            bulkDeleteBtn.classList.toggle('d-none', !anyChecked);
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(checkbox => checkbox.checked = this.checked);
            toggleBulkDeleteBtn();
        });

        document.querySelector('tbody')?.addEventListener('change', function(e) {
            if(e.target.matches('.row-checkbox')) {
                toggleBulkDeleteBtn();
            }
        });
    }

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const selectedIds = Array.from(rowCheckboxes)
                .filter(c => c.checked)
                .map(c => c.value);
            const model = this.dataset.model;
            const url = `/admin/${model}/bulk-delete`;

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Emin misiniz?',
                text: `${selectedIds.length} adet kaydı silmek üzeresiniz. Bu işlem geri alınamaz!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, hepsini sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    selectedIds.forEach(id => formData.append('ids[]', id));
                    fetchRequest(url, { method: 'POST', body: formData })
                        .then(data => {
                            if (data && data.success) {
                                iziToast.success({ title: 'Başarılı!', message: data.message, position: 'topRight' });
                                setTimeout(() => window.location.reload(), 1500);
                            }
                        });
                }
            });
        });
    }

    // --- 5. DÜZENLEME MODALI VERİ DOLDURMA ---
    const editModalEl = document.getElementById('editModal');
    if (editModalEl) {
        editModalEl.addEventListener('show.bs.modal', async function (event) {
            const button = event.relatedTarget;
            const fetchUrl = button.dataset.fetchUrl;
            const updateUrl = button.dataset.updateUrl;
            const modelName = button.dataset.model; // << EKLENMESİ GEREKEN TEK SATIR
            const form = editModalEl.querySelector('#editForm');

            form.action = updateUrl;
            form.reset();

            const data = await fetchRequest(fetchUrl, { method: 'GET' });

            if (data) {
                // Form alanlarını gelen JSON verisiyle doldur
                Object.keys(data).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) {
                        if (field.type === 'file') {
                            // Güvenlik nedeniyle dosya input'larına programatik olarak değer atanamaz.
                        } else if (field.type === 'checkbox') {
                            field.checked = data[key] == 1;
                        } else {
                            field.value = data[key];
                        }
                    }
                });

                // YENİ EKLENEN VE SORUNU ÇÖZEN BÖLÜM
                // Modal içindeki status switch'in data attributelarını set et
                const statusSwitchInModal = form.querySelector('.status-switch');
                if (statusSwitchInModal) {
                    statusSwitchInModal.dataset.id = data.id;       // Gelen veriden ID'yi ata
                    statusSwitchInModal.dataset.model = modelName; // Butondan gelen model adını ata
                }
                // YENİ BÖLÜM SONU

                // Mevcut görsel için önizleme alanı
                const imagePreviewContainer = form.querySelector('#image-preview-container');
                if (imagePreviewContainer) {
                    if (data.image_full_url) {
                        const imagePreview = form.querySelector('#image-preview');
                        if (imagePreview) {
                            imagePreview.src = data.image_full_url;
                            imagePreviewContainer.style.display = 'block';
                        }
                    } else {
                        imagePreviewContainer.style.display = 'none';
                    }
                }

                // Eğer sayfada TinyMCE editörü varsa, içeriğini doldur
                if (typeof tinymce !== 'undefined') {
                    setTimeout(() => {
                        const editor = tinymce.get('edit_content');
                        if (editor) {
                            editor.setContent(data.content || '');
                        }
                    }, 150);
                }
            }
        });
    }

    // --- 6. AJAX FORM GÖNDERİMİ (CREATE & UPDATE) ---
    async function handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const url = form.action;
        const formData = new FormData(form);

        // Eğer TinyMCE varsa, içeriğini form verisine ekle
        if (typeof tinymce !== 'undefined') {
            const editorId = form.id === 'createForm' ? 'create_content' : 'edit_content';
            const editor = tinymce.get(editorId);
            if (editor) {
                formData.set('content', editor.getContent());
            }
        }

        const response = await fetchRequest(url, { method: 'POST', body: formData });

        if (response && response.success) {
            const modalEl = form.closest('.modal');
            if(modalEl) {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
            }
            iziToast.success({ title: 'Başarılı!', message: response.message, position: 'topRight' });
            setTimeout(() => window.location.reload(), 1000);
        }
    }

    document.getElementById('createForm')?.addEventListener('submit', handleFormSubmit);
    document.getElementById('editForm')?.addEventListener('submit', handleFormSubmit);

});
