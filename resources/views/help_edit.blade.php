@extends('template')
@section('content')
<div class="container">
    <div class="row">
        <div class="col m12">
            <h4 style="margin-top: 20px;">Edit Help Page</h4>
        </div>
    </div>

    @if ($errors->any())
        <div class="row">
            <div class="col m12">
                <div class="card red lighten-4">
                    <div class="card-content">
                        <span class="card-title red-text">Validation Errors:</span>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="red-text">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="row">
            <div class="col m12">
                <div class="card green lighten-4">
                    <div class="card-content">
                        <span class="card-title green-text">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ url('/help-update') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="card col m12">
                <div class="card-content">
                    <span class="card-title">Support Text</span>
                    
                    <div class="row">
                        <div class="input-field col m12">
                            <textarea id="support_text" name="support_text" class="materialize-textarea" style="min-height: 150px;">{{ $helpPage->support_text ?? '' }}</textarea>
                            <label for="support_text">Support Text (HTML allowed)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="card col m6">
                <div class="card-content">
                    <span class="card-title">Inventory SOP</span>
                    
                    <div class="row">
                        @if($helpPage->inventory_sop_file)
                            <div class="col m12">
                                <p>
                                    <strong>Current File:</strong> 
                                    <a href="{{ asset('storage/' . $helpPage->inventory_sop_file) }}" target="_blank" class="teal-text">
                                        {{ basename($helpPage->inventory_sop_file) }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        <div class="file-field input-field col m12">
                            <div class="btn teal">
                                <span>Upload PDF</span>
                                <input type="file" name="inventory_sop" accept=".pdf,.doc,.docx" />
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Select a PDF or DOC file" />
                            </div>
                        </div>
                        <div class="col m12">
                            <p style="font-size: 12px; color: #999;">Maximum file size: 5 MB. Accepted formats: PDF, DOC, DOCX</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card col m6">
                <div class="card-content">
                    <span class="card-title">DCT Tools SOP</span>
                    
                    <div class="row">
                        @if($helpPage->dct_sop_file)
                            <div class="col m12">
                                <p>
                                    <strong>Current File:</strong> 
                                    <a href="{{ asset('storage/' . $helpPage->dct_sop_file) }}" target="_blank" class="teal-text">
                                        {{ basename($helpPage->dct_sop_file) }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        <div class="file-field input-field col m12">
                            <div class="btn teal">
                                <span>Upload PDF</span>
                                <input type="file" name="dct_sop" accept=".pdf,.doc,.docx" />
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Select a PDF or DOC file" />
                            </div>
                        </div>
                        <div class="col m12">
                            <p style="font-size: 12px; color: #999;">Maximum file size: 5 MB. Accepted formats: PDF, DOC, DOCX</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col m12">
                <button class="btn teal waves-effect waves-light" type="submit">
                    Save Changes
                    <i class="material-icons right">save</i>
                </button>
                <a href="{{ url('/help') }}" class="btn grey waves-effect waves-light">
                    Cancel
                    <i class="material-icons right">close</i>
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
