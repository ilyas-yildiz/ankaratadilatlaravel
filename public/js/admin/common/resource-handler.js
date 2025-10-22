document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // --- LFM ve Summernote Entegrasyonu ---
    var summernote_context;
    window.SetUrl = function (items) {
        if (summernote_context) {
            items.forEach(function (item) {
                // Summernote'un kendi 'insertImage' fonksiyonunu kullanıyoruz
                summernote_context.invoke('insertImage', item.url);
            });
            summernote_context = null; // Referansı temizle
        }
    };

    // --- Summernote Başlatma Fonksiyonu ---
    function initSummernote() {
        if (typeof $ === 'undefined' || !$.fn.summernote) {
            console.error('jQuery veya Summernote kütüphanesi yüklenemedi.');
            return;
        }
        $('.summernote-editor').summernote({
            lang: 'tr-TR',
            height: 350,
            focus: true,
            dialogsInBody: true, // Modal içi sorunlar için önemli
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'lfm', 'video', 'hr']], // LFM butonu burada
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            buttons: {
                lfm: function (context) { // Laravel File Manager butonu
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<i class="note-icon-picture"></i> Dosya Yöneticisi',
                        tooltip: 'Dosya Yöneticisi',
                        click: function () {
                            summernote_context = context; // Hangi editörden tıklandığını sakla
                            let route_prefix = '/admin/laravel-filemanager'; // LFM rotası
                            window.open(route_prefix + '?type=image', 'FileManager', 'width=900,height=600');
                        }
                    });
                    return button.render();
                }
            },
            callbacks: {
                // Bootstrap 5 modal kapatma uyumluluğu
                onDialogCreate: function (dialog) {
                    const closeBtn = $(dialog).find('.close');
                    closeBtn.removeAttr('data-dismiss').attr('data-bs-dismiss', 'modal');
                }
            }
        });
    }

    // Sayfa yüklendiğinde Summernote'u başlat
    initSummernote();

    // --- Genel AJAX Fonksiyonu ---
    async function fetchRequest(url, options) {
        options.headers = { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', ...options.headers };
        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                // Hata durumunda JSON'dan mesajı almaya çalış
                let errorData = {};
                try {
                    errorData = await response.json();
                } catch (e) {
                    // JSON yoksa genel hata
                    errorData.message = `HTTP Hatası: ${response.status} ${response.statusText}`;
                }
                throw new Error(errorData.message || 'Bilinmeyen bir sunucu hatası oluştu.');
            }
            // Başarılıysa JSON verisini döndür
            return await response.json();
        } catch (error) {
            // Hata logunu konsola yazdır (geliştirme için faydalı olabilir)
            console.error('Fetch Error:', error);
            iziToast.error({ title: 'Hata!', message: error.message, position: 'topRight' });
            return null; // Hata durumunda null döndür
        }
    }

    // --- 1. SÜRÜKLE-BIRAK İLE SIRALAMA ---
    const sortableList = document.getElementById('sortable-list');
    if (sortableList) {
        new Sortable(sortableList, {
            handle: '.handle-cell', // Taşıma ikonu
            animation: 150,
            onUpdate: function (evt) {
                // Sıralama değiştiğinde çalışır
                const order = Array.from(sortableList.querySelectorAll('tr')).map((row, index) => ({ id: row.dataset.id, position: index }));
                const url = sortableList.dataset.updateUrl; // Sıralama URL'si tablodan alınır
                const formData = new FormData();
                order.forEach((item, index) => {
                    formData.append(`order[${index}][id]`, item.id);
                    formData.append(`order[${index}][position]`, item.position);
                });
                fetchRequest(url, { method: 'POST', body: formData }).then(data => {
                    if (data && data.success) {
                        iziToast.success({ title: 'Başarılı!', message: data.message || 'Sıralama güncellendi.', position: 'topRight' });
                    }
                    // Hata durumunda fetchRequest zaten bildirim gösterir
                });
            }
        });
    }

    // --- 2. DURUM DEĞİŞTİRME (SWITCH) ---
    document.querySelectorAll('.status-switch').forEach(switchEl => {
        switchEl.addEventListener('change', function () {
            const id = this.dataset.id;
            const model = this.dataset.model; // Hangi model olduğu (blogs, services vb.)
            const status = this.checked;
            const url = `/admin/${model}/${id}/status`; // Ortak status rotası
            const formData = new FormData();
            formData.append('status', status ? '1' : '0');
            formData.append('_method', 'PATCH'); // Laravel'e PATCH isteği olduğunu söyle
            fetchRequest(url, { method: 'POST', body: formData }).then(data => {
                if (data && data.success) {
                    iziToast.success({ title: 'Başarılı!', message: data.message || 'Durum güncellendi.', position: 'topRight' });
                } else if (data === null) {
                    // Hata durumunda switch'i geri al (fetchRequest null döndürür)
                    this.checked = !status;
                }
            });
        });
    });

    // --- 3. TEKLİ SİLME ONAYI ---
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Formun normal gönderimini engelle
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
                    this.submit(); // Onaylanırsa formu gönder
                }
            });
        });
    });

    // --- 4. TOPLU İŞLEMLER (SEÇİLENLERİ SİL) ---
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    if (selectAllCheckbox && rowCheckboxes.length > 0 && bulkDeleteBtn) { // Elemanların varlığını kontrol et
        const toggleBulkDeleteBtn = () => {
            const anyChecked = Array.from(rowCheckboxes).some(c => c.checked);
            bulkDeleteBtn.classList.toggle('d-none', !anyChecked); // Seçili varsa butonu göster
        };
        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            toggleBulkDeleteBtn();
        });
        rowCheckboxes.forEach(checkbox => checkbox.addEventListener('change', toggleBulkDeleteBtn));

        bulkDeleteBtn.addEventListener('click', function () {
            const selectedIds = Array.from(rowCheckboxes).filter(c => c.checked).map(c => c.value);
            const model = this.dataset.model;
            const url = `/admin/${model}/bulk-delete`; // Ortak toplu silme rotası

            Swal.fire({
                title: 'Emin misiniz?',
                text: `${selectedIds.length} adet kaydı silmek üzeresiniz. Bu işlem geri alınamaz!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Silme için kırmızı buton
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, hepsini sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    selectedIds.forEach(id => formData.append('ids[]', id));
                    fetchRequest(url, { method: 'POST', body: formData }).then(data => {
                        if (data && data.success) {
                            iziToast.success({ title: 'Başarılı!', message: data.message, position: 'topRight' });
                            setTimeout(() => window.location.reload(), 1500); // Sayfayı yenile
                        }
                        // Hata durumunda fetchRequest zaten bildirim gösterir
                    });
                }
            });
        });
    }

    // --- 5. DÜZENLEME MODALI VERİ DOLDURMA (Sketch Görsel Eklendi) ---
    const editModalEl = document.getElementById('editModal');
    if (editModalEl) {
        // Modal açılmaya başladığında çalışır (veriyi çekmek için ideal)
        editModalEl.addEventListener('show.bs.modal', async function (event) {
            const button = event.relatedTarget; // Modalı açan buton
            const fetchUrl = button.dataset.fetchUrl; // Veri çekilecek URL
            const updateUrl = button.dataset.updateUrl; // Formun action URL'si
            const form = editModalEl.querySelector('#editForm'); // Modal içindeki form
            form.action = updateUrl; // Formun action'ını ayarla
            form.reset(); // Formu temizle

            // Ana görsel önizlemesini gizle ve temizle
            const imagePreviewContainer = form.querySelector('#image-preview-container');
            if (imagePreviewContainer) imagePreviewContainer.style.display = 'none';
            const imagePreview = form.querySelector('#image-preview');
            if (imagePreview) imagePreview.src = '';

            // Sketch görsel önizlemesini gizle ve temizle
            const sketchPreviewContainer = form.querySelector('#image_sketch-preview-container'); // Sketch container ID'si
            if (sketchPreviewContainer) sketchPreviewContainer.style.display = 'none';
            const sketchPreview = form.querySelector('#image_sketch-preview'); // Sketch image ID'si
            if (sketchPreview) sketchPreview.src = '';

            // Summernote'u sıfırla (eğer varsa)
            const editorTextarea = form.querySelector('.summernote-editor');
            if (editorTextarea && $.fn.summernote) {
                $('#' + editorTextarea.id).summernote('reset');
            }

            // Sunucudan veriyi çek
            const data = await fetchRequest(fetchUrl, { method: 'GET' });

            // Veri başarıyla geldiyse ve 'item' anahtarı varsa formu doldur
            if (data && data.item) {
                const item = data.item;

                // Gelen verideki her anahtar için formu doldur
                Object.keys(item).forEach(key => {
                    // YENİ: image_sketch_url'i bu döngüde atla, aşağıda özel olarak ele alacağız
                    if (key === 'image_sketch_url' || key === 'image_sketch_full_url') return;

                    // Checkbox/Switch için özel seçici
                    const field = form.querySelector(`input[type="checkbox"][name="${key}"]`) || form.querySelector(`[name="${key}"]`);

                    if (field) {
                        if (field.classList.contains('summernote-editor')) {
                            // Summernote'u aşağıda dolduracağız, atla
                        } else if (field.type === 'date') {
                            // Tarih inputunu YYYY-MM-DD formatıyla doldur
                            if (item[key] && typeof item[key] === 'string') {
                                field.value = item[key].substring(0, 10);
                            } else {
                                field.value = null;
                            }
                        } else if (field.type === 'checkbox') {
                            field.checked = item[key] == 1; // Değer 1 ise işaretle
                        } else if (field.tagName === 'SELECT') {
                            field.value = item[key]; // Select kutusunu doldur
                        } else {
                            field.value = item[key]; // Diğer input/textarea'ları doldur
                        }
                    }
                });

                // Ana görsel önizlemesini ayarla (eğer URL varsa)
                if (item.image_full_url && imagePreviewContainer && imagePreview) {
                    imagePreview.src = item.image_full_url;
                    imagePreviewContainer.style.display = 'block'; // Görseli göster
                }

                // Sketch görsel önizlemesini ayarla (eğer URL varsa)
                if (item.image_sketch_full_url && sketchPreviewContainer && sketchPreview) {
                    sketchPreview.src = item.image_sketch_full_url;
                    sketchPreviewContainer.style.display = 'block'; // Sketch görselini göster
                }

                // Summernote içeriğini ayarla (eğer varsa ve veri 'content' anahtarında ise)
                if (editorTextarea && item.content !== undefined && $.fn.summernote) {
                    $('#' + editorTextarea.id).summernote('code', item.content);
                }
            }
        });
    }

    // --- 6. AJAX FORM GÖNDERİMİ (CREATE & UPDATE) ---
    async function handleFormSubmit(e) {
        e.preventDefault(); // Sayfanın yenilenmesini engelle
        const form = e.target;
        const url = form.action;
        const formData = new FormData(form); // Formdaki tüm verileri al

        // Summernote editörünün içeriğini formData'ya ekle
        const summernoteTextarea = form.querySelector('.summernote-editor[name="content"]');
        if (summernoteTextarea && $.fn.summernote) {
            const editorId = '#' + summernoteTextarea.id;
            formData.set('content', $(editorId).summernote('code')); // 'content' adıyla ekle
        }

        // AJAX isteğini gönder
        const response = await fetchRequest(url, {
            method: 'POST', // PUT/PATCH için _method gizli alanı formda olmalı
            body: formData
        });

        // Başarılıysa modalı kapat, bildirim göster ve sayfayı yenile
        if (response && response.success) {
            const modalEl = form.closest('.modal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
            iziToast.success({ title: 'Başarılı!', message: response.message, position: 'topRight' });
            setTimeout(() => window.location.reload(), 1000); // 1 saniye sonra yenile
        }
        // Hata durumunda fetchRequest zaten bildirim gösterir
    }

    // Create ve Edit formlarının submit olayını dinle
    document.getElementById('createForm')?.addEventListener('submit', handleFormSubmit);
    document.getElementById('editForm')?.addEventListener('submit', handleFormSubmit);

    // Modal kapandığında Summernote içeriğini temizle
    $('#createModal, #editModal').on('hidden.bs.modal', function () {
        const summernoteTextarea = $(this).find('.summernote-editor');
        if (summernoteTextarea.length && $.fn.summernote) {
            // Summernote'u sıfırla
            $('#' + summernoteTextarea.attr('id')).summernote('reset');
        }
    });
});