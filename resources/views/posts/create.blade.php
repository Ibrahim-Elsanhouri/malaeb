@extends('layouts.header')
@section('title')
    اضف مقال جديد
@endsection
@section('content')
    <script type="text/javascript" src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector : "textarea",
            plugins : ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"],
            toolbar : "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        });
    </script>
    <div class="reserved" id="reserved" style=" padding-top: 80px ;min-height: 500px;">
        <div class="container">
            @if (Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">{{$errors->first()}}</div>
            @endif

       <div class="row">
           <div class="panel-heading" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;"><h3>أضف مقال</h3></div>
           <div class="panel-body"  style="color: #42a104;background-color: #f7fbfb">
          <div class="panel-body">
        <form action="new-post" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <input required="required" value="{{ old('title') }}" placeholder="اسم المقال هنا" type="text" name = "title"class="form-control" />
        </div>
        <div class="form-group">
            <textarea name='body'class="form-control" placeholder="نص المقال">{{ old('body') }}</textarea>
        </div>
        <input type="submit" name='publish' class="btn btn-success" value = "نشر"/>
         </form>
        </div>
            </div>
        </div>
    </div>
    </div>
@endsection