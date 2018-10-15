@extends('layouts/header')

@section('content')

<script type="text/javascript" charset="utf-8" async defer>
    var lang_vars = {
     "notLoggedin":"<?php echo __('playgrounds.pleaseLogin');?>",
     "sorry":"<?php echo __('playgrounds.sorry');?>",
     "login":"<?php echo __('playgrounds.login');?>",
     "bookingSuccess":"<?php echo __('playgrounds.bookingSuccess');?>",
     "toBookingPage":"<?php echo __('playgrounds.toBookingPage');?>",
     };
</script>

<div class="reserved">
    <div class="row">
        <div class="desc wow fadeInDownBig">
            <h1>{{$playgrounds->pg_name}}</h1>
        </div>
        <div class="container wow fadeInDownBig">
            <div class="row">

                <div id="pgslider" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#pgslider" data-slide-to="0" class="active"></li>
                        @if(isset($playgrounds->pg_images) && $playgrounds->pg_images != null)
                            @foreach ($playgrounds->pg_images as $key => $img) 
                            <li data-target="#pgslider" data-slide-to="{{$key+1}}"></li>
                            @endforeach
                        @endif
                    </ol>
                    <!-- Wrapper for slides -->

                    <div class="carousel-inner">
                        <div class="item active">
                            @if(isset($playgrounds->image) && $playgrounds->image != null)
                                <img src="{{asset('/'.$playgrounds->image.'')}}" alt="Chicago" class="img-responsive" alt="Cinque Terre" style="width: 100%;max-height: 446px;"> 
                            @else
                                <img src="{{asset('web_asset/images/elmala3eb1.jpg')}}" alt="Chicago" class="img-responsive" alt="Cinque Terre" style="width: 100%;max-height: 446px;">
                            @endif
                        </div>
                        @if(isset($playgrounds->pg_images) && $playgrounds->pg_images != null)
                            @foreach ($playgrounds->pg_images as $key => $img) 
                                <div class="item">
                                    <img src="{{asset('/'.$img.'')}}" class="img-responsive" style="max-height: 446px;">
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>

                
            </div>
            <br/>
        </div>
        <!-- <div class="container">-->
        <div class="container playground-details">
            <div class="row">
                <div class="panel with-nav-tabs panel-success">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1success" data-toggle="tab">{{__('playgrounds.details')}}</a></li>
                            <li><a href="#pg-tab2" data-toggle="tab">{{__('playgrounds.info')}}</a></li>
                            <li><a href="#pg-tab3" data-toggle="tab">{{__('playgrounds.pgtimes')}}</a></li>
                            <li><a href="#pg-tab4" data-toggle="tab">{{__('playgrounds.news')}}</a></li>
                            <li><a href="#pg-tab5" class="map-tab" data-toggle="tab">{{__('playgrounds.map')}}</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1success">
                                <div class="form-group row">
                                    <label class="col-md-3"> {{__('playgrounds.address')}}: </label>
                                    <span class="col-md-9"> {{$playgrounds->address}}</span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3"> {{__('playgrounds.pg_numberoffields')}}: </label>
                                    <span class="col-md-9"> {{$playgrounds->pg_numberoffields}}</span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3"> {{__('playgrounds.price')}}: </label>
                                    <span class="col-md-9"> {{$playgrounds->price}} {{__('playgrounds.currency')}}</span>
                                </div>

                                <div class="text-center">
                                    <a class="btn btn-danger book-now btn-lg center">{{__('playgrounds.book_now')}}</a>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pg-tab2">
                                <div class="form-group row">
                                    <label class="col-md-3"> {{__('playgrounds.hasFootball')}}: </label>
                                    <span class="col-md-9">
                                        @if ( $playgrounds->football_available )
                                            <i class="fa fa-check-circle-o fa-2x fa-green" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-times-circle-o fa-2x fa-red" aria-hidden="true"></i>
                                        @endif

                                    </span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3"> {{__('playgrounds.hasLight')}}: </label>
                                    <span class="col-md-9">
                                        @if ( $playgrounds->light_available )
                                            <i class="fa fa-check-circle-o fa-2x fa-green" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-times-circle-o fa-2x fa-red" aria-hidden="true"></i>
                                        @endif

                                    </span>
                                </div>

                                <div class="text-center">
                                    <a class="btn btn-danger book-now btn-lg center">{{__('playgrounds.book_now')}}</a>
                                </div>                                
                            </div>
                            <div class="tab-pane fade" id="pg-tab3">

                            <form action="" id="times-table" method="post" accept-charset="utf-8">
                            <input type="hidden" name="pg_id" value="{{$playgrounds->id}}">
                            
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    @if ( !empty( $playgrounds->pgtimes['times'] ) )
                                        @foreach ($playgrounds->pgtimes['times'] as $key => $dates)
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="heading_{{$key}}">
                                                    <h4 class="panel-title">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                                                              {{DTH::dayDate($dates['date'])}}
                                                            </a>
                                                          </h4>
                                                </div>
                                                <div id="collapse_{{$key}}" class="panel-collapse collapse @if ( $key == 0 ) echo 'in'; @endif" role="tabpanel" aria-labelledby="heading_{{$key}}">
                                                        <div class="panel-body">
                                                            <ul>
                                                                @foreach ($dates['times'] as $time)
                                                                    @foreach ($time as $ampm)
                                                                        <li class="row"  >
                                                                            <span class="col-md-1 " data-toggle="buttons">
                                                                                <span class="btn btn-default btn-sm">
                                                                                     <input type="checkbox" id="times" name="times[]" value="{{$ampm['id']}}">
                                                                                     <span class="glyphicon glyphicon-ok"></span>
                                                                                 </span>
                                                                            </span><!--{{ date( 'a', strtotime($ampm['from_datetime'])) }} -->
                                                                             <span class="col-md-2">{{$ampm['time']}} </span>
                                                                             <span class="col-md-9">     
                                                                             
                                                                                <!-- <a class="btn btn-default book-time" data-id="{{$ampm['id']}}" data-pg_id="{{$ampm['pg_id']}}" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> {{__('playgrounds.pleaseWait')}}">{{__('playgrounds.bookTime')}}</a> -->
                                                                                <span class="alert-info book-time-info">  {{__('playgrounds.pleaseLogin')}}</span>
                                                                             </span>
                                                                        </li>
                                                                    @endforeach
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <h2>لا توجد مواعيد متاحة حاليا.</h2>
                                    @endif
                                        <div class="text-center">

                                            <button type="submit" class="btn btn-danger book-now btn-lg center">{{__('playgrounds.book_now')}}</button>
                                        </div>                                                        
                                    </form>    
                                </div>

                            </div>
                            <div class="tab-pane fade" id="pg-tab4">
                                <ul>
                                    @if ( !empty( $playgrounds->pgtimes['news'] ) )
                                        @foreach ($playgrounds->pgtimes['news'] as $key => $news)
                                            <li>{{$news['title']}}</li>
                                        @endforeach
                                    @else
                                        <h2>لا توجد اخبار حاليا.</h2>
                                    @endif
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="pg-tab5">
                                <div id="map"></div>
                                    <script>
                                       
                                      function initMap() {
                                        var pg = {lat: {{$playgrounds->map_lat}}, lng: {{$playgrounds->map_lon}} };
                                        console.log(pg)
                                        var map = new google.maps.Map(document.getElementById('map'), {
                                          zoom: 16,
                                          center: pg
                                        });
                                        var marker = new google.maps.Marker({
                                          position: pg,
                                          map: map
                                        });
                                      }
                                    </script>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfjJ4FkKisaPGUFSZE4EEHukw-wbXfefs"></script>
</div>
@endsection