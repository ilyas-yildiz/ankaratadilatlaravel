<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Author;
use App\Models\AiChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class BlogController extends BaseResourceController
{
    protected function getModelInstance(): Model { return new Blog(); }
    protected function getViewPath(): string { return 'blogs'; }
    protected function getRouteName(): string { return 'blogs'; }
    protected function getValidationRules(Request $request, $id = null): array {
        $rules = [
            'title' => 'required|string|max:255|unique:blogs,title,' . $id,
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'nullable|exists:authors,id',
            'gallery_id' => 'nullable|exists:galleries,id',
            'content' => 'nullable|string',
            'is_featured' => 'required|boolean',
            'slug' => 'nullable|string',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255'
        ];
        $rules['image'] = $id ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240';
        return $rules;
    }
    protected function getImageFieldName(): ?string { return 'image'; }
    protected function getImagePath(): ?string { return 'blog-images'; }
    protected function getImageSizes(): array { return ['1460x730', '730x365', '365x182', '128x128']; }
    protected function getAdditionalDataForForms(): array {
        return [
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'galleries' => Gallery::where('status', true)->orderBy('name')->get(),
            'authors' => Author::where('status', true)->orderBy('title')->get(),
        ];
    }

    // GÜNCELLENEN METOT: BaseController ile uyumlu hale getirildi.
    /**
     * Belirtilen kaynağı düzenlemek için verileri JSON olarak döndürür.
     * İmza, BaseResourceController::edit($id) ile uyumludur.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        // Model'i $id kullanarak manuel olarak buluyoruz.
        $blog = Blog::findOrFail($id);
        
        // Veriyi JSON olarak döndürüyoruz.
        return response()->json(['item' => $blog]);
    }


    public function store(Request $request) {
        $request->merge(['slug' => Str::slug($request->title)]);
        return parent::store($request);
    }
    public function update(Request $request, $id) {
        $item = $this->model->findOrFail($id);
        if ($request->title !== $item->title) {
            $request->merge(['slug' => Str::slug($request->title)]);
        }
        return parent::update($request, $id);
    }

    // --- AI METODLARI ---
    public function createWithAi()
    {
        $chatHistory = session('ai_chat_history', []);
        $savedChats = Auth::user()->aiChats()->latest()->get();
        $activeChatId = session('active_chat_id', null);
        return view('admin.blogs.create_with_ai', compact('chatHistory', 'savedChats', 'activeChatId'));
    }

    public function startNewAiChat()
    {
        session()->forget(['ai_chat_history', 'active_chat_id']);
        return redirect()->route('admin.blogs.createWithAi');
    }

    public function generateWithAi(Request $request)
    {
        $request->validate(['message' => 'required|string|max:4000']);
        $newMessage = $request->input('message');
        $chatHistory = session('ai_chat_history', []);
        $chatHistory[] = ['role' => 'user', 'parts' => [['text' => $newMessage]]];
        $systemInstruction = "Sen, bir blog yazısı asistanısın. Kullanıcı ile sohbet ederek bir blog yazısı oluştur. Kullanıcı yazının son halini istediğinde, bir başlığı onayladığında veya yazıyı güncellemeni istediğinde, cevabını SADECE ve SADECE şu formatta ver, başka hiçbir yorum, giriş veya sonuç cümlesi ekleme: [BAŞLIK]: ... [İÇERİK]: ...";

        try {
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) { throw new Exception('Gemini API anahtarı (.env) dosyasında bulunamadı.'); }
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

            $client = new Client();
            $response = $client->post($url, [
                'json' => [
                    'contents' => $chatHistory,
                    'systemInstruction' => ['parts' => [['text' => $systemInstruction]]],
                    'generationConfig' => ['maxOutputTokens' => 8192]
                ]
            ]);

            $body = json_decode((string)$response->getBody(), true);
            $generatedText = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if (!$generatedText) { throw new Exception('API\'den geçerli bir metin yanıtı alınamadı.');}

            $chatHistory[] = ['role' => 'model', 'parts' => [['text' => $generatedText]]];
            session(['ai_chat_history' => $chatHistory]);

            if (session()->has('active_chat_id')) {
                $chat = AiChat::find(session('active_chat_id'));
                if ($chat && $chat->user_id === Auth::id()) {
                    $chat->history = $chatHistory;
                    $chat->save();
                }
            }

            return response()->json(['success' => true, 'reply' => $generatedText]);

        } catch (Exception $e) {
            Log::error('Yapay Zeka sohbet hatası: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Yapay zeka ile iletişim kurulurken bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }

    public function saveCurrentChat(Request $request)
    {
        $request->validate(['title' => 'required|string|max:100']);
        $chatHistory = session('ai_chat_history', []);
        if (count($chatHistory) < 1) {
            return response()->json(['success' => false, 'message' => 'Kaydedilecek bir sohbet bulunamadı.'], 400);
        }
        $newChat = Auth::user()->aiChats()->create([
            'title' => $request->input('title'),
            'history' => $chatHistory
        ]);
        session(['active_chat_id' => $newChat->id]);
        return response()->json(['success' => true, 'message' => 'Sohbet başarıyla kaydedildi!']);
    }

    public function loadChat(AiChat $chat)
    {
        if ($chat->user_id !== Auth::id()) {
            abort(403, 'Bu işleme yetkiniz yok.');
        }
        session(['ai_chat_history' => $chat->history, 'active_chat_id' => $chat->id]);
        return redirect()->route('admin.blogs.createWithAi');
    }

    public function destroyAiChat(AiChat $chat)
    {
        if ($chat->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Bu işleme yetkiniz yok.'], 403);
        }
        $chat->delete();
        return response()->json(['success' => true, 'message' => 'Sohbet başarıyla silindi.']);
    }

    public function prepareFromAi(Request $request)
    {
        $request->validate(['title' => 'required|string', 'content' => 'required|string']);
        $title = $request->input('title');
        $contentHtml = $request->input('content');
        $metaKeywords = 'Anahtar kelimeler üretilemedi.';
        $metaDescription = 'Açıklama üretilemedi.';

        try {
            $seoPrompt = "Aşağıdaki blog yazısı için SEO meta description (maksimum 160 karakter) ve meta keywords (virgülle ayrılmış 5-10 adet) oluştur.\n\nBAŞLIK: {$title}\n\nİÇERİK:\n{$contentHtml}\n\n---\nCevabını SADECE şu formatta ver, başka hiçbir yorum ekleme:\n[DESCRIPTION]: ...\n[KEYWORDS]: ...";
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) { throw new Exception('Gemini API anahtarı (.env) dosyasında bulunamadı.'); }
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";
            $client = new Client();
            $response = $client->post($url, ['json' => ['contents' => [['parts' => [['text' => $seoPrompt]]]]]]);
            $body = json_decode((string)$response->getBody(), true);
            $generatedText = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if ($generatedText) {
                if (preg_match('/\[DESCRIPTION\]:([\s\S]*?)(\n\[KEYWORDS\]:|$)/im', $generatedText, $descMatches)) {
                    $metaDescription = trim($descMatches[1]);
                }
                if (preg_match('/\[KEYWORDS\]:([\s\S]*)/im', $generatedText, $keywMatches)) {
                    $metaKeywords = trim($keywMatches[1]);
                }
            }
        } catch (Exception $e) {
            Log::error('AI SEO verisi oluşturma hatası: ' . $e->getMessage());
        }
        return redirect()->route('admin.blogs.index')
            ->with('ai_generated_data', [
                'title' => $title,
                'content' => $contentHtml,
                'meta_description' => $metaDescription,
                'meta_keywords' => $metaKeywords
            ]);
    }
}