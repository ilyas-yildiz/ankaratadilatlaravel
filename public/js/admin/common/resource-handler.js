document.addEventListener('DOMContentLoaded', function () {

    // CSRF token'ını tüm AJAX istekleri için ayarlayalım
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Genel AJAX isteği için bir yardımcı fonksiyon
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
                throw new Error(errorData.message || 'Bir hata oluştu.');
            }
            return await response.json();
        } catch (error) {
            console.error('Fetch Error:', error);
            iziToast.error({ title: 'Hata!', message: error.message, position: 'topRight' });
            return null;
        }
    }

// --- 1. SÜRÜKLE-BIRAK İLE SIRALAMA ---
    const sortableList = document.getElementById('sortable-list');
    if (sortableList) {
        const sortable = new Sortable(sortableList, {
            handle: '.handle-cell',
            animation: 150,
            onUpdate: function (evt) {
                const order = Array.from(sortableList.querySelectorAll('tr')).map((row, index) => ({
                    id: row.dataset.id,
                    position: index
                }));
                const url = sortableList.dataset.updateUrl;

                // FormData kullanarak veriyi PHP'nin anlayacağı standart form formatında hazırlıyoruz.
                const formData = new FormData();
                order.forEach((item, index) => {
                    formData.append(`order[${index}][id]`, item.id);
                    formData.append(`order[${index}][position]`, item.position);
                });

                // İsteği gönderirken Content-Type belirtmiyoruz, tarayıcı FormData için
                // doğru başlığı (multipart/form-data) otomatik olarak ayarlayacak.
                fetchRequest(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(data => {
                        if (data && data.success) {
                            iziToast.success({ title: 'Başarılı!', message: data.message || 'Sıralama güncellendi.', position: 'topRight' });
                        }
                    });
            }
        });
    }

    // --- 2. DURUM DEĞİŞTİRME (SWITCH) ---
    document.querySelectorAll('.status-switch').forEach(switchEl => {
        switchEl.addEventListener('change', function () {
            const id = this.dataset.id;
            const model = this.dataset.model;
            const status = this.checked;
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
        });
    });

    // --- 3. TEKLİ SİLME ONAYI ---
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // --- 4. TOPLU İŞLEMLER (SEÇİLENLERİ SİL) ---
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            toggleBulkDeleteBtn();
        });
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleBulkDeleteBtn);
        });
    }

    function toggleBulkDeleteBtn() {
        const anyChecked = Array.from(rowCheckboxes).some(c => c.checked);
        bulkDeleteBtn.classList.toggle('d-none', !anyChecked);
    }

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = Array.from(rowCheckboxes)
                .filter(c => c.checked)
                .map(c => c.value);
            const model = this.dataset.model;
            const url = `/admin/${model}/bulk-delete`;

            Swal.fire({
                title: 'Emin misiniz?',
                text: `${selectedIds.length} adet kaydı silmek üzeresiniz. Bu işlem geri alınamaz!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, hepsini sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    selectedIds.forEach(id => {
                        formData.append('ids[]', id);
                    });

                    fetchRequest(url, { method: 'POST', body: formData })
                        .then(data => {
                            if (data && data.success) {
                                iziToast.success({ title: 'Başarılı!', message: data.message, position: 'topRight' });
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
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
            const form = editModalEl.querySelector('#editForm');

            form.action = updateUrl;
            form.reset();

            const imagePreviewContainer = form.querySelector('#image-preview-container');
            if (imagePreviewContainer) imagePreviewContainer.style.display = 'none';

            const data = await fetchRequest(fetchUrl, { method: 'GET' });

            if (data) {
                // Bu döngü, `name` attribute'u veritabanı sütunuyla eşleşen tüm alanları doldurur.
                Object.keys(data).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) {
                        if (field.type === 'checkbox') {
                            field.checked = data[key] == 1;
                        } else {
                            field.value = data[key];
                        }
                    }
                });

                if (data.image_full_url && imagePreviewContainer) {
                    const imagePreview = form.querySelector('#image-preview');
                    imagePreview.src = data.image_full_url;
                    imagePreviewContainer.style.display = 'block';
                }

                setTimeout(() => {
                    const editor = tinymce.get('edit_content');
                    if (editor) {
                        editor.setContent(data.content || '');
                    }
                }, 150);
            }
        });
    }

    // --- 6. AJAX FORM GÖNDERİMİ (CREATE & UPDATE) ---
    async function handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const url = form.action;
        const formData = new FormData(form);

        const editorId = form.id === 'createForm' ? 'create_content' : 'edit_content';
        if (tinymce.get(editorId)) {
            formData.set('content', tinymce.get(editorId).getContent());
        }

        const response = await fetchRequest(url, { method: 'POST', body: formData });

        if (response && response.success) {
            const modalEl = form.closest('.modal');
            bootstrap.Modal.getInstance(modalEl).hide();
            iziToast.success({ title: 'Başarılı!', message: response.message, position: 'topRight' });

            setTimeout(() => window.location.reload(), 1000);
        }
    }

    document.getElementById('createForm')?.addEventListener('submit', handleFormSubmit);
    document.getElementById('editForm')?.addEventListener('submit', handleFormSubmit);

});
