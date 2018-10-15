@extends('layouts.header')

@section('content')
    <div class="reserved" id="reserved">
    <div class="row">
        <br/>
    </div>
    <div class="container">


        <div class="panel panel-default">

            <div class="panel-heading" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;"><h3>{{__('users.personalInfo')}}</h3></div>
            <div class="panel-body"  style="color: #42a104;background-color: #f7fbfb">


                @if(Auth::user()->image)
                    <img src="{{asset('/'.Auth::user()->image.'')}}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">
                @else
                    <img src="{{asset('web_asset/images/home-picture.png')}}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">
                @endif
                <label> الاســم :</label> <span> {{ $user->name }} </span>
                <br/>
                <label> رقم التليفون :</label> <span> {{ $user->mobile }} </span>
                <br/>
                <label> العنوان :</label> <span>{{ $user->city }} </span>
                <br/>
                <label> الصفـة :</label> <span> {{ $get_type($user->type) }} </span>
                <br/>
                
                <!--
                <form enctype="multipart/form-data" action="/profile" method="POST">
                    <label>Update Profile Image</label>
                    <input type="file" name="avatar">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="pull-right btn btn-sm btn-primary">
                </form> -->
            </div>
            @if($author)
            <div class="panel-footer" style="    background-color: #f7fbfb;">

                <a style="color: #4d4d4d;
    text-decoration: none;
    font-size: 17px;"  href="{{ url('/edit-profile/'.$user->id) }}"
                > <span class="glyphicon glyphicon-edit"></span>&nbsp; قـم بتعديل بيانات ملفك الشخصي</a>
            </div>
            @endif
        </div>

        <div>




            <ul class="list-group" >
                <li class="list-group-item" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;">
                    انضم في {{$user->created_at->format('M d,Y الساعة h:i a') }}
                </li>
                <li class="list-group-item panel-body" style="color: #42a104;background-color: #f7fbfb">
                    <table class="table-padding">
                        <style>
                            .table-padding td{
                                padding: 3px 8px;
                            }
                        </style>
                        <tr>
                            <td>كل المقالات</td>
                            <td> {{$posts_count}}</td>
                            @if($author && $posts_count)
                                <td><a href="{{ url('/my-all-posts')}}">إعرض الكل</a></td>
                            @endif
                        </tr>
                    </table>
                </li>
                <li class="list-group-item" style="color: #42a104;background-color: #f7fbfb">
                    عدد التعليقات {{$comments_count}}
                </li>
            </ul>

        </div>


    <div class="panel panel-default" style="display: none">
        <div class="panel-heading" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;"><h3>Latest Posts</h3></div>
        <div class="panel-body"  style="color: #42a104;background-color: #f7fbfb">

            @if(!empty($latest_posts[0]))
                @foreach($latest_posts as $latest_post)
                    <p>
                        <strong><a href="{{ url('/'.$latest_post->slug) }}">{{ $latest_post->title }}</a></strong>
                        <span class="well-sm">في {{ $latest_post->created_at->format('M d,Y الساعة h:i a') }}</span>
                    </p>
                @endforeach
            @else
                <p>لم تقم باضافة اي مواضيع حتى الان.</p>
            @endif
        </div>
    </div>
    <div class="panel panel-default" style="display: none">

        <div class="panel-heading" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;"><h3>آخر التعليقات</h3></div>
        <div class="list-group"  style="color: #42a104;background-color: #f7fbfb">

            @if(!empty($latest_comments[0]))
                @foreach($latest_comments as $latest_comment)
                    <div class="list-group-item">
                        <p>{{ $latest_comment->body }}</p>
                        <p>في {{ $latest_comment->created_at->format('M d,Y \a\t h:i a') }}</p>
                        <p>في مقال <a href="{{ url('/'.$latest_comment->post->slug) }}">{{ $latest_comment->post->title }}</a></p>
                    </div>
                @endforeach
            @else
                <div class="list-group-item">
                    <p>لم تقم باي تعليق.</p>
                </div>
            @endif
        </div>
    </div>
    </div>
    </div>
@endsection