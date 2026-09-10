<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    /**
     * Display a listing of bank branches with loan and user counts.
     */
    public function index()
    {
        $branches = Branch::withCount(['users', 'badLoans'])->orderBy('name')->paginate(15);

        return view('branches.index', compact('branches'));
    }

    /**
     * Show form for editing an existing branch.
     */
    public function edit(Branch $branch)
    {
        $branch->loadCount(['users', 'badLoans']);

        return view('branches.edit', compact('branch'));
    }

    /**
     * Store a new branch in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50', 'unique:branches,code'],
        ]);

        Branch::create($validated);

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

    /**
     * Update branch details.
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50', Rule::unique('branches', 'code')->ignore($branch->id)],
        ]);

        $branch->update($validated);

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    /**
     * Delete a branch if it has no associated loans or users.
     */
    public function destroy(Branch $branch)
    {
        if ($branch->badLoans()->exists()) {
            return redirect()
                ->route('branches.index')
                ->with('error', 'Cannot delete branch because it has active bad loans linked to it.');
        }

        $branch->delete();

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }
}

