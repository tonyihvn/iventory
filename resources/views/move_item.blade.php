@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Move / Transfer Item</h3>

                <form method="POST" action="{{ route('movements.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="input-field col s6">
                            <input type="hidden" name="itemid" value="{{ $item->id }}">
                            <input type="hidden" name="old"
                                value="{{ \App\User::select('name')->where('id', $item->user_id)->first()->name ?? '' .' - ' .$item->unit->unit_name .', ' .$item->department->department_name  ?? '' .', ' .$item->facilities->facility_name ?? ''  }}">

                            <input type="text" class="validate" value="{{ $item->item_name }}" readonly>
                        </div>

                        <div class="input-field col s6">
                            <input id="serial_no" type="text" class="validate" name="serial_no"
                                value="{{ $item->serial_no }}" readonly>
                            <label for="serial_no">Serial Number</label>
                        </div>
                    </div>

                    <div class="input-field">
                        <textarea id="reason" class="materialize-textarea" name="reason"></textarea>
                        <label for="reason">Reason for Movement</label>
                    </div>

                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Upload Supporting Docs (e.g. Police Report,etc)</span>
                            <input type="file" name="file[]" multiple>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <select name="from_user" id="from_user" class="select2">
                                <option selected value='{{ $item->user->id }}'>{{ $item->user->name }}</option>
                                @foreach ($users as $user)
                                    <option value='{{ $user->id }}'>{{ $user->name }}</option>
                                @endforeach
                                @if (auth()->user()->role == 'Admin')
                                    <option value="0">Not Listed - Add New User</option>
                                @endif
                            </select>
                            <label class="active">From User</label>
                        </div>

                        <div class="input-field col s6">
                            <select name="to_user" id="to_user" class="select2">
                                <option selected value='{{ $item->user->id }}'>{{ $item->user->name }}</option>
                                @foreach ($users as $user)
                                    <option value='{{ $user->id }}'>{{ $user->name }}</option>
                                @endforeach
                                @if (auth()->user()->role == 'Admin')
                                    <option value="0">Not Listed - Add New User</option>
                                @endif
                            </select>
                            <label class="active">To User</label>
                        </div>
                    </div>

                    @if (auth()->user()->role == 'Admin')
                        <div class="row">
                            <div class="input-field col s6" id="new_from_user_field" style="display:none;">
                                <input id="new_from_user" type="text" class="validate" name="new_from_user" value="">
                                <label for="new_from_user" class="active">Enter New "From" User Name</label>
                            </div>
                            <div class="input-field col s6" id="new_to_user_field" style="display:none;">
                                <input id="new_to_user" type="text" class="validate" name="new_to_user" value="">
                                <label for="new_to_user" class="active">Enter New "To" User Name</label>
                            </div>
                        </div>
                    @endif

                    <h5>New Facility / User Location</h5>
                    <hr>

                    <div class="input-field">
                        <select name="facility" id="facility" materialize="material_select" class="select2">
                            </option>
                            @foreach ($facilities as $facility)
                                <option value='{{ $facility->id }}'>{{ $facility->facility_name }}</option>
                            @endforeach
                        </select>
                        <label>Select Facility</label>
                    </div>

                    <div class="input-field">
                        <select name="department" class="initialized">
                            </option>
                            @foreach ($departments as $department)
                                <option value='{{ $department->id }}'>{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        <label>Select Department</label>
                    </div>

                    <div class="input-field">
                        <select name="unit" class="initialized">
                            @foreach ($units as $unit)
                                <option value='{{ $unit->id }}'>{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
                        <label>Select Unit</label>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <select name="user" class="initialized">
                                <option selected value='{{ $item->user->id }}'>{{ $item->user->name ?? ''  }}</option>
                                @foreach ($users as $user)
                                    <option value='{{ $user->id }}'>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <label>Approved By</label>
                        </div>

                        <div class="input-field col s6">
                            <select name="approved_by" class="initialized">
                                @auth
                                    <option value='{{ auth()->user()->id }}' selected>{{ auth()->user()->name ?? ''  }}</option>
                                @endauth
                                @guest
                                    <option value="1" selected>Added By</option>
                                @endguest

                            </select>
                            <label>Modified By</label>
                        </div>
                    </div>
                    <div class="input-field">
                        <input id="date_moved" type="date" class="datepicker" name="date_moved">
                        <label for="date_moved">Date Moved</label>
                    </div>

                    <div class="input-field text-right right" style="margin-bottom:20px;">

                        <button type="submit" class="btn">
                            Move Item
                        </button>

                    </div>
                </form>


            </div>
        </div>
    </div>

    <script>
        // Reveal the "new user name" input when the Admin picks
        // "Not Listed - Add New User" (value 0) in the From/To user dropdowns.
        (function initMoveUserFields() {
            if (typeof jQuery === 'undefined') {
                setTimeout(initMoveUserFields, 100);
                return;
            }
            jQuery(function ($) {
                function toggle(selectId, fieldId) {
                    var isNew = String($('#' + selectId).val()) === '0';
                    var $field = $('#' + fieldId);
                    if (!$field.length) return;
                    if (isNew) {
                        $field.show();
                    } else {
                        $field.hide();
                        $field.find('input').val('');
                    }
                }

                $('#from_user').on('change', function () { toggle('from_user', 'new_from_user_field'); });
                $('#to_user').on('change', function () { toggle('to_user', 'new_to_user_field'); });
            });
        })();
    </script>
@endsection
