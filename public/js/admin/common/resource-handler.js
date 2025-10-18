document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var summernote_context;
    window.SetUrl = function (items) {
        if (summernote_context) {
            items.forEach(function (item) {
                summernote_context.invoke('insertImage', item.url);
            });
            summernote_context = null;
        }
    };

    function initSummernote() {
        if (typeof $ === 'undefined' || !$.fn.summernote) {
            console.error('jQuery veya Summernote kütüphanesi yüklenemedi.');
            return;
        }
        $('.summernote-editor').summernote({
            lang: 'tr-TR',
            height: 350,
            focus: true,
            dialogsInBody: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'lfm', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            buttons: {
                lfm: function(context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<i class="note-icon-picture"></i> Dosya Yöneticisi',
                        tooltip: 'Dosya Yöneticisi',
                        click: function() {
                            summernote_context = context;
                            let route_prefix = '/admin/laravel-filemanager';
                            window.open(route_prefix + '?type=image', 'FileManager', 'width=900,height=600');
                        }
                    });
                    return button.render();
                }
            },
            callbacks: {
                onDialogCreate: function(dialog) {
                    const closeBtn = $(dialog).find('.close');
                    closeBtn.removeAttr('data-dismiss').attr('data-bs-dismiss', 'modal');
                }
            }
        });
    }

    initSummernote();

    async function fetchRequest(url, options) {
        options.headers = { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', ...options.headers };
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

    const sortableList = document.getElementById('sortable-list');
    if (sortableList) {
        new Sortable(sortableList, {
            handle: '.handle-cell',
            animation: 150,
            onUpdate: function (evt) {
                const order = Array.from(sortableList.querySelectorAll('tr')).map((row, index) => ({ id: row.dataset.id, position: index }));
                const url = sortableList.dataset.updateUrl;
                const formData = new FormData();
                order.forEach((item, index) => {
                    formData.append(`order[${index}][id]`, item.id);
                    formData.append(`order[${index}][position]`, item.position);
                });
                fetchRequest(url, { method: 'POST', body: formData }).then(data => {
                    if (data && data.success) {
                        iziToast.success({ title: 'Başarılı!', message: data.message || 'Sıralama güncellendi.', position: 'topRight' });
                    }
                });
            }
        });
    }

    document.querySelectorAll('.status-switch').forEach(switchEl => {
        switchEl.addEventListener('change', function () {
            const id = this.dataset.id;
            const model = this.dataset.model;
            const status = this.checked;
            const url = `/admin/${model}/${id}/status`;
            const formData = new FormData();
            formData.append('status', status ? '1' : '0');
            formData.append('_method', 'PATCH');
            fetchRequest(url, { method: 'POST', body: formData }).then(data => {
                if (data && data.success) {
                    iziToast.success({ title: 'Başarılı!', message: 'Durum güncellendi.', position: 'topRight' });
                }
            });
        });
    });

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

    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    if (selectAllCheckbox) {
        const toggleBulkDeleteBtn = () => {
            const anyChecked = Array.from(rowCheckboxes).some(c => c.checked);
            bulkDeleteBtn.classList.toggle('d-none', !anyChecked);
        };
        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            toggleBulkDeleteBtn();
        });
        rowCheckboxes.forEach(checkbox => checkbox.addEventListener('change', toggleBulkDeleteBtn));
    }

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = Array.from(rowCheckboxes).filter(c => c.checked).map(c => c.value);
            const model = this.dataset.model;
            const url = `/admin/${model}/bulk-delete`;

            Swal.fire({
                title: 'Emin misiniz?',
                text: `${selectedIds.length} adet kaydı silmek üzeresiniz. Bu işlem geri alınamaz!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Evet, hepsini sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    selectedIds.forEach(id => formData.append('ids[]', id));
                    fetchRequest(url, { method: 'POST', body: formData }).then(data => {
                        if (data && data.success) {
                            iziToast.success({ title: 'Başarılı!', message: data.message, position: 'topRight' });
                            setTimeout(() => window.location.reload(), 1500);
                        }
                    });
                }
            });
        });
    }

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

            if (data && data.item) {
                const item = data.item;

                Object.keys(item).forEach(key => {
                    // GÜNCELLENDİ: Sadece checkbox/switch için daha spesifik bir seçici kullanıyoruz.
                    const field = form.querySelector(`input[type="checkbox"][name="${key}"]`) || form.querySelector(`[name="${key}"]`);
                    
                    if (field) {
                        if (field.type === 'checkbox') {
                            field.checked = item[key] == 1;
                        } else {
                            field.value = item[key];
                        }
                    }
                });

                if (item.image_full_url && imagePreviewContainer) {
                    form.querySelector('#image-preview').src = item.image_full_url;
                    imagePreviewContainer.style.display = 'block';
                }

                $('#edit_content').summernote('code', item.content || '');
            }
        });
    }

    async function handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const url = form.action;
        const formData = new FormData(form);
        const editorId = form.id === 'createForm' ? '#create_content' : '#edit_content';
        if ($(editorId).length) {
            formData.set('content', $(editorId).summernote('code'));
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