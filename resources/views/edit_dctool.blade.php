@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Add New Supply</h3>
                

                <form method="POST" action="{{ route('dctools.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $dctool->id }}">                    
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="tool_name" type="text" class="validate" name="tool_name" value="{{ $dctool->tool_name }}">
                            <label for="tool_name">Enter DCTool Name</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field">
                            <textarea id="description" class="materialize-textarea" name="description">{{ $dctool->description }}</textarea>
                            <label for="description">Program Area</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="category" type="text" class="validate" name="category" value="{{ $dctool->category }}">
                            <label for="category">Category</label>
                        </div>
                    </div>

                    <div class="input-field text-right right" style="margin-bottom:20px;">
                        <button type="submit" class="btn">
                            Update DCTool
                        </button>

                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection
