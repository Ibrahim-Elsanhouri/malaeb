@extends('layouts/header')

@section('content')
<!-- Start Contact Us -->
<div class="reserved" id="reserved">
    <div class="desc wow fadeInDownBig">
        <h1>العروض</h1>
        <p>
            العروض المقدمة من الملاعب محدثة دائماً
        </p>
    </div>
    <div class="container wow fadeInDownBig">
        <div class="row">
            @foreach ($offers as $offer)
                <div class="col-md-6 col-sm-6 col-xs-12">

                    <div class="mal3ab">
                        <a href="{{ $offer->url }}" >
                        <img src="{{ asset($offer->image) }}" alt="mal3ab" class="img-responsive court-new">
                        <div class="info">
                            <h1>{{ $offer->title }}</h1>
                            <div class="sub-title">عرض بتاريخ {{ date('d-m-Y', strtotime($offer->created_at)) }}</div>
                            <div class="row">
                                <div class="col-xs-9 col-sm-12">
                                  {!! $offer->details !!}
                                </div>
                            </div>
                            
                        </div>
                    </a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
<!-- End Contact Us -->
<!-- End Contact Us -->
@endsection