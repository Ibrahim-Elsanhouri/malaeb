@extends('layouts/header')
@section('title')
    تعديل مقال
@endsection
@section('content')
    <div class="reserved" id="reserved" style=" padding-top: 80px ;min-height: 500px;">
        <div class="container">
            @if (Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
            @endif
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading" style="color: #42a104; background-color: #e3f1f1;border-color: #ddd;"><h3>تعديل مقال</h3></div>
                    <div class="panel-body"  style="color: #42a104;background-color: #f7fbfb">

    <form method="post" action='{{ url("update") }}'>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="post_id" value="{{ $post->id }}{{ old('post_id') }}">
        <div class="form-group">
            <input required="required" placeholder="Enter title here" type="text" name = "title" class="form-control" value="@if(!old('title')){{$post->title}}@endif{{ old('title') }}"/>
        </div>
        <div class="form-group">
    <textarea name='body' class="form-control">@if(!old('body')) {{ $post->body }} @endif {{ old('body') }}</textarea>
        </div>
        @if($post->active == '1')
            <input type="submit" name='publish' class="btn btn-success" value = "حفظ"/>
        @else
            <input type="submit" name='publish' class="btn btn-success" value = "Publish"/>
        @endif
        
        <a href="{{  url('delete/'.$post->id.'?_token='.csrf_token()) }}" class="btn btn-danger">حذف</a>
    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection