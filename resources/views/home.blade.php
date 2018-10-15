@extends('layouts/header') @section('content')
<!-- Start Home Section -->
<?php
session_start();

/* Define how long the maximum amount of time the session can be inactive. */
define("MAX_IDLE_TIME", 3);

function getOnlineUsers() {
    return false;

    if ($directory_handle = opendir(session_save_path())) {
        $count = 0;
        while (false !== ($file = readdir($directory_handle))) {
            if ($file != '.' && $file != '..') {
// Comment the 'if(...){' and '}' lines if you get a significant amount of traffic
                if (time() - fileatime(session_save_path() . '\\' . $file) < MAX_IDLE_TIME * 60) {
                    $count++;
                }
            }
            closedir($directory_handle);

            return $count;

        }
        {
            return false;
        }

    }
}

?>
    <!-- Start Home Section -->
    <div class="home" id="home">
        <div class="container wow bounceInRight">
            <div class="row">
                <div class="overlay">
                    <div class="data">
                        <img src="{{asset('web_asset/images/home-picture.png')}}" alt="picture" class="img-responsive">
                        <h1 class="text-center">استكشف أفضل الملاعب القريبة منك</h1>
                        <p class="lead center-block">
                            تعتبر كرة القدم هى اللعبة الرياضية الأولى على مستوى العالم من حيث الشعبية والمتابعة ، وترجع نشأة هذة اللعبه الى انجلترا عام 1863 عندما بدأت اللعبة بالظهور بعد تأسيس أول اتحاد لكرة القدم فى العالم وهو الاتحاد الإنجليزى.<br>
                        </p>
                        <!-- <div class="button center-block"><a href="#">إقرأ المزيد</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="reservation">
            <div class="container">
                {!! Form::open(['url' => url('/playground'), 'method' => 'get']) !!}
                <div class='row'>
                    <div class="green">

                        <div class='col-md-3 col-xs-12 search-form'>
                            <div class="form-group">
                                <div class='input-group'>
                                    {!! Form::text('pg_name', null, ['class' => 'form-control','placeholder' => 'إبحث بإسم الملعب' ]) !!}
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-search"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12 search-form">
                            <div class="form-group">
                                <div class='input-group'>
                                    {!! Form::text('username', null, ['class' => 'form-control pg-time','placeholder' => 'تاريخ الحجز' ]) !!}
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-12 search-form">
                            <div class="form-group">
                                <div class='input-group'>
                                    {!! Form::select('address', $cities, null, ['class' => 'form-control']) !!}
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-map-marker"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-12 search-form">
                            <div class="form-group">
                                <div class='input-group'>

                                    <button class="btn btn-primary  btn-lg search-button">
                                ابحث عن اقرب ملعب  <i class="glyphicon glyphicon-search"></i>
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- End Home Section -->

    <!-- Start Why Section -->
    <div class="why" id="why">
        <div class="reasons wow bounceInRight" data-wow-duration="1s">
            <img src="{{asset('web_asset/images/why-icon.png')}}" alt="photo1" class="img-responsive">
            <h1>لماذا تطبيق نخبة الملاعب</h1>
            <p>
                تعتبر كرة القدم هى اللعبة الرياضية الأولى على مستوى العالم
            </p>
        </div>

        <div class="container wow bounceInLeft" data-wow-duration="2s">
            <div class="row">
                <div class="col-md-4 col-xs-4">
                    <div class="reason">
                        <img src="{{asset('web_asset/images/stdium-icon.png')}}" alt="photo2" class="img-responsive">
                        <h1>عدد كبير من الملاعب</h1>
                        <p>تعتبر كرة القدم هى اللعبة الرياضية الأولى على مستوى العالم</p>
                    </div>
                </div>

                <div class="col-md-4 col-xs-4">
                    <div class="reason">
                        <img src="{{asset('web_asset/images/player-icon.png')}} " alt="photo3" class="img-responsive">
                        <h1>ميزات التسجيل كلاعب</h1>
                        <p>تعتبر كرة القدم هى اللعبة الرياضية الأولى على مستوى العالم</p>
                    </div>
                </div>

                <div class="col-md-4 col-xs-4">
                    <div class="reason">
                        <img src="{{asset('web_asset/images/captin-icon.png')}}" alt="photo4" class="img-responsive">
                        <h1>ميزات التسجيل كصاحب ملعب</h1>
                        <p>تعتبر كرة القدم هى اللعبة الرياضية الأولى على مستوى العالم</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Why Section -->

    <!-- Start Reserved Section -->
    <div class="reserved" id="reserved">
        <div class="desc wow fadeInDownBig">
            <img src="{{asset('web_asset/images/reserved-icon.png')}}" alt="img1" class="img-responsive">
            <h1>أكثر الملاعب حجــــــزا</h1>
            <p>
                تعتبر كرة القدم هى اللعبة الرياضية الأولى على مستوى العالم
            </p>
        </div>

        <div class="container wow fadeInDownBig">
            <div class="row">
                @foreach($laster as $last) @foreach($last as $llast )
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="mal3ab">
                        <img src="{{$asset($llast->image)}}" alt="mal3ab" class="img-responsive court-new">

                        <div class="info">
                            <h1> {{str_limit( $llast->pg_name,16)}}</h1>
                            <h4>{{str_limit( $llast->address,25)}}</h4>
                            <div class="row">
                                <div class="col-xs-9 col-sm-8">
                                    @for ($i=0; $i < 5; $i++) 
                                        @if( $i < $llast->rating )
                                            <i class="fa fa-star checked"></i>
                                        @else
                                            <i class="fa fa-star"></i>
                                        @endif
                                    @endfor
                                    
                                </div>
                                <div class="col-xs-3 col-sm-4">
                                    <span>{{$llast->price}} ريال</span>
                                </div>
                            </div>
                            <div class="map">
                                <a href="{{url('/playgrounds/'.$llast->id)}}" role="button" class="btn">إحجز الأن</a>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach @endforeach
            </div>
        </div>
    </div>
    <!-- End Reserved Section -->

    <!-- Start Statistics Section-->
    <div class="statistics" id="statistics">
        <div class="static">
            <img src="{{asset('web_asset/images/statistics-logo.png')}}" alt="image1" class="img-responsive">
            <h1>احصائيات التطبيق</h1>
            <p>تعتبر كرة القدم هى اللعبة الرياضية الأولى على مستوى العالم</p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="rect">
                        <img src="{{asset('web_asset/images/mal3ab-icon.png')}}" alt="image2" class="img-responsive">
                        <span>{{$play_ground_count}}</span>
                        <h1>ملعب مضاف</h1>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="rect">
                        <img src="{{asset('web_asset/images/red-logo.png')}}" alt="image3" class="img-responsive">
                        <span>{{$reserv}}</span>
                        <h1>حجز ملعب </h1>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="rect">
                        <img src="{{asset('web_asset/images/visitors-icon.png')}}" alt="image4" class="img-responsive"> @foreach($statistics as $static)
                        <span class="visitor">{{$static->visitors}}</span> @endforeach
                        <h1>زائر </h1>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="rect">
                        <img src="{{asset('web_asset/images/ticket-bg.png')}}" alt="image5" class="img-responsive">
                        <span>1</span>
                        <h1>متواجدون الان </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Statistics Section -->

    <!-- Start mala3eb Section -->
    <div class="mala3eb" id="mala3eb">
        <div class="last">
            <img src="{{asset('web_asset/images/last.png')}}">
            <h1>أخر الملاعب المضافة</h1>
            <p>تعتبر كرة القدم هى اللعبة الرياضية الأولى على مستوى العالم</p>
        </div>



        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="carousel slide multi-item-carousel" id="theCarousel">
                        <div class="carousel-inner">
                            @foreach($playground as $key => $play)
                            <div class="item @if ( $key == 0 ) active @endif">
                                <div class="col-md-4">
                                    <img src="{{ $asset_latest($play->image)}}" alt="mala3eb1" class="img-responsive slider_img">
                                    <div class="carousel-caption">
                                        <h1> {{str_limit( $play->pg_name,16)}}</h1>
                                        <h2> {{str_limit( $play->address,25)}} </h2>
                                        <a href="{{url('/playgrounds/'.$play->id)}}" class="btn btn-default green_btn">
                                                شاهد الملعب الان
                                            <span class="fa fa-eye fa-2x" aria-hidden="true"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <a class="left carousel-control" href="#theCarousel" data-slide="prev">                   
                            <img src="{{asset('web_asset/images/left.png')}}" alt="left arrow"></a>
                        <a class="right carousel-control" href="#theCarousel" data-slide="next">
                          
                                                <img src="{{asset('web_asset/images/right.png')}}" alt="right arrow">
                            </a>
                    </div>
                </div>
            </div>
        </div>






    </div>
    <!-- End mala3eb Section -->


    <!-- Start Contact Us-->
    <div class="contact-us clearfix" id="contact-us">
        <div class="contacts">
            <img src="{{asset('web_asset/images/contact-icon.png')}}" alt="contact" class="img-responsive">
            <h1>تواصل معنا</h1>
            <p class="lead center-block">
                اشترك فى قنوات الاتصال الاجتماعية الخاصة بنا أو راسلنا عن طريق البريد أو الهاتف ونحن سعداء بتواصلكم.
            </p>
        </div>

        <div class="container">
            <div class="row">
                <form id="contact-form" method="post" role="form" accept-charset="utf-8">
                    <div class="formContent">
                        <div class="col-md-6 contact-form">
                            
                                <input type="text" class="form-control" name="name" placeholder="إسمك" required title="This field should not be left blank." >
                                <input type="email" class="form-control" id="email" name="email" placeholder="البرد الالكترونى" required>
                                <input type="text" class="form-control" placeholder="الرسالة" name="message" required data-minlength="10">
                                <input class="btn btn-primary btn-lg btn-block" type="submit" value="إرسال">
                            

                        </div>
                        <div class="col-md-6">
                            <div class="map-responsive">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d463879.2699751238!2d46.54165854039019!3d24.7249303010641!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f03890d489399%3A0xba974d1c98e79fd5!2sRiyadh+Saudi+Arabia!5e0!3m2!1sen!2seg!4v1496655259131" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Contact Us -->

    @if ( $type )
    <div id="confirmation-success"></div>
    @endif @endsection
