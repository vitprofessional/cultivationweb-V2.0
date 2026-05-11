@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Governing Body
@endsection
@php
$config =App\Models\ServerConfig::first()
@endphp
@section('frontcontent')
<style>
#myTable th,td{
        text-align:left !important;
        vertical-align:center;
}
#myTable th{
    font-weight:bold;
}
</style>

 <section class="container mt-4">
    <div class="row">
        <div class="col-md-12 text-center con-title my-4">
            <h2 class="hedingAbout wow fadeInLeft animated" data-wow-delay=".60s"><span>Let's have a look the Governing Body of <span>@if(!empty($config->instituteName)){{ $config->instituteName }}@else Jahanara Ayub Academy @endif</span></span> </h2>
        </div>
    </div>
    <div class="row align-items-center">
         <div class="col-10 mx-auto my-4">
            @if($Datakey->count()>0) 
                @foreach($Datakey as $data)
                <table class="table table-bordered">
                    @if(!empty($data->avatar))
                    <td style="width:10%"><img  class="w-100 img-thumbnail" src="{{ config('app.url') }}/public/upload/image/cultivation/{{ $data->avatar }}"></td>
                    @else
                    <td style="width:10%"><img  class="w-100 img-thumbnail" src="{{ config('app.url') }}/public/avatar.png"></td>
                    @endif
                    <td style="width:90%">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width:15%">Name</th>
                                <td colspan="3">: {{$data->fullName}}</td>
                            </tr>
                            <tr>
                                <th style="width:15%">Designation</th>
                                <td colspan="3">: {{$data->designation}}</td>
                            </tr>
                            <tr>
                                <th style="width:15%">Mobile</th>
                                <td style="width:35%">: {{$data->mobile}}</td>
                                <th style="width:15%">Email</th>
                                <td style="width:35%">: {{$data->email}}</td>
                            </tr> 
                        </table>
                    </td>
                </table>

                @endforeach
            @else
                <table class="table table-bordered my-4">
                    <tr class="p-4">
                        <td colspan="6">Sorry! No data found</td>
                    </tr>
                </table>
            @endif  
         </div>
    </div>
</section>
@endsection