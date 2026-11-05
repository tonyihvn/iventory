@extends('template')

@section('content')
<div class="row" style="width:98%; margin:auto;">
    <h5 class="text-center center" style="text-align: center; margin-bottom: 30px;">Create New Note</h5>

    @if ($errors->any())
        <div class="card-panel red lighten-2">
            <span class="white-text"><strong>Validation Errors:</strong></span>
            <ul class="white-text" style="margin-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="col s12 m10 offset-m1">
        <form method="POST" action="{{ route('notes.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Title Field -->
            <div class="input-field col s12">
                <input type="text" id="title" name="title" class="validate" value="{{ old('title') }}" required>
                <label for="title">Note Title</label>
                @error('title')
                    <span class="helper-text red-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Inventory Item Selection (Optional) -->
            <div class="input-field col s12">
                <select id="inventory_id" name="inventory_id" materialize="material_select" class="select2">
                    <option value="">No Item Selected (General Note)</option>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->id }}" {{ (old('inventory_id') ?? $preselectedInventoryId) == $inventory->id ? 'selected' : '' }}>
                            {{ $inventory->item_name }} ({{ $inventory->serial_no }} - {{ $inventory->tag_no}})
                        </option>
                    @endforeach
                </select>
                <label for="inventory_id">Link to Item (Optional)</label>
                @error('inventory_id')
                    <span class="helper-text red-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Content Field with WYSIWYG Editor -->
            <div class="input-field col s12">
                <label for="content">Note Content</label>
                <textarea id="content" name="content" class="materialize-textarea" required style="height: 300px;">{{ old('content') }}</textarea>
                @error('content')
                    <span class="helper-text red-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- File Attachments -->
            <div class="col s12">
                <h6>Attachments</h6>
                <div class="file-field input-field">
                    <div class="btn waves-effect waves-light dark-purple">
                        <span>Choose Files</span>
                        <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.txt,.zip">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload one or more files">
                    </div>
                </div>
                <p class="grey-text" style="margin-top: 5px;">Max 10MB per file. Accepted: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, GIF, TXT, ZIP</p>
                @error('attachments')
                    <span class="helper-text red-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Is Public Checkbox -->
            <div class="col s12" style="margin-top: 20px;">
                <label>
                    <input type="checkbox" name="is_public" value="1">
                    <span>Share with other admins (Admin/Super users only)</span>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="col s12" style="margin-top: 30px; margin-bottom: 20px;">
                <button type="submit" class="btn waves-effect waves-light dark-purple">
                    <i class="material-icons left">save</i>Create Note
                </button>
                <a href="{{ route('notes.index') }}" class="btn waves-effect waves-light grey">
                    <i class="material-icons left">cancel</i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Summernote WYSIWYG Editor -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.js"></script>

<script>
    $(function() {
        // Initialize Materialize select
        $('select').formSelect();

        // Initialize Summernote editor
        $('#content').summernote({
            placeholder: 'Enter your note content here...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>

<style>
    .note-editor.note-frame {
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>
@endsection
