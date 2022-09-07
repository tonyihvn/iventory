@extends('guest_template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m12" style="margin-top:20px;">

                <h4 class="card-header text-center" style="text-align:center;">Help and Support</h4>
                <hr>

                <p><b>Help and Support: </b><br>
                    For support or issues using the SI Inventory System send a mail to;<br>
                    Central_HI@ihvnigeria.org<br>
                    Or use any of the HI Whatsapp Group to discuss it.
                </p>
                <p>
                    You can also download a copy of the SOP to help guide you further.<br>
                    <a href="{{url('/uploads/SOP.pdf')}}" class="btn">Click Here to Get a Copy of the SOP Guide (in .PDF)</a>

                </p>



        </div>
    </div>
</div>
@endsection
