<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\JobVacancie;
use App\JobApplication;
use Illuminate\Support\Facades\DB;
use App\ActivityLog;

class SuperAdminDashboardController extends Controller
{
    /**
     * Path ke file JSON settings
     */
    private function settingsPath()
    {
        return config_path('site_settings.json');
    }

    /**
     * Baca semua settings dari JSON
     */
    private function readSettings()
    {
        $path = $this->settingsPath();
        if (!file_exists($path)) {
            return [];
        }
        return json_decode(file_get_contents($path), true) ?: [];
    }

    /**
     * Tulis settings ke JSON
     */
    private function writeSettings(array $data)
    {
        file_put_contents(
            $this->settingsPath(),
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Helper: ambil satu nilai setting
     * Contoh: SiteSettings::get('rekrutmen.recruitment_min_age', '18')
     */
    public static function getSetting(string $dotKey, $default = null)
    {
        $path = config_path('site_settings.json');
        if (!file_exists($path)) return $default;

        $data = json_decode(file_get_contents($path), true) ?: [];
        $keys = explode('.', $dotKey);

        $value = $data;
        foreach ($keys as $k) {
            if (!isset($value[$k])) return $default;
            $value = $value[$k];
        }
        return $value;
    }

    // ─────────────────────────────────────────────────
    // Dashboard
    // ─────────────────────────────────────────────────

    public function index()
    {
        $stats = [
            'total_users'        => User::count(),
            'total_vacancies'    => JobVacancie::count(),
            'total_applicants'   => User::whereHas('role', function ($q) {
                $q->where('name', 'Pelamar')->orWhere('name', 'Tamu');
            })->count(),
            'total_applications' => JobApplication::count(),
        ];

        $applicationStats = JobApplication::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as count')
        )->groupBy('month')->orderBy('month')->get();

        $recentLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.super_admin', compact('stats', 'applicationStats', 'recentLogs'));
    }

    // ─────────────────────────────────────────────────
    // Settings (baca dari JSON)
    // ─────────────────────────────────────────────────

    public function settings()
    {
        $settings = $this->readSettings();
        return view('superadmin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $old = $this->readSettings();

        // Update nilai per group -> key
        foreach ($request->except('_token') as $key => $value) {
            foreach ($old as $group => $items) {
                if (array_key_exists($key, $items)) {
                    $old[$group][$key] = $value;
                    break;
                }
            }
        }

        // Simpan ke file JSON
        $this->writeSettings($old);

        // Jika ada perubahan email, update juga .env supaya beneran jalan
        $envMap = [
            'mail_host'         => 'MAIL_HOST',
            'mail_port'         => 'MAIL_PORT',
            'mail_username'     => 'MAIL_USERNAME',
            'mail_password'     => 'MAIL_PASSWORD',
            'mail_encryption'   => 'MAIL_ENCRYPTION',
            'mail_from_address' => 'MAIL_FROM_ADDRESS',
            'mail_from_name'    => 'MAIL_FROM_NAME',
        ];

        $envChanges = [];
        foreach ($envMap as $jsonKey => $envKey) {
            if ($request->has($jsonKey)) {
                $envChanges[$envKey] = $request->input($jsonKey);
            }
        }

        if (!empty($envChanges)) {
            $this->updateEnvFile($envChanges);
        }

        return back()->with('success', 'Pengaturan sistem berhasil disimpan!');
    }

    // ─────────────────────────────────────────────────
    // Activity Logs
    // ─────────────────────────────────────────────────

    public function logs()
    {
        $logs = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.user_id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('superadmin.logs', compact('logs'));
    }

    // ─────────────────────────────────────────────────
    // Helper: update .env
    // ─────────────────────────────────────────────────

    private function updateEnvFile(array $data)
    {
        $envPath    = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($data as $envKey => $newValue) {
            $escaped = (str_contains($newValue, ' ') || $newValue === '')
                ? '"' . $newValue . '"'
                : $newValue;

            if (preg_match("/^{$envKey}=.*/m", $envContent)) {
                $envContent = preg_replace("/^{$envKey}=.*/m", "{$envKey}={$escaped}", $envContent);
            } else {
                $envContent .= "\n{$envKey}={$escaped}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
