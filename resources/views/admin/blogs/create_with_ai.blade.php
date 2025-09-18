@extends('admin.layouts.app')

@section('title', 'AI ile Blog Yazısı Oluştur')

@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

{{-- YENİ: Görsel iyileştirmeler için özel CSS stilleri --}}
@push('styles')
    <style>
        #chat-wrapper { height: 70vh; }
        #chat-messages { background-color: #f1f5f9; }
        .avatar { height: 40px !important; width: 40px !important; flex-shrink: 0 !important; border-radius: 50% !important; }
        .prose-styles h1, .prose-styles h2, .prose-styles h3, .prose-styles h4 { margin-top: 1em; margin-bottom: 0.5em; font-weight: 600; line-height: 1.3; }
        .prose-styles h1 { font-size: 1.5rem; } .prose-styles h2 { font-size: 1.25rem; } .prose-styles h3 { font-size: 1.1rem; }
        .prose-styles p { margin-bottom: 1em; }
        .prose-styles ul, .prose-styles ol { padding-left: 1.5em; margin-bottom: 1em; }
        .prose-styles ul { list-style-type: disc; } .prose-styles ol { list-style-type: decimal; }
        .prose-styles li { margin-bottom: 0.5em; }
        .chat-item-actions { opacity: 0; transition: opacity 0.2s ease-in-out; }
        .list-group-item:hover .chat-item-actions, .list-group-item.active .chat-item-actions { opacity: 1; }
        #saved-chats-sidebar .list-group-item.active {
            background-color: #e9ecef !important; /* Soft gri tonu */
            color: #212529 !important; /* Koyu metin rengi */
            border-color: #dee2e6 !important;
            font-weight: 600 !important;
        }
    </style>
@endpush


