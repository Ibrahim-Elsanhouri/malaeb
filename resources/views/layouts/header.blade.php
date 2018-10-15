<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Original Bootstrap 3.x -->
    <link rel="stylesheet" href="{{ asset('web_asset/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('web_asset/css/bootstrap.min.css') }}">

    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="{{ asset('web_asset/css/bootstrap-rtl.css') }}">

    <!-- Add font-awesome -->
    <link rel="stylesheet" href="{{ asset('web_asset/css/font-awesome.min.css') }}">

    <!-- Add Hover.css File -->
    <link rel="stylesheet" href="{{ asset('web_asset/css/hover.css') }}">

    <!-- Add animate.css File -->
    <link rel="stylesheet" href="{{ asset('web_asset/css/animate.css') }}">

    <!-- Add style.css File -->
    <link rel="stylesheet" href="{{ asset('web_asset/css/style.css') }}">

    <!-- Add Media Query File -->
    <link rel="stylesheet" href="{{ asset('web_asset/css/media.css') }}">

    <link rel="stylesheet" href="{{ asset('web_asset/css/media.css') }}">
    <!-- bootstrap-datetimepicker -->
    <link rel="stylesheet" href="{{ asset('web_asset/css/bootstrap-datetimepicker.min.css') }}">
    <!-- Sweet alert -->
    <link rel="stylesheet" href="{{ asset('web_asset/css/sweetalert.css') }}">
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="{{asset('web_asset/js/html5shiv.js')}}" ></script>
        <script src="{{asset('web_asset/js/respond.min.js')}}" ></script>
        <![endif]-->
</head>

<body>

    <!-- Start Header Section-->
    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('home') }}"><img src="{{asset('web_asset/images/logo.png')}}" class="img-responsive logo"></a> @if (Auth::guest())
                <div class="register" class="regist">
                    <ul>
                        <li><a href="{{ url('/login') }}" class="login hvr-float-shadow"> تسجيل الدخول</a></li>
                        |
                        <li><a href="{{ url('/register') }}" class="new hvr-float-shadow"> مستخدم جديد</a></li>
                    </ul>
                </div>


                @else
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse registered">
                    <ul class="nav navbar-nav navbar-left">
                        <li class="dropdown" style="margin-top: -15px">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="direction: ltr">{{ Auth::user()->name }} <span class="caret"  ></span></a>
                            <ul class="dropdown-menu" role="menu" style=" margin-top: 12px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    border-color: #5a5a7a;">
                                <li style="font-size: 12px;">
                                    <a href="{{ url('/user/'.Auth::id()) }}"><i class="fa fa-btn fa-user" > </i>  &nbsp;  المعلومات الشخصية </a>
                                </li>
                                @if (Auth::user()->can_post())
                                <li style="font-size: 12px;"><a href="{{ url('/profile/'.Auth::id()) }}"><i class="glyphicon glyphicon-search"></i>  &nbsp;الحجوزات </a></li>

                                <li style="font-size: 12px;">
                                    <a href="{{ url('/new-post') }}"><i class="glyphicon glyphicon-comment"></i> &nbsp; إضافة مقال جديد </a>
                                </li>
                                <li style="font-size: 12px;">
                                    <a href="{{ url('/user/'.Auth::id().'/posts') }}"><i class="glyphicon glyphicon-list-alt"></i> &nbsp;   مقالاتي</a>
                                </li>
                                @endif

                                <li style="font-size: 12px;">
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="glyphicon glyphicon-log-out"></i>  &nbsp;  تسجيل الخروج</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>

                                </li>
                            </ul>
                        </li>
                        @if(Auth::user()->image)
                        <li>
                            <img src="{{asset('/'.Auth::user()->image.'')}}" style="width: 80px;
    height: 80px;
    float: left;
    border-radius: 50%;
border:5px solid #767272;">
                        </li>
                        @else
                        <li>
                            <img src="{{asset('uploads/users/iiii.png')}}" style="    width: 80px;
    height: 80px;
    float: left;
    border-radius: 50%;
