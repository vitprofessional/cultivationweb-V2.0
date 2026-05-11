@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
About Us
@endsection
@php
$config =App\Models\ServerConfig::first()
@endphp
@section('frontcontent')
<style>
    .hedingAbout{
        text-align:center;
        margin-bottom:50px;
        
    }
   .principal img{
        width:100%;
        height:400px;
        border-radius:100%;
        text-align:center;
        margin-top:30px; 
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        
    }
    .principalspace{
        width:100%;
        height:auto;
        text-align:justify;
        margin:auto;
        font-family:Raleway;
        font-size:15px;
        padding-top:30px;
        padding-bottom:30px;
        line-height:29px;
        
    }
</style>

 <section class="my-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center con-title mt-4">
                <h1 class="wow fadeInLeft animated my-4" data-wow-delay=".60s">Let's have a look about <span>@if(!empty($config->instituteName)){{ $config->instituteName }}@else Jahanara Ayub Academy @endif</span></h1>
           </div>
        </div>
        @if($data)
        <div class="row my-4">
            <div class="col-md-12 text-center con-title">
                <h2  class=" wow fadeInLeft animated" data-wow-delay=".60s">{{$data->insHeadline}}</h2>
           </div>
        </div>

        <div class="row align-items-center mt-0">
             <div class="col-md-8 col-12 mx-auto">
                <img  class="w-75 wow fadeIn animated" data-wow-delay="1s" src="{{ config('app.url') }}/public/upload/image/cultivation/{{ $data->heroImg}}">
           </div>
             <div class="col-md-10 col-12 mx-auto">
                <h4 class="mt-4">About Us</h4>
                 <p class="wow fadeIn animated" data-wow-delay="1s" >  {{$data->insDetails}}
                 </p>
                 <h4>Establish Date</h4>
                 <p class="wow fadeIn animated" data-wow-delay="1s" >  
                 @php
                    $aboutEstYear='';
                    if(!empty($data->establishDate)){
                        try { $aboutEstYear = \Carbon\Carbon::parse($data->establishDate)->format('Y'); }
                        catch(Exception $e){ if(preg_match('/(19|20)\d{2}/',$data->establishDate,$m)){ $aboutEstYear = $m[0]; } }
                    }
                 @endphp
                 {{$aboutEstYear}}</p>
                 <h4>Total Area</h4>
                 <p class="wow fadeIn animated" data-wow-delay="1s" >  
                 {{$data->landSize}}</p>
                 <h4>Our Mission</h4>
                 <p class="wow fadeIn animated" data-wow-delay="1s" > 
                 {{$data->mission}}</p>
                 <h4>Our Vision</h4>
                 <p class="wow fadeIn animated" data-wow-delay="1s" > 
                 {{$data->vision}}</p>
            </div>    
        </div>
        @else
            <div class="alert alert-info">Sorry! No data found!</div>
        @endif
    </div>
</section>

   


@endsection