@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">AI Yazı Asistanı</h4>
                <div class="d-flex gap-2 align-items-center">
                    @if(isset($activeChatId) && $savedChats->find($activeChatId))
                        <span class="badge bg-light text-success border border-success">
                            <i class="ri-checkbox-circle-fill"></i> Bu sohbet otomatik kaydediliyor
                        </span>
                    @else
                        <button class="btn btn-success" id="saveChatBtn"><i class="ri-save-line align-bottom me-1"></i> Sohbeti Kaydet</button>
                    @endif
                    <a href="{{ route('admin.blogs.startNewAiChat') }}" class="btn btn-info"><i class="ri-add-circle-line align-bottom me-1"></i> Yeni Sohbet</a>
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary"><i class="ri-arrow-left-line align-bottom me-1"></i> Geri Dön</a>
                </div>
            </div><!-- end card header -->

            <div class="card-body p-0">
                <div id="chat-wrapper" class="d-flex flex-row">
                    <div id="saved-chats-sidebar" class="d-none d-lg-flex flex-column border-end" style="width: 280px;">
                        <div class="p-3 border-bottom"><h6 class="mb-0">En Son</h6></div>
                        <div class="overflow-auto">
                            <div class="list-group list-group-flush">
                                @forelse($savedChats as $chat)
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center text-truncate pe-2 {{ (isset($activeChatId) && $activeChatId == $chat->id) ? 'active' : '' }}">
                                        <a href="{{ route('admin.blogs.loadAiChat', $chat->id) }}" class="text-decoration-none text-body flex-grow-1 me-2 text-truncate" title="{{ $chat->title }}">{{ $chat->title }}</a>
                                        <div class="chat-item-actions flex-shrink-0">
                                            <button class="btn btn-sm btn-outline-danger delete-chat-btn py-0 px-1" data-id="{{ $chat->id }}" data-url="{{ route('admin.blogs.destroyAiChat', $chat->id) }}"><i class="ri-delete-bin-line"></i></button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-3 text-muted text-center small">Hiç kayıtlı sohbet yok.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div id="chat-container" class="d-flex flex-column flex-grow-1">
                        <div id="chat-messages" class="flex-grow-1 p-3 p-lg-4 d-flex flex-column gap-4 overflow-auto"></div>
                        <div class="p-3 bg-light border-top">
                            <div class="d-flex align-items-center gap-2">
                                <textarea id="userInput" class="form-control flex-grow-1" rows="1" placeholder="Mesajınızı buraya yazın..."></textarea>
                                <button id="sendButton" class="btn btn-primary h-100">
                                    <i class="ri-send-plane-2-fill ri-lg"></i>
                                    <span class="ms-1 d-none d-sm-inline">Gönder</span>
                                </button>
                            </div>
                            <div id="quick-prompts" class="mt-2 d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary quick-prompt-btn" data-prompt="Şu ana kadarki sohbetimizi temel alarak, son konuştuğumuz konu hakkında bir blog yazısı oluştur. Cevabını SADECE ve SADECE şu formatta ver, başka hiçbir yorum, giriş veya sonuç cümlesi ekleme: [BAŞLIK]: ... [İÇERİK]: ...">
                                    <i class="ri-article-line me-1"></i> Final Blog Yazısını Oluştur
                                </button>
                                <button class="btn btn-sm btn-outline-secondary quick-prompt-btn" data-prompt="Az önceki blog yazısı için 5 tane alternatif başlık önerisi sun.">
                                    <i class="ri-heading me-1"></i> Alternatif Başlıklar Öner
                                </button>
                                <button class="btn btn-sm btn-outline-secondary quick-prompt-btn" data-prompt="Yazıyı daha akıcı hale getir ve paragrafları geliştir.">
                                    <i class="ri-magic-line me-1"></i> Yazıyı Geliştir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sendButton = document.getElementById('sendButton');
            const saveChatBtn = document.getElementById('saveChatBtn');
            const userInput = document.getElementById('userInput');
            const messagesContainer = document.getElementById('chat-messages');
            const savedChatsSidebar = document.getElementById('saved-chats-sidebar');
            const quickPromptsContainer = document.getElementById('quick-prompts');
            const initialChatHistory = {!! json_encode($chatHistory ?? []) !!};
            const converter = new showdown.Converter({ tables: true, strikethrough: true, tasklists: true, simpleLineBreaks: true });

            function loadInitialHistory() {
                messagesContainer.innerHTML = '';
                if (initialChatHistory.length === 0) {
                    appendMessage("Merhaba! Nasıl bir blog yazısı oluşturmak istersiniz? Lütfen konuyu veya bir başlık fikrini yazın.", 'model_init');
                } else {
                    initialChatHistory.forEach(message => {
                        appendMessage(message.parts[0].text, message.role);
                    });
                }
            }

            if(saveChatBtn) {
                saveChatBtn.addEventListener('click', function() {
                    const title = prompt("Bu sohbet için bir başlık girin:", "Yeni Blog Yazısı Fikri");
                    if (title) {
                        saveCurrentChat(title);
                    }
                });
            }

            async function saveCurrentChat(title) {
                try {
                    const response = await fetch("{{ route('admin.blogs.saveAiChat') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ title: title })
                    });
                    const data = await response.json();
                    if (response.ok && data.success) {
                        iziToast.success({ title: 'Başarılı!', message: data.message, position: 'topRight' });
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        throw new Error(data.message || 'Sohbet kaydedilemedi.');
                    }
                } catch (error) {
                    iziToast.error({ title: 'Hata!', message: error.message, position: 'topRight' });
                }
            }

            if (savedChatsSidebar) {
                savedChatsSidebar.addEventListener('click', function(e) {
                    const deleteButton = e.target.closest('.delete-chat-btn');
                    if (!deleteButton) return;
                    e.preventDefault();
                    const deleteUrl = deleteButton.dataset.url;
                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Bu sohbet kalıcı olarak silinecek!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Evet, sil!',
                        cancelButtonText: 'Vazgeç'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteChat(deleteUrl, deleteButton);
                        }
                    });
                });
            }

            async function deleteChat(url, buttonEl) {
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    if (response.ok && data.success) {
                        iziToast.success({ title: 'Başarılı!', message: data.message, position: 'topRight' });
                        buttonEl.closest('.list-group-item').remove();
                    } else {
                        throw new Error(data.message || 'Sohbet silinemedi.');
                    }
                } catch (error) {
                    iziToast.error({ title: 'Hata!', message: error.message, position: 'topRight' });
                }
            }

            if (quickPromptsContainer) {
                quickPromptsContainer.addEventListener('click', function(e) {
                    const button = e.target.closest('.quick-prompt-btn');
                    if (button && button.dataset.prompt) {
                        const staticPrompt = button.dataset.prompt;
                        appendMessage(staticPrompt, 'user');
                        toggleLoading(true);
                        callAIAssistant(staticPrompt);
                    }
                });
            }

            sendButton.addEventListener('click', sendMessage);
            userInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            messagesContainer.addEventListener('click', function(e) {
                const button = e.target.closest('.use-content-btn');
                if (button) {
                    const rawContentFromButton = decodeURIComponent(button.dataset.content);
                    prepareAndRedirect(rawContentFromButton);
                }
            });

            function sendMessage() {
                const messageText = userInput.value.trim();
                if (messageText === '') return;
                appendMessage(messageText, 'user');
                userInput.value = '';
                toggleLoading(true);
                callAIAssistant(messageText);
            }

            function isFullArticle(text) {
                if (!text) return false;
                return text.match(/\[BAŞLIK\]/i) && text.match(/\[İÇERİK\]/i);
            }

            function appendMessage(text, role) {
                const messageRow = document.createElement('div');
                messageRow.className = 'd-flex align-items-start gap-3';
                const isArticle = isFullArticle(text);

                if (role === 'user') {
                    messageRow.classList.add('justify-content-end');
                    messageRow.innerHTML = `
                        <div class="p-3 rounded shadow-sm bg-light" style="max-width: 80%;">
                            <p class="fw-bold mb-1">Siz</p>
                            <p class="mb-0">${text.replace(/\n/g, '<br>')}</p>
                        </div>
                        <div class="avatar bg-dark text-white d-flex align-items-center justify-content-center border border-3 border-primary">
                            <i class="ri-user-line fs-5"></i>
                        </div>`;
                } else if (role === 'system_error') {
                    messageRow.innerHTML = `
                        <div class="avatar bg-danger text-white d-flex align-items-center justify-content-center"><i class="ri-error-warning-line fs-5"></i></div>
                        <div class="p-3 rounded shadow-sm bg-danger-subtle text-danger-emphasis border border-danger-subtle" style="max-width: 80%;">
                            <p class="fw-bold text-danger">Sistem Mesajı</p>
                            <p class="mb-0">${text}</p>
                        </div>`;
                } else {
                    const contentHtml = converter.makeHtml(text);
                    let useButtonHtml = '';
                    if (isArticle) {
                        useButtonHtml = `<div class="mt-3 pt-3 border-top"><button class="use-content-btn btn btn-sm btn-success" data-content="${encodeURIComponent(text)}"><i class="ri-add-line"></i> Bu İçeriği Yazıya Aktar</button></div>`;
                    }
                    messageRow.innerHTML = `
                        <div class="avatar bg-dark bg-opacity-75 text-white d-flex align-items-center justify-content-center border border-3 border-success"><i class="ri-robot-2-line fs-5"></i></div>
                        <div class="p-3 rounded shadow-sm bg-white border" style="max-width: 80%;">
                            <p class="fw-bold text-primary">AI Asistanı</p>
                            <div class="prose-styles">${contentHtml}</div>
                            ${useButtonHtml}
                        </div>`;
                }
                messagesContainer.appendChild(messageRow);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function toggleLoading(isLoading) {
                if (isLoading) {
                    sendButton.disabled = true;
                    sendButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
                } else {
                    sendButton.disabled = false;
                    sendButton.innerHTML = `<i class="ri-send-plane-2-fill ri-lg"></i> <span class="ms-1 d-none d-sm-inline">Gönder</span>`;
                }
            }

            function parseArticle(text) {
                if (!text) return { title: 'Başlık Bulunamadı', content: '' };
                const titleMatch = text.match(/\[BAŞLIK\]:([\s\S]*?)(\n\[İÇERİK\]:|$)/im);
                const contentMatch = text.match(/\[İÇERİK\]:([\s\S]*)/im);
                if (titleMatch && contentMatch) return { title: titleMatch[1].trim(), content: contentMatch[1].trim() };
                const markdownHeaderMatch = cleanText.match(/^\s*(#+)\s+(.*)/m);
                if (markdownHeaderMatch) {
                    const title = markdownHeaderMatch[2].trim();
                    const content = cleanText.substring(markdownHeaderMatch[0].length).trim();
                    return { title: title, content: content };
                }
                return { title: 'Başlık Belirlenemedi', content: text };
            };

            function prepareAndRedirect(rawContentFromButton) {
                const parsed = parseArticle(rawContentFromButton);
                const contentHtml = converter.makeHtml(parsed.content);
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.blogs.prepareFromAi') }}";
                form.style.display = 'none';
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                const titleInput = document.createElement('input');
                titleInput.type = 'hidden';
                titleInput.name = 'title';
                titleInput.value = parsed.title;
                form.appendChild(titleInput);
                const contentInput = document.createElement('input');
                contentInput.type = 'hidden';
                contentInput.name = 'content';
                contentInput.value = contentHtml;
                form.appendChild(contentInput);
                document.body.appendChild(form);
                form.submit();
            }

            async function callAIAssistant(message) {
                try {
                    const response = await fetch("{{ route('admin.blogs.generateWithAi') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: message })
                    });
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.error || 'API isteği başarısız oldu.');
                        }
                        if (data.success && data.reply) {
                            appendMessage(data.reply, 'model');
                        }
                    } else {
                        const errorHtml = await response.text();
                        if (errorHtml.toLowerCase().includes('login')) {
                            throw new Error("Oturum zaman aşımına uğradı veya güvenlik anahtarı geçersiz. Sayfa yenileniyor...");
                        } else {
                            const titleMatch = errorHtml.match(/<title>(.*?)<\/title>/);
                            let errorMessage = "Sunucuda beklenmedik bir hata oluştu (JSON yanıtı alınamadı).";
                            if (titleMatch && titleMatch[1]) {
                                errorMessage = `Sunucu Hatası: ${titleMatch[1]}`;
                            }
                            throw new Error(errorMessage);
                        }
                    }
                } catch (error) {
                    console.error("AI Asistanı Hatası:", error.message);
                    if (error.message.includes("Oturum zaman aşımına uğradı")) {
                        iziToast.warning({
                            title: 'Oturum Sona Erdi',
                            message: 'Güvenlik nedeniyle oturumunuz kapatıldı. Lütfen tekrar giriş yapın.',
                            position: 'topRight',
                            timeout: 3500,
                            onClosing: function () {
                                window.location.reload();
                            }
                        });
                    } else {
                        iziToast.error({
                            title: 'Hata!',
                            message: error.message,
                            position: 'topRight'
                        });
                    }
                } finally {
                    toggleLoading(false);
                }
            }

            loadInitialHistory();
        });
    </script>
@endpush

