<?php

namespace App\Http\Controllers;

use App\Note;
use App\NoteAttachment;
use App\inventory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // Query notes based on user role
        if ($user->role == 'Admin' || $user->role == 'Super') {
            // Admins can see all notes
            $notes = Note::with(['user', 'inventory', 'attachments'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Users can only see their own notes
            $notes = Note::where('user_id', $user->id)
                ->with(['user', 'inventory', 'attachments'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $inventories = inventory::select('id', 'item_name', 'serial_no','tag_no')->orderBy('item_name')->get();
        $preselectedInventoryId = $request->query('inventory_id');
        return view('notes.create', compact('inventories', 'preselectedInventoryId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'inventory_id' => 'nullable|exists:inventories,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240' // Max 10MB per file
        ]);

        // Create the note
        $note = new Note();
        $note->user_id = Auth::id();
        $note->title = $request->title;
        $note->content = $request->content;
        $note->inventory_id = $request->inventory_id;
        $note->is_public = $request->has('is_public') ? 1 : 0;
        $note->save();

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $this->storeAttachments($note, $request->file('attachments'));
        }

        return redirect()->route('notes.show', $note)->with('success', 'Note created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        $this->authorize('view', $note);
        
        $note->load(['user', 'inventory', 'attachments']);
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        $this->authorize('update', $note);
        
        $inventories = inventory::select('id', 'item_name', 'serial_no')->orderBy('item_name')->get();
        $note->load(['attachments']);
        
        return view('notes.edit', compact('note', 'inventories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'inventory_id' => 'nullable|exists:inventories,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240'
        ]);

        $note->title = $request->title;
        $note->content = $request->content;
        $note->inventory_id = $request->inventory_id;
        $note->is_public = $request->has('is_public') ? 1 : 0;
        $note->save();

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $this->storeAttachments($note, $request->file('attachments'));
        }

        return redirect()->route('notes.show', $note)->with('success', 'Note updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);

        // Delete all attachments
        foreach ($note->attachments as $attachment) {
            if (Storage::exists('public/' . $attachment->file_path)) {
                Storage::delete('public/' . $attachment->file_path);
            }
            $attachment->delete();
        }

        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Note deleted successfully!');
    }

    /**
     * Delete a specific attachment
     *
     * @param  int  $attachmentId
     * @return \Illuminate\Http\Response
     */
    public function deleteAttachment($attachmentId)
    {
        $attachment = NoteAttachment::findOrFail($attachmentId);
        $note = $attachment->note;
        $this->authorize('update', $note);

        if (Storage::exists('public/' . $attachment->file_path)) {
            Storage::delete('public/' . $attachment->file_path);
        }

        $attachment->delete();

        return redirect()->route('notes.edit', $note)->with('success', 'Attachment deleted successfully!');
    }

    /**
     * Download an attachment
     *
     * @param  int  $attachmentId
     * @return \Illuminate\Http\Response
     */
    public function downloadAttachment($attachmentId)
    {
        $attachment = NoteAttachment::findOrFail($attachmentId);
        $note = $attachment->note;
        $this->authorize('view', $note);

        $filePath = 'public/' . $attachment->file_path;

        if (!Storage::exists($filePath)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        return Storage::download($filePath, $attachment->file_name);
    }

    /**
     * Get notes for a specific inventory item
     *
     * @param  int  $inventoryId
     * @return \Illuminate\Http\Response
     */
    public function getInventoryNotes($inventoryId)
    {
        $user = Auth::user();
        $inventory = inventory::findOrFail($inventoryId);

        if ($user->role == 'Admin' || $user->role == 'Super') {
            $notes = Note::where('inventory_id', $inventoryId)
                ->with(['user', 'attachments'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $notes = Note::where('inventory_id', $inventoryId)
                ->where('user_id', $user->id)
                ->with(['user', 'attachments'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('notes.inventory-notes', compact('inventory', 'notes'));
    }

    /**
     * Store attachments for a note
     *
     * @param  \App\Note  $note
     * @param  array  $files
     * @return void
     */
    private function storeAttachments(Note $note, $files)
    {
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            // Store file in storage/app/public/notes/{note_id}/
            $filePath = $file->storeAs(
                'notes/' . $note->id,
                $fileName,
                'public'
            );

            // Create attachment record
            NoteAttachment::create([
                'note_id' => $note->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType
            ]);
        }
    }
}
