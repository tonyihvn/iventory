@extends('guest_template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m12" style="margin-top:20px;">

                <h4 class="card-header text-center" style="text-align:center;">Help and Support</h4>
                <hr>

                <p><b>Help and Support: </b><br>
                    @if($helpPage && $helpPage->support_text)
                        {!! $helpPage->support_text !!}
                    @else
                        For support or issues using the SI Inventory System send a mail to;<br>
                        support@vmss.ng<br>
                        Or use any of the HI Whatsapp Group to discuss it.
                    @endif
                </p>
                
                <h3>Download the Inventory SOP</h3>
                <p>
                    You can also download a copy of the SOP to help guide you further.<br>
                    @if($helpPage && $helpPage->inventory_sop_file)
                        <a href="{{ asset('storage/' . $helpPage->inventory_sop_file) }}" class="btn" download>
                            <i class="material-icons left">get_app</i>Click Here to Get a Copy of the SOP Guide
                        </a>
                    @else
                        <span class="grey-text">Inventory SOP not available yet</span>
                    @endif
                </p>
                
                <hr>
                
                <h3>Download the DCT Tools SOP</h3>
                <p>
                    You can also download a copy of the DCT SOP to help guide you further.<br>
                    @if($helpPage && $helpPage->dct_sop_file)
                        <a href="{{ asset('storage/' . $helpPage->dct_sop_file) }}" class="btn" download>
                            <i class="material-icons left">get_app</i>Click Here to Get a Copy of the DCT SOP Guide
                        </a>
                    @else
                        <span class="grey-text">DCT SOP not available yet</span>
                    @endif
                </p>

                @auth
                @if(auth()->user()->isAdmin() || auth()->user()->role === 'Super')
                    <hr>
                    <p style="text-align: right;">
                        <a href="{{ url('/help-edit') }}" class="btn teal">
                            <i class="material-icons left">edit</i>Edit Help Page
                        </a>
                    </p>
                @endif
                @endauth

        </div>
    </div>
</div>
@endsection
