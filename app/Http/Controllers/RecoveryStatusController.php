<?php

namespace App\Http\Controllers;

use App\Models\BadLoan;
use Illuminate\Http\Request;

class RecoveryStatusController extends Controller
{
    /**
     * Show the tabbed recovery management screen for a bad loan.
     */
    public function manage(BadLoan $badLoan)
    {
        $this->authorize('manageRecovery', $badLoan);

        $badLoan->load([
            'branch',
            'blacklist',
            'auction',
            'partialRelease',
            'nba',
            'interestRebate',
            'drt'
        ]);

        return view('recovery.manage', compact('badLoan'));
    }

    /**
     * Update or create Blacklist record.
     */
    public function updateBlacklist(Request $request, BadLoan $badLoan)
    {
        $this->authorize('manageRecovery', $badLoan);

        $validated = $request->validate([
            'proposal_received_date'  => ['nullable', 'date'],
            '35_days_notice'          => ['nullable', 'date'],
            'black_list_no'           => ['nullable', 'string', 'max:100'],
            'date'                    => ['nullable', 'date'],
            'black_list_release_date' => ['nullable', 'date'],
            'remarks'                 => ['nullable', 'string'],
            'client_application'      => ['nullable', 'string'],
            'client_deposit_amount'   => ['nullable', 'numeric', 'min:0'],
            'client_timeline'         => ['nullable', 'string', 'max:255'],
        ]);

        $badLoan->blacklist()->updateOrCreate(
            ['bad_loan_id' => $badLoan->id],
            $validated
        );

        return redirect()
            ->route('recovery.manage', ['badLoan' => $badLoan->id, 'tab' => 'blacklist'])
            ->with('success', 'Blacklist status updated successfully.');
    }

    /**
     * Update or create Auction record.
     */
    public function updateAuction(Request $request, BadLoan $badLoan)
    {
        $this->authorize('manageRecovery', $badLoan);

        $validated = $request->validate([
            'proposal_received_date' => ['nullable', 'date'],
            'collateral_value'       => ['nullable', 'numeric', 'min:0'],
            'valuators_name'         => ['nullable', 'string', 'max:255'],
            'valuation_date'         => ['nullable', 'date'],
            'first_auction_notice'   => ['nullable', 'date'],
            'first_auction_status'   => ['nullable', 'string', 'max:100'],
            'first_auction_recover'  => ['nullable', 'numeric', 'min:0'],
            'second_notice'          => ['nullable', 'date'],
            'second_status'          => ['nullable', 'string', 'max:100'],
            'second_recover'         => ['nullable', 'numeric', 'min:0'],
            'third_notice'           => ['nullable', 'date'],
            'third_status'           => ['nullable', 'string', 'max:100'],
            'third_recover'          => ['nullable', 'numeric', 'min:0'],
            'remarks'                => ['nullable', 'string'],
            'client_application'     => ['nullable', 'string'],
            'client_deposit_amount'  => ['nullable', 'numeric', 'min:0'],
            'client_timeline'        => ['nullable', 'string', 'max:255'],
        ]);

        $badLoan->auction()->updateOrCreate(
            ['bad_loan_id' => $badLoan->id],
            $validated
        );

        return redirect()
            ->route('recovery.manage', ['badLoan' => $badLoan->id, 'tab' => 'auction'])
            ->with('success', 'Auction recovery status updated successfully.');
    }

    /**
     * Update or create Partial Release / Restructure / Reschedule record.
     */
    public function updatePartialRelease(Request $request, BadLoan $badLoan)
    {
        $this->authorize('manageRecovery', $badLoan);

        $validated = $request->validate([
            'proposal_received_date' => ['nullable', 'date'],
            'partial_release'        => ['nullable', 'string', 'max:255'],
            'plot_no'                => ['nullable', 'string', 'max:255'],
            'value_of_land'          => ['nullable', 'numeric', 'min:0'],
            'lending_time'           => ['nullable', 'string', 'max:255'],
            'recovery_time'          => ['nullable', 'string', 'max:255'],
            'received_amount'        => ['nullable', 'numeric', 'min:0'],
            'restructure_reschedule' => ['nullable', 'string'],
            'main_decision'          => ['nullable', 'string'],
            'remarks'                => ['nullable', 'string'],
        ]);

        $badLoan->partialRelease()->updateOrCreate(
            ['bad_loan_id' => $badLoan->id],
            $validated
        );

        return redirect()
            ->route('recovery.manage', ['badLoan' => $badLoan->id, 'tab' => 'partial_release'])
            ->with('success', 'Partial release and restructuring status updated successfully.');
    }

