// public/js/admin/references/references.js
// Bu dosya, Referanslar sayfasının ana JavaScript mantığını yönetir.

import { initConfirmation } from '../common/sweetalert-handler.js';
import { initStatusSwitch } from '../common/status-handler.js';
import { initSortable } from '../common/sortable-handler.js';
import { initAjaxFormHandlers } from '../common/forms-handler.js';
import { initCheckboxManagement } from '../common/bulk-action-handler.js';

document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 1. MODEL ADI REFERANS OLARAK GÜNCELLENDİ
    const modelName = 'references';

    // 2. AJAX Formları için ayarları tanımla
    const formOptions = {
        // Form ID'leri referans modülüne uygun olarak güncellendi
        createFormId: '#createReferenceForm',
        editFormId: '#editReferenceForm',
        csrfToken: csrfToken
    };
    initAjaxFormHandlers(formOptions);

    // 3. Diğer Ortak İşlevler
    // Sortable: ID ve modelName güncellendi.
    initSortable('referencesTable', modelName, csrfToken);
    // Status Switch: class adı ve modelName güncellendi.
    initStatusSwitch('.reference-status-switch', modelName, csrfToken);
    // Toplu seçim ve buton gösterimi/gizlemesi
    initCheckboxManagement('selectAllCheckbox', '.row-checkbox', 'bulkDeleteBtn');

    // 4. TOPLU SİLME ONAYI
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

    // 5. DÜZENLEME MODALINI DOLDURMA (REFERANSLAR'A ÖZEL)
    // index.blade.php'de bu butona 'openEditModal' class'ı vermiştik.
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            // Modal ID'si güncellendi
            const modal = document.getElementById('editReferenceModal');

            // Verileri butonun data-* özelliklerinden al (index.blade.php'deki yeni alanlar)
            const updateUrl = this.dataset.updateUrl;
            const name = this.dataset.name;           // YENİ ALAN
            const title = this.dataset.title;
            const description = this.dataset.description; // YENİ ALAN
            const websiteUrl = this.dataset.websiteUrl;   // YENİ ALAN
            const imageUrl = this.dataset.imageUrl;

            // Modal içindeki elementleri bul ve doldur (ID'ler _edit_modal.blade.php ile eşleşmeli)
            modal.querySelector('form').action = updateUrl;
            modal.querySelector('#edit_name').value = name || '';         // YENİ ID
            modal.querySelector('#edit_title').value = title || '';
            modal.querySelector('#edit_description').value = description || ''; // YENİ ID
            modal.querySelector('#edit_website_url').value = websiteUrl || ''; // YENİ ID

            // Eski alanlar (subtitle, link, button_text) kaldırıldı.

            // Mevcut görseli gösterme (ID'si index.blade.php'de kullanılmıştı)
            const currentImage = document.getElementById('current_image_preview');
            if (imageUrl) {
                currentImage.src = imageUrl;
                currentImage.closest('.image-preview-container').style.display = 'block';
            } else {
                currentImage.closest('.image-preview-container').style.display = 'none';
            }
        });
    });
});