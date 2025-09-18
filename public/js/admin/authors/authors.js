// public/js/admin/authors/authors.js
// Bu dosya, yazarlar sayfasının ana JavaScript mantığını yönetir.

import { initTinyMCE } from '../common/tinymce-handler.js';
import { initSweetAlert, initConfirmation } from '../common/sweetalert-handler.js';
import { initStatusSwitch } from '../common/status-handler.js';
import { initSortable } from '../common/sortable-handler.js';
import { initAjaxFormHandlers } from '../common/forms-handler.js';
import { initCheckboxManagement } from '../common/bulk-action-handler.js';
// DİKKAT: Artık sorunlu initEditModal'ı import etmiyoruz.

document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const modelName = 'authors';

    // 1. AJAX Formları için ayarları tanımla
    const formOptions = {
        createFormId: '#createAuthorForm',
        editFormId: '#editAuthorForm',
        createEditorId: 'description',
        editEditorId: 'edit_description',
        csrfToken: csrfToken
    };
    initAjaxFormHandlers(formOptions);

    // 2. Diğer Ortak İşlevler (Bunlar doğru çalışıyor)
    initTinyMCE('#description, #edit_description');
    initSortable('authorsTable', modelName, csrfToken);
    initStatusSwitch('.status-switch', modelName, csrfToken);
    initCheckboxManagement('selectAllCheckbox', '.row-checkbox', 'bulkDeleteBtn');
    initSweetAlert('.deleteAuthorForm');
    // 3. TOPLU SİLME ONAYI (EKSİK OLAN KISIM)
    initConfirmation('#bulkDeleteBtn', (button) => {
        const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        const url = `/admin/${modelName}/bulk-delete`;

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ ids: selectedIds })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Silindi!', data.message, 'success').then(() => window.location.reload());
                } else {
                    Swal.fire('Hata!', data.message || 'Bir hata oluştu.', 'error');
                }
            });
    });

    // 3. DÜZENLEME MODALINI DOLDURMA (AUTHORS'A ÖZEL BASİT YÖNTEM)
    // Bu kod, artık ortak bir handler'a güvenmek yerine, bu sayfaya özel olarak çalışır.
    document.querySelectorAll('.edit-author-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = document.getElementById('editAuthorModal');

            // Verileri doğrudan butonun data-* özelliklerinden al
            const updateUrl = this.dataset.updateUrl;
            const title = this.dataset.title;
            const description = this.dataset.description;
            const imageUrl = this.dataset.imageUrl;

            // Modal içindeki elementleri bul ve doldur
            modal.querySelector('form').action = updateUrl;
            modal.querySelector('#edit_title').value = title || '';
            modal.querySelector('#current_image').src = imageUrl || 'https://placehold.co/150x150/EFEFEF/AAAAAA&text=Görsel+Yok';

            const editor = tinymce.get('edit_description');
            if (editor) {
                editor.setContent(description || '');
            }
        });
    });
});

