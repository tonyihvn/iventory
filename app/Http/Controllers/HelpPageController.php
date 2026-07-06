<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HelpPage;
use Illuminate\Support\Facades\Storage;

class HelpPageController extends Controller
{
    /**
     * Display the help page (public)
     */
    public function show()
    {
        $helpPage = HelpPage::first() ?? new HelpPage();
        return view('help', ['helpPage' => $helpPage]);
    }

    /**
     * Show edit form for admin
     */
    public function edit()
    {
        $helpPage = HelpPage::first() ?? new HelpPage();
        return view('help_edit', ['helpPage' => $helpPage]);
    }

    /**
     * Update help page content and documents
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'support_text' => 'nullable|string',
            'inventory_sop' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'dct_sop' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $helpPage = HelpPage::first() ?? new HelpPage();

        if ($request->filled('support_text')) {
            $helpPage->support_text = $request->input('support_text');
        }

        // Handle Inventory SOP file upload
        if ($request->hasFile('inventory_sop')) {
            // Delete old file if exists
            if ($helpPage->inventory_sop_file && Storage::disk('public')->exists($helpPage->inventory_sop_file)) {
                Storage::disk('public')->delete($helpPage->inventory_sop_file);
            }
            $file = $request->file('inventory_sop');
            $path = $file->store('documents/sops', 'public');
            $helpPage->inventory_sop_file = $path;
        }

        // Handle DCT SOP file upload
        if ($request->hasFile('dct_sop')) {
            // Delete old file if exists
            if ($helpPage->dct_sop_file && Storage::disk('public')->exists($helpPage->dct_sop_file)) {
                Storage::disk('public')->delete($helpPage->dct_sop_file);
            }
            $file = $request->file('dct_sop');
            $path = $file->store('documents/sops', 'public');
            $helpPage->dct_sop_file = $path;
        }

        $helpPage->save();

        return redirect('/help-edit')->with('success', 'Help page updated successfully!');
    }
}
