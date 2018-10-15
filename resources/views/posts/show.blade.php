@extends('layouts/header')

@section('title-meta')
    <p>{{ $post->created_at->format('بتاريخ M d,Y الساعة h:i a') }} بواسطة <a href="{{ url('user/'.$post->author_id)}}">{{ $author($post->author_id) }}</a></p>
@endsection
@section('content')
    <div class="reserved" id="reserved" style=" padding-top: 80px ;min-height: 500px;">
        <div class="container">

            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-heading" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;"><h3><p>{{ $post->created_at->format('بتاريخ M d,Y الساعة h:i a') }} بواسطة <a href="{{ url('user/'.$post->author_id)}}">{{ $author($post->author_id) }}</a></p></h3>
                        @if(!Auth::guest() && ($post->author_id == Auth::user()->id || CustomHelper::is_admin()))
                                <button class="btn" ><a href="{{ url('edit/'.$post->slug)}}">تحرير المقالة</a></button>
                        @endif
                    </div>

        <div class="panel-body"  style="color: #42a104;background-color: #f7fbfb">

    @if($post)
        <div >
            <ul class="list-group">
                <li class="list-group-item">
                  <span> {!! $post->body !!}</span>
                </li>

            </ul>
        </div>
        <h3>التعليقات</h3>
        <div>
            @if($comments)
                <ul style="list-style: none; padding: 0">
                    @foreach($comments as $comment)
                        <li class="panel-body">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <h3>{{ $author($post->$comment->author_id) }}</h3>
                                    <p>{{ $comment->created_at->format('بتاريخ M d,Y الساعة h:i a') }}</p>
                                </div>
                                <div class="list-group-item">
                                    <p>{{ $comment->body }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div>
            <div>اترك تعليقاً</div>
        </div>
        @if(Auth::guest())
            <p>سجل لتترك تعليقاً</p>
        @else
            <div class="panel-body">
                <form method="post" action="{{ route('AddComment') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="on_post" value="{{ $post->id }}">
                    <input type="hidden" name="slug" value="{{ $post->slug }}">
                    <div class="form-group">
                        <textarea required="required" placeholder="اكتب التعليق هنا" name = "body" class="form-control"></textarea>
                    </div>
                    <input type="submit" name='post_comment' class="btn btn-success" value = "ارسل"/>
                </form>
            </div>
        @endif
        
    @else
        404 error
    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection