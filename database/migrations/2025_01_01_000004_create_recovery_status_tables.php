<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Blacklist Module
        Schema::create('loan_blacklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bad_loan_id')->unique()->constrained('bad_loans')->cascadeOnDelete();
            $table->date('proposal_received_date')->nullable();
            $table->date('35_days_notice')->nullable();
            $table->string('black_list_no')->nullable();
            $table->date('date')->nullable();
            $table->date('black_list_release_date')->nullable();
            $table->text('remarks')->nullable();
            $table->text('client_application')->nullable();
            $table->decimal('client_deposit_amount', 15, 2)->default(0.00);
            $table->string('client_timeline')->nullable();
            $table->timestamps();
        });

        // 2. Auction Module
        Schema::create('loan_auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bad_loan_id')->unique()->constrained('bad_loans')->cascadeOnDelete();
            $table->date('proposal_received_date')->nullable();
            $table->decimal('collateral_value', 15, 2)->default(0.00);
            $table->string('valuators_name')->nullable();
            $table->date('valuation_date')->nullable();
            $table->date('first_auction_notice')->nullable();
            $table->string('first_auction_status')->nullable();
            $table->decimal('first_auction_recover', 15, 2)->default(0.00);
            $table->date('second_notice')->nullable();
            $table->string('second_status')->nullable();
            $table->decimal('second_recover', 15, 2)->default(0.00);
            $table->date('third_notice')->nullable();
            $table->string('third_status')->nullable();
            $table->decimal('third_recover', 15, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->text('client_application')->nullable();
            $table->decimal('client_deposit_amount', 15, 2)->default(0.00);
            $table->string('client_timeline')->nullable();
            $table->timestamps();
        });

        // 3. Partial Release Module
        Schema::create('loan_partial_releases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bad_loan_id')->unique()->constrained('bad_loans')->cascadeOnDelete();
            $table->date('proposal_received_date')->nullable();
            $table->string('partial_release')->nullable();
            $table->string('plot_no')->nullable();
            $table->decimal('value_of_land', 15, 2)->default(0.00);
            $table->string('lending_time')->nullable();
            $table->string('recovery_time')->nullable();
            $table->decimal('received_amount', 15, 2)->default(0.00);
            $table->text('restructure_reschedule')->nullable();
            $table->text('main_decision')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // 4. NBA Book (Non-Banking Assets) Module
        Schema::create('loan_nbas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bad_loan_id')->unique()->constrained('bad_loans')->cascadeOnDelete();
            $table->date('proposal_received_date')->nullable();
            $table->decimal('committees_valuation', 15, 2)->default(0.00);
            $table->text('decision_from_board')->nullable();
            $table->date('35_days_notice')->nullable();
            $table->date('letter_to_land_reg_office')->nullable();
            $table->date('ownership_transfer')->nullable();
            $table->decimal('value_of_property', 15, 2)->default(0.00);
            $table->decimal('remaining_loan', 15, 2)->default(0.00);
            $table->decimal('revaluation', 15, 2)->default(0.00);
            $table->date('auction_notice')->nullable();
            $table->text('remarks')->nullable();
            $table->text('client_application')->nullable();
            $table->decimal('client_deposit_amount', 15, 2)->default(0.00);
            $table->string('client_timeline')->nullable();
            $table->timestamps();
        });

        // 5. Interest Rebate / Write-Off Module
        Schema::create('loan_interest_rebates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bad_loan_id')->unique()->constrained('bad_loans')->cascadeOnDelete();
            $table->date('proposal_received_date')->nullable();
            $table->date('decision_date')->nullable();
            $table->string('decision_authority')->nullable();
            $table->decimal('interest_paid', 15, 2)->default(0.00);
            $table->decimal('interest_rebate', 15, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->text('collateral')->nullable();
            $table->decimal('write_off_principal', 15, 2)->default(0.00);
            $table->decimal('write_off_regular_interest', 15, 2)->default(0.00);
            $table->decimal('write_off_penal_interest', 15, 2)->default(0.00);
            $table->text('write_off_collateral')->nullable();
            $table->text('write_off_remarks')->nullable();
            $table->timestamps();
        });

        // 6. DRT (Debt Recovery Tribunal) Module
        Schema::create('loan_drts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bad_loan_id')->unique()->constrained('bad_loans')->cascadeOnDelete();
            $table->date('proposal_received_date')->nullable();
            $table->date('letter_to_legal_department')->nullable();
            $table->date('letter_to_drt')->nullable();
            $table->string('case_filed_returned')->nullable();
            $table->text('decision_from_drt')->nullable();
            $table->date('decision_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_drts');
        Schema::dropIfExists('loan_interest_rebates');
        Schema::dropIfExists('loan_nbas');
        Schema::dropIfExists('loan_partial_releases');
        Schema::dropIfExists('loan_auctions');
        Schema::dropIfExists('loan_blacklists');
    }
};

