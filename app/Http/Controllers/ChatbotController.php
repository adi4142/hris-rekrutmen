<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\JobVacancie;
use App\JobApplication;
use App\JobApplicant;
use App\Selection;

/**
 * ChatbotController
 * 
 * Controller untuk fitur chatbot AI HR Assistant
 * Menggunakan Google Gemini API untuk menjawab pertanyaan
 */
class ChatbotController extends Controller
{
    /**
     * Tampilkan halaman chatbot
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('applicant.chatbot');
    }

    /**
     * Proses pesan dari chatbot menggunakan Gemini AI
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $request->input('message');
        $user = auth()->user();
        
        // Kumpulkan konteks untuk AI
        $context = $this->gatherContext($user);
        
        // Kirim ke Gemini AI
        $reply = $this->sendToGemini($message, $context, $user);
        
        return response()->json([
            'reply' => $reply,
            'status' => 'success'
        ]);
    }

    /**
     * Proses pesan chatbot untuk Admin/HRD menggunakan Gemini AI
     *  
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminChat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $request->input('message');
        $user = auth()->user();
        
        // Kumpulkan konteks admin
        $context = $this->gatherAdminContext($user);
        
        // Kirim ke Gemini AI
        $reply = $this->sendToGemini($message, $context, $user, 'admin');
        
        return response()->json([
            'reply' => $reply,
            'status' => 'success'
        ]);
    }

    /**
     * Kumpulkan konteks data untuk pelamar
     * 
     * @param mixed $user
     * @return array
     */
    protected function gatherContext($user)
    {
        $context = [];
        
        // Data pelamar
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();
        
        if ($applicant) {
            $context['applicant'] = [
                'name' => $applicant->name,
                'email' => $applicant->email,
                'phone' => $applicant->phone,
                'has_cv' => !empty($applicant->cv_file),
            ];
            
            // Status lamaran terbaru
            $latestApp = JobApplication::with(['jobVacancie'])
                ->where('job_applicant_id', $applicant->job_applicant_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($latestApp) {
                $context['latest_application'] = [
                    'position' => $latestApp->jobVacancie->title ?? 'Tidak tersedia',
                    'status' => $latestApp->status,
                    'applied_at' => $latestApp->created_at->format('d M Y'),
                ];
            }
            
            // Total lamaran
            $context['total_applications'] = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)->count();
        }
        
        // Lowongan tersedia
        $vacancies = JobVacancie::with(['position', 'departement'])
            ->where('status', 'open')
            ->take(5)
            ->get()
            ->map(function($v) {
                return [
                    'title' => $v->title,
                    'department' => $v->departement->name ?? 'Umum',
                    'description' => substr($v->description ?? '', 0, 100),
                ];
            })->toArray();
        
        $context['available_vacancies'] = $vacancies;
        $context['total_vacancies'] = JobVacancie::where('status', 'open')->count();
        
        // Tahapan seleksi
        $selections = Selection::orderBy('created_at')->get()->map(function($s) {
            return $s->name;
        })->toArray();
        
        $context['selection_stages'] = $selections;
        
        return $context;
    }