border:5px solid #767272;
    ">
                        </li>
                        @endif


                    </ul>
                </div>
                @endif

            </div>
        </div>

    </nav>

    <nav class="navbar navbar-inverse">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#anas" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="anas">
                <ul class="nav navbar-nav">
                    <li class="active hvr-pop"><a data-scroll href="{{ url('/') }}">الرئيسية <span class="sr-only">(current)</span></a></li>
                    <li><a data-scroll href="{{ url('/about-us') }}" class="hvr-pop">من نحن</a></li>
                    <li><a data-scroll href="{{ url('/playground') }}" class="hvr-pop">الملاعب</a></li>
                    <li><a data-scroll href="{{ url('/blog') }}" class="hvr-pop">المدونة</a></li>
                    <li><a data-scroll href="{{ url('/offers') }}" class="hvr-pop">العروض</a></li>
                    <li><a data-scroll href="{{ url('/contact-us') }}" class="hvr-pop">تواصل معنا</a></li>
                </ul>
                <ul class="slinks">
                    <li><a href="{{ CustomHelper::siteOptions('snapchat') }}"><img src="{{asset('web_asset/images/ring.png')}}"  alt="ring" class="img-responsive hvr-wobble-vertical"></a></li>
                    <li><a href="{{ CustomHelper::siteOptions('instagram') }}"><img src="{{asset('web_asset/images/instegram.png')}}"  alt="instgram" class="img-responsive hvr-wobble-vertical"></a></li>
                    <li><a href="{{ CustomHelper::siteOptions('twitter') }}"><img src="{{asset('web_asset/images/tw.png')}}"  alt="twitter"  class="img-responsive hvr-wobble-vertical"></a></li>
                    <li><a href="{{ CustomHelper::siteOptions('facebook') }}"><img src="{{asset('web_asset/images/fb.png')}}"   alt="facebook" class="img-responsive hvr-wobble-vertical"></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Header Section-->
    @yield('content')
    <!-- Start Footer Section -->
    <div class="footer clearfix">
        <div class="topFooter clearfix">
            <div class="container">
                <div class="row">
                    
                    <div class="col-md-2 col-sm-12">
                        <div class="loading">
                            <img class="flogo" src="{{asset('web_asset/images/footer-logo.png')}}" alt="footerlogo" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="loading">
                            <h4>قم بتحميل تطبيق نخبة الملاعب الآن</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="loading">
                            <a href="{{ CustomHelper::siteOptions('android') }}">
                                <img class="gp" src="{{asset('web_asset/images/googleplay.jpg')}}" alt="footerlogo" class="img-responsive">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="loading">
                            <a href="{{ CustomHelper::siteOptions('ios') }}">
                                <img class="as" src="{{asset('web_asset/images/appStore.jpg')}}" alt="footerlogo" class="img-responsive">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="middleFooter clearfix">
            <div class="container">
                <div class="row">
                   
   <div class="col-sm-8 col-xs-12">
                        <ul class="agreement">
                            <li><a href="{{ url('agreement') }}">اتفاقيه الاستخدام</a></li>
                            <li><a href="{{ url('terms') }}">الشروط والاحكام</a></li>
                            <li><a href="{{ url('privacy') }}">سياسة الخصوصية</a></li>
                        </ul>
                    </div>

                    <div class="col-sm-3 col-xs-12">
                        <ul class="socials">
                            <li><a href="{{ CustomHelper::siteOptions('facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i></a> </li>

                            <li><a href="{{ CustomHelper::siteOptions('twitter') }}"><i class="fa fa-twitter" aria-hidden="true"></i></a> </li>

                            <li><a href="{{ CustomHelper::siteOptions('instagram') }}"><i class="fa fa-instagram" aria-hidden="true"></i></a> </li>

                            <li><a href="{{ CustomHelper::siteOptions('snapchat') }}"><i class="fa fa-bell-o" aria-hidden="true"></i></a> </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bottomFooter  clearfix">
            <div class="container">
                <div class="row">
                 
                    <div class="col-xs-12 text-center copyright">
                        <h3 class="copyright">جميع الحقوق محفوظة نخبة الملاعب 2017م</h3>
                        <h5 class="copyright">Developed by <a href="http://www.appssquare.com/" target="blank">Apps Square</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Section-->

    <a href="#" id="scrollup"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
    <script src="{{asset('web_asset/js/jquery-1.12.2.min.js')}}"></script>
    <script src="{{asset('web_asset/js/bootstrap.min.js')}}"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="{{asset('web_asset/js/moment.js')}}"></script>
    <script src="{{asset('web_asset/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        // new WOW().init();

    </script>
    <script src="{{asset('web_asset/js/smooth-scroll.js')}}"></script>
    <script>
        smoothScroll.init();

    </script>
    <script src="{{asset('web_asset/js/scrollUp.js')}}"></script>
    
    <script src="{{asset('web_asset/js/main.js')}}"></script>
    
    <script type="text/javascript" charset="utf-8" async defer>
        var homeUrl = '{{url("/")}}';
        window.Laravel = {!!json_encode(['csrfToken' => csrf_token(),]) !!};

    </script>
    <script src="{{asset('web_asset/js/sweetalert.min.js')}}"></script>
</body>

</html>
