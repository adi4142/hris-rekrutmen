<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SetupBatchRecruitmentSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Update job_vacancies table
        if (!$this->columnExists('job_vacancies', 'quota')) {
            Schema::table('job_vacancies', function (Blueprint $table) {
                $table->integer('quota')->default(0)->after('salary_nominal')
                    ->comment('0 = unlimited, >0 = max hired count');
            });
        }

        if (!$this->columnExists('job_vacancies', 'hired_count')) {
            Schema::table('job_vacancies', function (Blueprint $table) {
                $table->integer('hired_count')->default(0)->after('quota')
                    ->comment('Auto-updated: count of accepted applicants');
            });
        }

        // 2. Create job_vacancy_stages table - Tahapan seleksi per lowongan
        if (!Schema::hasTable('job_vacancy_stages')) {
            Schema::create('job_vacancy_stages', function (Blueprint $table) {
                $table->id('vacancy_stage_id');
                $table->unsignedBigInteger('vacancies_id');
                $table->string('stage_name')->comment('e.g., Screening, Written Test, Interview');
                $table->integer('stage_order')->comment('Order dalam pipeline (1, 2, 3, ...)');
                $table->text('description')->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('vacancies_id')
                    ->references('vacancies_id')
                    ->on('job_vacancies')
                    ->onDelete('cascade');

                // Indexes
                $table->index('vacancies_id');
                $table->unique(['vacancies_id', 'stage_order']); // Prevent duplicate orders
            });
        }

        // 3. Update job_applications table
        if (!$this->columnExists('job_applications', 'current_stage_id')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->unsignedBigInteger('current_stage_id')->nullable()->after('status')
                    ->comment('FK to job_vacancy_stages - tracks current stage in pipeline');
            });
        }

        // 4. Create batch_processing_logs table - Audit trail
        if (!Schema::hasTable('batch_processing_logs')) {
            Schema::create('batch_processing_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vacancies_id');
                $table->unsignedBigInteger('current_stage_id');
                $table->unsignedBigInteger('next_stage_id')->nullable();
                $table->integer('approved_count')->default(0);
                $table->integer('rejected_count')->default(0);
                $table->unsignedBigInteger('processed_by_user_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('vacancies_id')
                    ->references('vacancies_id')
                    ->on('job_vacancies')
                    ->onDelete('cascade');
                
                $table->foreign('current_stage_id')
                    ->references('vacancy_stage_id')
                    ->on('job_vacancy_stages')
                    ->onDelete('cascade');

                // Indexes for fast queries
                $table->index(['vacancies_id', 'created_at']);
                $table->index(['current_stage_id', 'processed_at']);
            });
        }

        // 5. Add indexes untuk performance
        $this->addOptimalIndexes();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_processing_logs');
        
        Schema::table('job_applications', function (Blueprint $table) {
            if ($this->columnExists('job_applications', 'current_stage_id')) {
                $table->dropColumn('current_stage_id');
            }
        });

        Schema::dropIfExists('job_vacancy_stages');

        Schema::table('job_vacancies', function (Blueprint $table) {
            if ($this->columnExists('job_vacancies', 'hired_count')) {
                $table->dropColumn('hired_count');
            }
            if ($this->columnExists('job_vacancies', 'quota')) {
                $table->dropColumn('quota');
            }
        });
    }

    /**
     * Add optimal indexes untuk mencegah N+1 queries dan optimize batch operations
     */
    private function addOptimalIndexes()
    {
        // Indexes untuk job_applications
        if (Schema::hasTable('job_applications')) {
            // Untuk query "semua aplikasi di lowongan dengan status tertentu"
            if (!$this->indexExists('job_applications', 'idx_vacancy_status')) {
                DB::statement("CREATE INDEX idx_vacancy_status ON job_applications (vacancies_id, status)");
            }
            
            // Untuk query "semua aplikasi di tahapan tertentu"
            if (!$this->indexExists('job_applications', 'idx_current_stage')) {
                DB::statement("CREATE INDEX idx_current_stage ON job_applications (current_stage_id, status)");
            }
            
            // Untuk query "aplikasi per pelamar"
            if (!$this->indexExists('job_applications', 'idx_applicant')) {
                DB::statement("CREATE INDEX idx_applicant ON job_applications (job_applicant_id)");
            }
        }

        // Indexes untuk job_vacancy_stages
        if (Schema::hasTable('job_vacancy_stages')) {
            // Untuk query "semua stages di lowongan diurutkan"
            if (!$this->indexExists('job_vacancy_stages', 'idx_vacancy_order')) {
                DB::statement("CREATE INDEX idx_vacancy_order ON job_vacancy_stages (vacancies_id, stage_order)");
            }
        }
    }

    /**
     * Helper untuk check apakah column sudah ada tanpa dependensi Doctrine DBAL
     */
    private function columnExists($table, $column)
    {
        $db = DB::connection()->getDatabaseName();
        $columns = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table' AND COLUMN_NAME = '$column'");
        return count($columns) > 0;
    }

    /**
     * Helper untuk check apakah index sudah ada tanpa dependensi Doctrine DBAL
     */
    private function indexExists($table, $indexName)
    {
        $db = DB::connection()->getDatabaseName();
        $indexes = DB::select("SELECT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table' AND INDEX_NAME = '$indexName'");
        return count($indexes) > 0;
    }
}
