<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedComprehensiveSystemSettings extends Migration
{
    public function up()
    {
        $settings = [
            // ── GROUP: perusahaan ──────────────────────────────────────
            ['key' => 'company_name',    'value' => 'PT. Rekrutmen Indonesia',       'group' => 'perusahaan'],
            ['key' => 'company_email',   'value' => 'hrd@perusahaan.com',            'group' => 'perusahaan'],
            ['key' => 'company_phone',   'value' => '021-12345678',                  'group' => 'perusahaan'],
            ['key' => 'company_address', 'value' => 'Jl. Contoh No. 1, Jakarta',     'group' => 'perusahaan'],
            ['key' => 'company_website', 'value' => 'https://perusahaan.com',         'group' => 'perusahaan'],
            ['key' => 'company_tagline', 'value' => 'Solusi Rekrutmen Terpercaya',    'group' => 'perusahaan'],

            // ── GROUP: rekrutmen ───────────────────────────────────────
            ['key' => 'recruitment_default_status',      'value' => 'applied',    'group' => 'rekrutmen'],
            ['key' => 'recruitment_min_age',             'value' => '18',         'group' => 'rekrutmen'],
            ['key' => 'recruitment_max_age',             'value' => '45',         'group' => 'rekrutmen'],
            ['key' => 'recruitment_vacancy_duration',    'value' => '30',         'group' => 'rekrutmen'],
            ['key' => 'recruitment_max_applicants',      'value' => '100',        'group' => 'rekrutmen'],
            ['key' => 'recruitment_auto_close_vacancy',  'value' => '1',          'group' => 'rekrutmen'],
            ['key' => 'recruitment_notify_applicant',    'value' => '1',          'group' => 'rekrutmen'],
            ['key' => 'recruitment_min_score_pass',      'value' => '70',         'group' => 'rekrutmen'],

            // ── GROUP: email ───────────────────────────────────────────
            ['key' => 'mail_host',         'value' => 'smtp.gmail.com',     'group' => 'email'],
            ['key' => 'mail_port',         'value' => '465',                'group' => 'email'],
            ['key' => 'mail_username',     'value' => '',                   'group' => 'email'],
            ['key' => 'mail_password',     'value' => '',                   'group' => 'email'],
            ['key' => 'mail_encryption',   'value' => 'ssl',                'group' => 'email'],
            ['key' => 'mail_from_address', 'value' => '',                   'group' => 'email'],
            ['key' => 'mail_from_name',    'value' => 'HRD Rekrutmen',      'group' => 'email'],

            // ── GROUP: keamanan ────────────────────────────────────────
            ['key' => 'license_code_admin', 'value' => 'ADMIN2026XYZ',  'group' => 'keamanan'],
            ['key' => 'license_code_hrd',   'value' => 'HRD2026ABC',    'group' => 'keamanan'],
            ['key' => 'session_lifetime',   'value' => '120',           'group' => 'keamanan'],
            ['key' => 'max_login_attempts', 'value' => '5',             'group' => 'keamanan'],
        ];

        foreach ($settings as $s) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $s['key']],
                ['value' => $s['value'], 'group' => $s['group'], 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down()
    {
        $keys = [
            'company_name','company_email','company_phone','company_address','company_website','company_tagline',
            'recruitment_default_status','recruitment_min_age','recruitment_max_age','recruitment_vacancy_duration',
            'recruitment_max_applicants','recruitment_auto_close_vacancy','recruitment_notify_applicant','recruitment_min_score_pass',
            'mail_host','mail_port','mail_username','mail_password','mail_encryption','mail_from_address','mail_from_name',
            'license_code_admin','license_code_hrd','session_lifetime','max_login_attempts',
        ];
        DB::table('system_settings')->whereIn('key', $keys)->delete();
    }
}
