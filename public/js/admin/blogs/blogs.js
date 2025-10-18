// Bu dosyada diğer handler'ları import etmeye gerek yok, çünkü index.blade.php'de zaten çağırılıyorlar.
// Sadece summernote handler'ı gerekiyor.
import { initSummernote } from './common/summernote-handler.js';

// DOM tamamen yüklendikten sonra çalıştır
document.addEventListener('DOMContentLoaded', function () {
    
    const createModalEl = document.getElementById('createModal');
    const editModalEl = document.getElementById('editModal');

    if (!createModalEl || !editModalEl) {
        return;
    }

    // --- YENİ EKLE MODALI ---
    createModalEl.addEventListener('shown.bs.modal', function () {
        initSummernote('#create_content');
    });
    createModalEl.addEventListener('hidden.bs.modal', function () {
        // jQuery'nin varlığını ve summernote eklentisinin yüklendiğini kontrol et
        if (window.jQuery && $.fn.summernote) {
            $('#create_content').summernote('destroy');
        }
    });

    // --- DÜZENLEME MODALI ---
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const blogId = this.dataset.id;
            const updateUrl = this.dataset.updateUrl;
            
            const editForm = editModalEl.querySelector('#editForm');
            editForm.action = updateUrl;
            
            // Veri çekme URL'sini `data-fetch-url` attribute'undan alıyoruz
            fetch(this.dataset.fetchUrl)
                .then(response => response.json())
                .then(data => {
                    // Form alanlarını doldur
                    editForm.querySelector('[name="title"]').value = data.title || '';
                    editForm.querySelector('[name="category_id"]').value = data.category_id || '';
                    editForm.querySelector('[name="author_id"]').value = data.author_id || '';
                    editForm.querySelector('[name="gallery_id"]').value = data.gallery_id || '';
                    editForm.querySelector('[name="meta_description"]').value = data.meta_description || '';
                    editForm.querySelector('[name="meta_keywords"]').value = data.meta_keywords || '';
                    editForm.querySelector('[name="is_featured"]').checked = (data.is_featured == 1);

                    const content = data.content || '';

                    // Modal tamamen açıldığında editörü başlat ve içeriği doldur
                    editModalEl.addEventListener('shown.bs.modal', function () {
                        initSummernote('#edit_content');
                        $('#edit_content').summernote('code', content);
                    }, { once: true });
                    
                    // Modal kapandığında editörü yok et
                    editModalEl.addEventListener('hidden.bs.modal', function () {
                         if (window.jQuery && $.fn.summernote) {
                            $('#edit_content').summernote('destroy');
                        }
                    }, { once: true });
                })
                .catch(error => console.error('Düzenleme verisi çekilirken hata:', error));
        });
    });
    
    // --- FORM GÖNDERME SENKRONİZASYONU ---
    document.querySelector('#createForm')?.addEventListener('submit', function() {
        if (window.jQuery && $.fn.summernote) {
            $('#create_content').val($('#create_content').summernote('code'));
        }
    });
    
    document.querySelector('#editForm')?.addEventListener('submit', function() {
        if (window.jQuery && $.fn.summernote) {
            $('#edit_content').val($('#edit_content').summernote('code'));
        }
    });
});