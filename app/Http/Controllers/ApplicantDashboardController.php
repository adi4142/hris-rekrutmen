<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobVacancie;
use App\JobApplication;
use App\JobApplicant;

/**
 * ApplicantDashboardController
 * 
 * Controller khusus untuk dashboard PELAMAR (tamu)
 * Menampilkan statistik lamaran pribadi pelamar
 */
class ApplicantDashboardController extends Controller
{
    /**
     * Tampilkan dashboard pelamar dengan statistik lamaran pribadi
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil user yang sedang login
        $user = auth()->user();
        
        // Cari data pelamar berdasarkan user_id
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();
        
        // Jika pelamar belum memiliki profil, set default values
        if ($applicant) {
            // Total lamaran yang pernah diajukan oleh pelamar ini
            $totalMyApplications = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)->count();
            
            // Ambil lamaran terbaru untuk menampilkan status
            $latestApplication = JobApplication::with(['jobVacancie', 'selectionApplicant.selection'])
                ->where('job_applicant_id', $applicant->job_applicant_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            // Hitung lamaran berdasarkan status
            $pendingApplications = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)
                ->where('status', 'pending')
                ->count();
                
            $acceptedApplications = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)
                ->where('status', 'accepted')
                ->count();
                
            $rejectedApplications = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)
                ->where('status', 'rejected')
                ->count();
            
            // Riwayat semua lamaran pelamar ini
            $applicationHistory = JobApplication::with(['jobVacancie.position', 'jobVacancie.departement'])
                ->where('job_applicant_id', $applicant->job_applicant_id)
                ->orderBy('created_at', 'desc')
                ->get();
            // Cari lamaran yang sedang aktif (dalam seleksi)
            $activeApplication = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)
                ->whereIn('status', ['pending', 'approved', 'process', 'applied'])
                ->first();
        } else {
            // Default values jika belum ada profil pelamar
            $totalMyApplications = 0;
            $latestApplication = null;
            $pendingApplications = 0;
            $acceptedApplications = 0;
            $rejectedApplications = 0;
            $applicationHistory = collect(); // Empty collection
            $activeApplication = null;
        }
        
        // Total lowongan aktif yang bisa dilamar
        $totalActiveVacancies = JobVacancie::where('status', 'open')->count();
        
        // Daftar lowongan aktif untuk ditampilkan
        $activeVacancies = JobVacancie::with(['position', 'departement'])
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.applicant', compact(
            'user',
            'applicant',
            'totalMyApplications',
            'latestApplication',
            'pendingApplications',
            'acceptedApplications',
            'rejectedApplications',
            'totalActiveVacancies',
            'activeVacancies',
            'applicationHistory',
            'activeApplication'
        ));
    }

    /**
     * Tampilkan halaman profil pelamar
     * 
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = auth()->user();
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();

        return view('applicant.profile', compact('user', 'applicant'));
    }

    /**
     * Update profil pelamar
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'cv_file' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        // Update atau create data pelamar
        $applicant = JobApplicant::updateOrCreate(
            ['user_id' => $user->user_id],
            [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
            ]
        );

        // Handle CV upload jika ada
        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('cv_files', 'public');
            $applicant->update(['cv_file' => $cvPath]);
        }

        return redirect()->route('applicant.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Tampilkan daftar lowongan yang tersedia
     * 
     * @return \Illuminate\View\View
     */
    public function vacancies()
    {
        $user = auth()->user();
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();
        
        // Ambil semua lowongan aktif dengan relasi
        $vacancies = JobVacancie::with(['position', 'departement'])
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Cek apakah ada lamaran yang sedang dalam proses (seleksi)
        $activeApplication = null;
        if ($applicant) {
            $activeApplication = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)
                ->whereIn('status', ['pending', 'approved', 'process', 'applied'])
                ->first();
        }

        return view('applicant.vacancies', compact('user', 'vacancies', 'activeApplication'));
    }

    /**
     * Tampilkan form untuk melamar pekerjaan
     * 
     * @param int $vacancies_id
     * @return \Illuminate\View\View
     */
    public function applyForm($vacancies_id)
    {
        $user = auth()->user();
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();
        $vacancy = JobVacancie::with(['position', 'departement'])->findOrFail($vacancies_id);

        // Cek apakah ada lamaran yang sedang dalam proses (seleksi)
        $activeApplication = null;
        if ($applicant) {
            $activeApplication = JobApplication::with(['jobVacancie'])
                ->where('job_applicant_id', $applicant->job_applicant_id)
                ->whereIn('status', ['pending', 'approved', 'process', 'applied'])
                ->first();
        }

        return view('applicant.apply', compact('user', 'applicant', 'vacancy', 'activeApplication'));
    }

    /**
     * Submit lamaran kerja
     * 
     * @param Request $request
     * @param int $vacancies_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitApplication(Request $request, $vacancies_id)
    {
        $user = auth()->user();
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();

        // Pastikan pelamar sudah memiliki profil
        if (!$applicant) {
            return redirect()->route('applicant.profile')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        // Cek apakah ada lamaran yang sedang dalam proses (seleksi)
        $activeApplication = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)
            ->whereIn('status', ['pending', 'approved', 'process', 'applied'])
            ->first();

        if ($activeApplication) {
            if ($activeApplication->vacancies_id == $vacancies_id) {
                return redirect()->route('applicant.vacancies')
                    ->with('error', 'Anda sudah pernah melamar ke lowongan ini.');
            } else {
                return redirect()->route('applicant.vacancies')
                    ->with('error', 'Anda masih dalam tahap seleksi untuk lowongan lain. Anda hanya bisa melamar satu lowongan dalam satu waktu.');
            }
        }

        // Buat lamaran baru
        JobApplication::create([
            'job_applicant_id' => $applicant->job_applicant_id,
            'vacancies_id' => $vacancies_id,
            'status' => 'pending',
        ]);

        return redirect()->route('applicant.applications')
            ->with('success', 'Lamaran berhasil dikirim!');
    }

    /**
     * Tampilkan riwayat lamaran pelamar
     * 
     * @return \Illuminate\View\View
     */
    public function applications()
    {
        $user = auth()->user();
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();

        $applications = collect();
        if ($applicant) {
            $applications = JobApplication::with(['jobVacancie.position', 'jobVacancie.departement', 'selectionApplicant'])
                ->where('job_applicant_id', $applicant->job_applicant_id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('applicant.applications', compact('user', 'applicant', 'applications'));
    }

    /**
     * Tampilkan detail lamaran
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function applicationDetail($id)
    {
        $user = auth()->user();
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();

        // Pastikan lamaran ini milik pelamar yang sedang login
        $application = JobApplication::with(['jobVacancie.position', 'jobVacancie.departement', 'selectionApplicant.selection'])
            ->where('application_id', $id)
            ->where('job_applicant_id', $applicant->job_applicant_id ?? 0)
            ->firstOrFail();

        return view('applicant.application-detail', compact('user', 'applicant', 'application'));
    }
}
