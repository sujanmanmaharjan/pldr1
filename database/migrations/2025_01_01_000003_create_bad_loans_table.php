<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bad_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('proprietor')->nullable();
            $table->string('reg_no')->nullable();
            $table->date('reg_authority_date')->nullable();
            $table->string('pan')->nullable()->index();
            $table->string('ctz')->nullable(); // Citizenship No
            $table->string('nin')->nullable(); // National ID No
            $table->text('guarantor_details')->nullable();
            $table->string('client_code')->nullable()->index();
            $table->string('main_code')->nullable();
            $table->string('loan_type')->nullable();
            $table->decimal('limit', 15, 2)->default(0.00);
            $table->date('sanction_date')->nullable();
            $table->date('last_renew_date')->nullable();
            $table->date('last_repayment_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('principal_os', 15, 2)->default(0.00);
            $table->decimal('interest_os', 15, 2)->default(0.00);
            $table->string('loan_class')->nullable(); // Substandard, Doubtful, Loss
            $table->date('7_days_notice_date')->nullable();
            $table->date('15_days_notice_date')->nullable();
            $table->date('21_days_notice_date')->nullable();
            $table->string('status')->default('Active'); // e.g., Active, In Recovery, Settled, Written Off
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bad_loans');
    }
};