    /**
     * Kumpulkan konteks data untuk Admin/HRD
     * 
     * @param mixed $user
     * @return array
     */
    protected function gatherAdminContext($user)
    {
        $context = [];
        
        // Statistik rekrutmen
        $context['statistics'] = [
            'total_applicants' => JobApplicant::count(),
            'total_vacancies' => JobVacancie::where('status', 'open')->count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'accepted_applications' => JobApplication::where('status', 'accepted')->count(),
            'rejected_applications' => JobApplication::where('status', 'rejected')->count(),
        ];
        
        // Lamaran terbaru pending
        $pendingApps = JobApplication::with(['jobApplicant', 'jobVacancie'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($app) {
                return [
                    'applicant_name' => $app->jobApplicant->name ?? 'N/A',
                    'position' => $app->jobVacancie->title ?? 'N/A',
                    'applied_at' => $app->created_at->format('d M Y'),
                ];
            })->toArray();
        
        $context['pending_applications'] = $pendingApps;
        
        // Lowongan aktif
        $vacancies = JobVacancie::with(['position', 'departement'])
            ->where('status', 'open')
            ->get()
            ->map(function($v) {
                return [
                    'title' => $v->title,
                    'department' => $v->departement->name ?? 'Umum',
                ];
            })->toArray();
        
        $context['active_vacancies'] = $vacancies;
        
        return $context;
    }

    /**
     * Kirim pesan ke Gemini AI API
     * 
     * @param string $message
     * @param array $context
     * @param mixed $user
     * @param string $role
     * @return string
     */
    protected function sendToGemini($message, $context, $user, $role = 'applicant')
    {
        $apiKey = config('services.gemini.key');
        $apiUrl = config('services.gemini.url');
        
        if (empty($apiKey) || empty($apiUrl)) {
            Log::error('Gemini API key or URL not configured');
            return $this->getFallbackResponse($message, $context, $user, $role);
        }
        
        // Buat system prompt berdasarkan role
        $systemPrompt = $this->buildSystemPrompt($context, $user, $role);
        
        try {
            $response = Http::timeout(30)->post("{$apiUrl}?key={$apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nPertanyaan user: " . $message]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ]
                ]
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'];
                    // Format HTML untuk display
                    $reply = $this->formatReply($reply);
                    return $reply;
                }
            }
            
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return $this->getFallbackResponse($message, $context, $user, $role);
            
        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
            return $this->getFallbackResponse($message, $context, $user, $role);
        }
    }

    /**
     * Buat system prompt untuk Gemini
     * 
     * @param array $context
     * @param mixed $user
     * @param string $role
     * @return string
     */
    protected function buildSystemPrompt($context, $user, $role)
    {
        $contextJson = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        if ($role === 'admin') {
            return <<<PROMPT
    Kamu adalah HR Assistant AI untuk sistem HRIS (Human Resource Information System).
    Kamu membantu Admin/HRD dalam mengelola proses rekrutmen dan kepegawaian.

    IDENTITAS:
    - Nama: HR Assistant
    - Peran: Asisten AI untuk Admin/HRD
    - Bahasa: Bahasa Indonesia yang formal tapi ramah

    KEMAMPUAN:
    - Memberikan informasi statistik rekrutmen
    - Membantu melihat daftar lamaran pending
    - Memberikan saran untuk proses seleksi
    - Menjawab pertanyaan seputar manajemen SDM

    DATA KONTEKS SAAT INI:
    {$contextJson}

    ATURAN:
    1. Selalu jawab dalam Bahasa Indonesia
    2. Berikan informasi yang akurat berdasarkan data konteks
    3. Jika tidak tahu jawabannya, katakan dengan jujur
    4. Gunakan emoji secara wajar untuk membuat percakapan lebih ramah
    5. Format jawaban dengan jelas (gunakan bullet points jika perlu)
    6. Untuk pertanyaan di luar konteks HR, redirect dengan sopan
    7. Jangan berikan informasi sensitif seperti gaji karyawan spesifik

    Nama user saat ini: {$user->name}
    PROMPT;
        }
        
        return <<<PROMPT
    Kamu adalah HR Assistant AI untuk sistem rekrutmen perusahaan.
    Kamu membantu pelamar kerja dalam proses melamar pekerjaan.

    IDENTITAS:
    - Nama: HR Assistant  
    - Peran: Asisten AI untuk Pelamar Kerja
    - Bahasa: Bahasa Indonesia yang ramah dan supportif

    KEMAMPUAN:
    - Memberikan informasi status lamaran
    - Menjelaskan lowongan yang tersedia
    - Memberi tips melamar kerja
    - Menjelaskan tahapan seleksi
    - Menjawab FAQ seputar rekrutmen

    DATA KONTEKS PELAMAR SAAT INI:
    {$contextJson}

    ATURAN:
    1. Selalu jawab dalam Bahasa Indonesia
    2. Berikan informasi yang akurat berdasarkan data konteks
    3. Jika pelamar belum punya profil, sarankan untuk melengkapi profil
    4. Untuk pertanyaan di luar konteks rekrutmen, redirect dengan sopan
    5. Jangan membuat janji yang tidak bisa dipenuhi (misal: "pasti diterima")
    6. Format jawaban dengan jelas dan mudah dibaca

    Nama user saat ini: {$user->name}
    PROMPT;
    }

    /**
     * Format reply untuk HTML display
     * 
     * @param string $reply
     * @return string
     */
    protected function formatReply($reply)
    {
        // Convert markdown-like formatting to HTML
        $reply = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $reply);
        $reply = preg_replace('/\*(.*?)\*/', '<i>$1</i>', $reply);
        $reply = preg_replace('/^- (.*)$/m', '• $1', $reply);
        $reply = preg_replace('/^\d+\. (.*)$/m', '$0', $reply);
        $reply = nl2br($reply);
        
        return $reply;
    }

    /**
     * Fallback response jika API gagal
     * 
     * @param string $message
     * @param array $context
     * @param mixed $user
     * @param string $role
     * @return string
     */
    protected function getFallbackResponse($message, $context, $user, $role)
    {
        $message = strtolower($message);
        
        if ($role === 'admin') {
            return $this->generateAdminFallback($message, $context, $user);
        }
        
        return $this->generateApplicantFallback($message, $context, $user);
    }

    /**
     * Fallback response untuk pelamar
     */
    protected function generateApplicantFallback($message, $context, $user)
    {
        // --- STATUS LAMARAN ---
        if (strpos($message, 'status') !== false || strpos($message, 'lamaran') !== false) {
            if (empty($context['latest_application'])) {
                return "Anda belum memiliki lamaran aktif. Silakan lihat lowongan yang tersedia di menu <b>Lowongan Tersedia</b>. 📋";
            }
            
            $app = $context['latest_application'];
            $statusEmoji = [
                'pending' => '⏳',
                'accepted' => '🎉',
                'rejected' => '☠️',
                'process' => '🔄',
            ];
            $emoji = $statusEmoji[$app['status']] ?? '📋';
            
            return "Status lamaran Anda untuk posisi <b>{$app['position']}</b>:<br><br>" .
                   "{$emoji} Status: <b>" . ucfirst($app['status']) . "</b><br>" .
                   "📅 Tanggal Lamar: {$app['applied_at']}";
        }
        
        // --- LOWONGAN ---
        if (strpos($message, 'lowongan') !== false || strpos($message, 'kerja') !== false) {
            if (empty($context['available_vacancies'])) {
                return "Mohon maaf, saat ini tidak ada lowongan yang tersedia. Silakan cek kembali nanti ya! 🙏";
            }
            
            $list = "";
            foreach ($context['available_vacancies'] as $v) {
                $list .= "• <b>{$v['title']}</b> - {$v['department']}<br>";
            }
            
            return "Ada <b>{$context['total_vacancies']}</b> lowongan tersedia:<br><br>{$list}<br>" .
                   "Kunjungi menu <b>Lowongan Tersedia</b> untuk melamar! 🚀";
        }
        
        // --- BANTUAN ---
        if (strpos($message, 'bantuan') !== false || strpos($message, 'help') !== false || strpos($message, 'halo') !== false) {
            return "Halo {$user->name}! 👋<br><br>" .
                   "Saya HR Assistant, siap membantu Anda:<br>" .
                   "• <b>Status lamaran</b> - Cek status terbaru<br>" .
                   "• <b>Lowongan</b> - Lihat lowongan tersedia<br>" .
                   "• <b>Cara melamar</b> - Panduan melamar kerja<br>" .
                   "• <b>Profil</b> - Info profil Anda<br><br>" .
                   "Silakan tanyakan apa saja! 😊";
        }
        
        // --- DEFAULT ---
        return "Maaf, saya sedang mengalami kendala teknis. 🔧<br><br>" .
               "Coba tanyakan tentang:<br>" .
               "• Status lamaran<br>" .
               "• Lowongan kerja<br>" .
               "• Cara melamar<br><br>" .
               "Atau ketik <b>bantuan</b> untuk info lebih lanjut.";
    }

    /**
     * Fallback response untuk admin
     */
    protected function generateAdminFallback($message, $context, $user)
    {
        // --- STATISTIK ---
        if (strpos($message, 'statistik') !== false || strpos($message, 'total') !== false) {
            $stats = $context['statistics'];
            
            return "📊 <b>Statistik Rekrutmen</b><br><br>" .
                   "• Total Pelamar: <b>{$stats['total_applicants']}</b><br>" .
                   "• Lowongan Aktif: <b>{$stats['total_vacancies']}</b><br>" .
                   "• Lamaran Pending: <b>{$stats['pending_applications']}</b><br>" .
                   "• Diterima: <b>{$stats['accepted_applications']}</b><br>" .
                   "• Ditolak: <b>{$stats['rejected_applications']}</b>";
        }
        
        // --- DEFAULT ---
        return "Halo {$user->name}! 👋<br><br>" .
               "Saya bisa membantu dengan:<br>" .
               "• <b>Statistik</b> - Data rekrutmen<br>" .
               "• <b>Pending</b> - Lamaran menunggu review<br><br>" .
               "Silakan tanyakan! 😊";
    }
}
