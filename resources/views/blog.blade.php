@extends('layouts.header')

@section('content')


    @if ( !$posts->count() )
    <div class="reserved" id="reserved" style=" padding-top: 80px ;min-height: 200px;">
    <div class="container">
        <div class="alert alert-info">عفواً  لا يوجد مقالات جديده.</div>
        </div>
    </div>
        
    @else

        <div class="reserved" id="reserved" style=" padding-top: 80px ;min-height: 500px;">
            <div class="container">
                @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
                <div class="row">
                    <div class="panel panel-default">

                        <div class="panel-heading" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;"><h3>
                            @if( $title )
                            مقالات {{$title}}
                            @else
                            المدونة
                            @endif
                         </h3></div>
                        <div class="panel-body"  style="color: #42a104;background-color: #f7fbfb">



            @foreach( $posts as $post )
                <div class="list-group">
                    <div class="list-group-item">
                        <h3>
                            <a href="{{ url('blog/'.$post->slug) }}">{{ $post->title }}</a>
                            @if(!Auth::guest() && ($post->author_id == Auth::user()->id ))
                                @if($post->active == '1')
                                    <button class="btn"><a href="{{ url('edit/'.$post->slug)}}">تعديل</a></button>
                                @else
                                    <button class="btn" style="float: right"><a href="{{ url('edit/'.$post->slug)}}">Edit Draft</a></button>
                                @endif
                            @endif
                        </h3>
                        @if(Auth::guest() )
                            <p>{{ $post->created_at->format('M d,Y في h:i a') }} بواسطة <a>{{ $post->author->name }}</a></p>
                        @else
                            <p>{{ $post->created_at->format('M d,Y في h:i a') }} بواسطة <a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a></p>
                        @endif

                    </div>
                    <div class="list-group-item">
                        <article>
                            {!! str_limit($post->body, $limit = 1500, $end = '....... <a href='.url("blog/".$post->slug).'>Read More</a>') !!}
                        </article>
                    </div>
                </div>
            @endforeach
          <center>{!! $posts->render() !!} </center>
        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
@endsection