    /**
     * Update or create NBA (Non-Banking Asset) Book record.
     */
    public function updateNba(Request $request, BadLoan $badLoan)
    {
        $this->authorize('manageRecovery', $badLoan);

        $validated = $request->validate([
            'proposal_received_date'     => ['nullable', 'date'],
            'committees_valuation'       => ['nullable', 'numeric', 'min:0'],
            'decision_from_board'        => ['nullable', 'string'],
            '35_days_notice'             => ['nullable', 'date'],
            'letter_to_land_reg_office'  => ['nullable', 'date'],
            'ownership_transfer'         => ['nullable', 'date'],
            'value_of_property'          => ['nullable', 'numeric', 'min:0'],
            'remaining_loan'             => ['nullable', 'numeric', 'min:0'],
            'revaluation'                => ['nullable', 'numeric', 'min:0'],
            'auction_notice'             => ['nullable', 'date'],
            'remarks'                    => ['nullable', 'string'],
            'client_application'         => ['nullable', 'string'],
            'client_deposit_amount'      => ['nullable', 'numeric', 'min:0'],
            'client_timeline'            => ['nullable', 'string', 'max:255'],
        ]);

        $badLoan->nba()->updateOrCreate(
            ['bad_loan_id' => $badLoan->id],
            $validated
        );

        return redirect()
            ->route('recovery.manage', ['badLoan' => $badLoan->id, 'tab' => 'nba'])
            ->with('success', 'NBA Book status updated successfully.');
    }

    /**
     * Update or create Interest Rebate / Write-Off record.
     */
    public function updateInterestRebate(Request $request, BadLoan $badLoan)
    {
        $this->authorize('manageRecovery', $badLoan);

        $validated = $request->validate([
            'proposal_received_date'       => ['nullable', 'date'],
            'decision_date'                => ['nullable', 'date'],
            'decision_authority'           => ['nullable', 'string', 'max:255'],
            'interest_paid'                => ['nullable', 'numeric', 'min:0'],
            'interest_rebate'              => ['nullable', 'numeric', 'min:0'],
            'remarks'                      => ['nullable', 'string'],
            'collateral'                   => ['nullable', 'string'],
            'write_off_principal'          => ['nullable', 'numeric', 'min:0'],
            'write_off_regular_interest'   => ['nullable', 'numeric', 'min:0'],
            'write_off_penal_interest'     => ['nullable', 'numeric', 'min:0'],
            'write_off_collateral'         => ['nullable', 'string'],
            'write_off_remarks'            => ['nullable', 'string'],
        ]);

        $badLoan->interestRebate()->updateOrCreate(
            ['bad_loan_id' => $badLoan->id],
            $validated
        );

        return redirect()
            ->route('recovery.manage', ['badLoan' => $badLoan->id, 'tab' => 'interest_rebate'])
            ->with('success', 'Interest rebate and write-off details updated successfully.');
    }

    /**
     * Update or create DRT (Debt Recovery Tribunal) record.
     */
    public function updateDrt(Request $request, BadLoan $badLoan)
    {
        $this->authorize('manageRecovery', $badLoan);

        $validated = $request->validate([
            'proposal_received_date'      => ['nullable', 'date'],
            'letter_to_legal_department'  => ['nullable', 'date'],
            'letter_to_drt'               => ['nullable', 'date'],
            'case_filed_returned'         => ['nullable', 'string', 'max:255'],
            'decision_from_drt'           => ['nullable', 'string'],
            'decision_date'               => ['nullable', 'date'],
            'remarks'                     => ['nullable', 'string'],
        ]);

        $badLoan->drt()->updateOrCreate(
            ['bad_loan_id' => $badLoan->id],
            $validated
        );

        return redirect()
            ->route('recovery.manage', ['badLoan' => $badLoan->id, 'tab' => 'drt'])
            ->with('success', 'DRT status updated successfully.');
    }
}

