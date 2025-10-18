// public/js/admin/common/forms-handler.js
// Bu dosya, tüm modüller için AJAX form gönderimlerini ve modal yönetimini yapar.

/**
 * AJAX tabanlı formları ve düzenleme modallarını başlatır.
 * @param {object} options - Modüle özel ayarları içeren obje.
 * Örn: { modelName: 'blogs', createFormId: '#createblogForm', ... }
 */
import { syncSummernote } from './summernote-handler.js';
export function initAjaxFormHandlers(options) {
    const { modelName, createFormId, createModalId, editFormId, editModalId, csrfToken } = options;

    // --- YENİ KAYIT FORMU ---
    const createForm = document.querySelector(createFormId);
    if (createForm) {
        createForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            // TinyMCE içeriğini ekle (varsa) - VARLIK KONTROLÜ EKLENDİ
          if (options.editorType === 'summernote') {
            syncSummernote(formId);
        }

            // AJAX isteğini gönder
            submitAjaxForm(this.action, 'POST', formData, csrfToken, createFormId);
        });
    }

    // --- DÜZENLEME FORMU ---
    const editForm = document.querySelector(editFormId);
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            // TinyMCE içeriğini ekle (varsa) - VARLIK KONTROLÜ EKLENDİ
            if (typeof tinymce !== 'undefined') {
                const editEditor = tinymce.get(options.editEditorId || 'editContent');
                if (editEditor) {
                    const fieldName = editEditor.getElement().getAttribute('name');
                    formData.set(fieldName, editEditor.getContent());
                }
            }

            // AJAX isteğini gönder
            submitAjaxForm(this.action, 'POST', formData, csrfToken, editFormId);
        });
    }
}

/**
 * AJAX form gönderimini yapan ana fonksiyon
 */
function submitAjaxForm(url, method, formData, csrfToken, formId) {
    fetch(url, {
        method: method,
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: formData,
    })
        .then(response => response.json()) // Her zaman JSON yanıtı bekle
        .then(data => {
            handleResponse(data, formId);
        })
        .catch(error => {
            console.error('AJAX Hatası:', error);
            iziToast.error({ title: 'Hata', message: 'Sunucuyla iletişim kurulamadı.' });
        });
}

/**
 * Sunucudan gelen yanıta göre bildirim gösterir ve sayfayı yeniler.
 */
function handleResponse(data, formId) {
    const form = document.querySelector(formId);
    const modal = form.closest('.modal');
    const modalInstance = bootstrap.Modal.getInstance(modal);

    if (data.success) {
        iziToast.success({ title: 'Başarılı', message: data.message });
        if (modalInstance) modalInstance.hide();
        setTimeout(() => window.location.reload(), 1500); // 1.5 saniye sonra sayfayı yenile
    } else if (data.errors) {
        // Doğrulama hatalarını göster
        for (const key in data.errors) {
            iziToast.error({ title: 'Hata', message: data.errors[key][0] });
        }
    } else {
        iziToast.error({ title: 'Hata', message: data.message || 'Bilinmeyen bir hata oluştu.' });
    }
}
