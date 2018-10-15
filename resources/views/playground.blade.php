@extends('layouts/header')

@section('content')

    <div class="reserved" id="reserved">
        <div class="desc wow fadeInDownBig">
            <img src="{{asset('web_asset/images/reserved-icon.png')}}"  alt="img1" class="img-responsive">
            <h1> الملاعب المضافة </h1>
            <p>تعتبر كرة القدم هى اللعبة الرياضية الأولى على مستوى العالم</p>
        </div>

        <div class="container wow fadeInDownBig">
            <div class="row">
                @foreach($playgrounds as $playground)
                    <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="mal3ab">
                        <img src="{{$asset($playground->image)}}" alt="mal3ab" class="img-responsive court-new">

                        <div class="info">
                            <h1> {{str_limit( $playground->pg_name,16)}}</h1>
                            <h4>{{str_limit( $playground->address,25)}}</h4>
                            <div class="row">
                                <div class="col-xs-9 col-sm-8">
                                    @for ($i=0; $i < 5; $i++) 
                                        @if( $i < $playground->rating )
                                            <i class="fa fa-star checked"></i>
                                        @else
                                            <i class="fa fa-star"></i>
                                        @endif
                                    @endfor
                                    
                                </div>
                                <div class="col-xs-3 col-sm-4">
                                    <span>{{$playground->price}} ريال</span>
                                </div>
                            </div>
                            <div class="map">
                                <a href="{{url('/playgrounds/'.$playground->id)}}" role="button" class="btn">إحجز الأن</a>
                            </div>

                        </div>
                    </div>
                </div>
                    @endforeach
            </div>
            <div class="pager">
            {{ $playgrounds->links() }}
                </div>
        </div>



    </div>


@endsection
