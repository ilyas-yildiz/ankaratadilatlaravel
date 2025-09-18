// public/js/admin/blogs/blogs.js
// Bu dosya, bloglar sayfasının ana JavaScript mantığını yönetir.
// Tüm ortak işlevleri "common" klasöründen içe aktarır.

import { initTinyMCE } from '../common/tinymce-handler.js';
import { initSweetAlert, initConfirmation } from '../common/sweetalert-handler.js';
import { initStatusSwitch } from '../common/status-handler.js';
import { initSortable } from '../common/sortable-handler.js';
import { initAjaxFormHandlers } from '../common/forms-handler.js';
import { initCheckboxManagement } from '../common/bulk-action-handler.js';

document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const modelName = 'blogs';

    // 1. AJAX Formları için ayarları tanımla
    const formOptions = {
        modelName: modelName,
        createFormId: '#createblogForm',
        editFormId: '#editblogForm',
        createEditorId: 'content',
        editEditorId: 'edit_content', // YENİ ID
        csrfToken: csrfToken
    };

    // 2. ORTAK İŞLEVLERİ BAŞLAT
    initTinyMCE('#content, #edit_content'); // YENİ ID
    initSortable('blogsTable', modelName, csrfToken);
    initStatusSwitch('.status-switch', modelName, csrfToken);
    initCheckboxManagement('selectAllCheckbox', '.row-checkbox', 'bulkDeleteBtn');
    initAjaxFormHandlers(formOptions);

    // 3. TEKLİ ve TOPLU SİLME
    initSweetAlert('.deleteBlogForm');

    // 4. TOPLU SİLME ONAYI (BUTON TABANLI)
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
            })
            .catch(() => Swal.fire('Hata!', 'Sunucuyla iletişim kurulamadı.', 'error'));
    });

    // 4. DÜZENLEME MODALINI DOLDURMA (BLOGLARA ÖZEL ESKİ YÖNTEM)
    // Bu, senin için kritik olan ve çalışan eski forms-handler.js'deki mantıktır.
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const blogId = this.dataset.id;
            const modal = document.getElementById('editblogModal');

            // DÜZELTME: Yeni, standart ID'leri kullanıyoruz.
            modal.querySelector('#editblogForm').action = this.dataset.updateUrl;
            modal.querySelector('#edit_title').value = this.dataset.title; // ESKİ: #editBlogTitleInput
            modal.querySelector('#edit_category_id').value = this.dataset.categoryId; // ESKİ: #editCategoryId
            modal.querySelector('#edit_gallery_id').value = this.dataset.galleryId || ''; // ESKİ: #editGalleryId

            // YENİ EKLENEN KOD: Yazar dropdown'ını ayarla
            modal.querySelector('#edit_author_id').value = this.dataset.authorId || '';

            // YENİ EKLENEN KOD: Manşet switch'ini ayarla
            const featuredSwitch = modal.querySelector('#edit_is_featured');
            if (featuredSwitch) {
                // data-is-featured'dan gelen '1' veya '0' değerine göre
                // switch'in 'checked' durumunu true/false olarak ayarla.
                featuredSwitch.checked = (this.dataset.isFeatured == '1');
            }
            // YENİ EKLEME BİTTİ

            // Görsel önizlemesini ayarla
            const imagePreviewElement = modal.querySelector('#edit_imageUrl'); // ESKİ: #editImagePreviewContainer
            const imageUrl = this.dataset.imageUrl;
            if (imagePreviewElement) {
                imagePreviewElement.src = imageUrl || 'https://placehold.co/150x150/EFEFEF/AAAAAA&text=Görsel+Yok';
            }

            // Büyük 'content' verisini sunucudan ayrı bir istekle çek
            fetch(`/admin/blogs/${blogId}/edit-content`)
                .then(response => response.json())
                .then(data => {
                    const editor = tinymce.get('edit_content'); // ESKİ: editContent
                    if (editor) {
                        editor.setContent(data.content || '');
                    }
                })
                .catch(error => console.error('İçerik çekme hatası:', error));
        });
    });
});
