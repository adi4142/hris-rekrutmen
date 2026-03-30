<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobVacancie;
use App\JobApplication;
use App\JobApplicant;
use Illuminate\Support\Facades\Mail;


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
            // Ambil semua riwayat lamaran (mengurangi query berulang)
            $applicationHistory = JobApplication::with(['jobVacancie.position', 'jobVacancie.departement'])
                ->where('job_applicant_id', $applicant->job_applicant_id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            // Total lamaran yang pernah diajukan oleh pelamar ini
            $totalMyApplications = $applicationHistory->count();
            
            // Hitung lamaran berdasarkan status menggunakan collection
            $pendingApplications = $applicationHistory->whereIn('status', ['pending', 'applied'])->count();
            $acceptedApplications = $applicationHistory->whereIn('status', ['accepted', 'hired'])->count();
            $rejectedApplications = $applicationHistory->where('status', 'rejected')->count();
            
            // Ambil lamaran terbaru untuk menampilkan status dan proses seleksi
            $latestApplication = JobApplication::with([
                'jobVacancie.position', 
                'jobVacancie.departement',
                'batch.stages.selection',
                'selectionApplicant.selection',
                'selectionApplicant.aspectScores.aspect'
            ])
                ->where('job_applicant_id', $applicant->job_applicant_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            // Cari lamaran yang sedang aktif (dalam seleksi)
            $activeApplication = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)
                ->whereIn('status', ['pending', 'approved', 'process', 'applied', 'offering', 'offering_sent', 'negotiation_requested'])
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
    

        return view('dashboard.applicant', compact(
            'user',
            'applicant',
            'totalMyApplications',
            'latestApplication',
            'pendingApplications',
            'acceptedApplications',
            'rejectedApplications',
            'applicationHistory',
            'activeApplication'
        ));
    }
    

    public function editProfile()
    {
        $user = auth()->user();
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();

        return view('applicant.profile_edit', compact('user', 'applicant'));
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
        $applicant = JobApplicant::where('user_id', $user->user_id)->first();
        
        // Pre-validate for new profiles (require CV)
        $isNew = !$applicant;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update User info
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $profileData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ];

        // Handle Photo upload
        if ($request->hasFile('photo')) {
            $profileData['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        // Update atau create data pelamar
        $applicant = JobApplicant::updateOrCreate(
            ['user_id' => $user->user_id],
            $profileData
        );

        return redirect()->route('applicant.profile')
            ->with('success', 'Profil berhasil diperbarui.');
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

        if (!$applicant) {
            abort(403, 'Akses ditolak. Silakan lengkapi profil pelamar Anda terlebih dahulu.');
        }

        // Pastikan lamaran ini milik pelamar yang sedang login
        $application = JobApplication::with([
            'jobVacancie.position', 
            'jobVacancie.departement', 
            'batch.stages.selection',
            'selectionApplicant.selection',
            'selectionApplicant.aspectScores.aspect'
            
        ])
            ->where('application_id', $id)
            ->where('job_applicant_id', $applicant->job_applicant_id)
            ->firstOrFail();

        return view('applicant.application-detail', compact('user', 'applicant', 'application'));
    }
}
