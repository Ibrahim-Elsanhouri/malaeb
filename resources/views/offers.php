@extends('layouts/header')

@section('content')
<!-- Start Contact Us -->
<div class="contact-us" id="contact-us">
    <div class="contacts">
        <img src="{{asset('web_asset/images/contact-icon.png')}}"  alt="contact" class="img-responsive">
        <h1>تواصل معنا</h1>
        <p class="lead center-block">
            اشترك فى قنوات الاتصال الاجتماعية الخاصة بنا أو راسلنا عن
            طريق البريد أو الهاتف ونحن سعداء بتواصلكم.
        </p>
    </div>

    <div class="container">
        <div class="row">



            <form action="contact-us" method="POST" data-toggle="validator" >

                @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif
                <div id="mail-status"></div>
                {{ csrf_field() }}

                <div class="formContent">
                    <div class="col-md-6">

                        <div class="form-group{{$errors->has('name')? ' has-error':''}}">
                            <input type="text" id="name" name="name" value="{{Request::old('name')}}" required class="form-control" data-error="NEW ERROR MESSAGE" placeholder="إسمك">
                            @if ($errors->has('name'))
                                <span class="error">{{ $errors->first('name') }}</span>
                                @endIf
                        </div>

                        <div class="form-group{{$errors->has('email')? ' has-error':''}}">
                            <input type="email" id="email" name="email" value="{{Request::old('email')}}" required  class="form-control" placeholder="البرد الالكترونى">
                            @if ($errors->has('email'))
                                <span class="error">{{ $errors->first('email') }}</span>
                                @endIf
                        </div>



                        <div class="form-group{{$errors->has('message')? ' has-error':''}}">
                            <input type="text" id="message" name="message" value="{{Request::old('message')}}" required class="form-control" placeholder="الرسالة">
                            @if ($errors->has('message'))
                                <span class="error">{{ $errors->first('message') }}</span>
                                @endIf
                        </div>




                        <button class="btn btn-primary btn-lg btn-block" id="btn-submit"><img class="send" src="{{asset('web_asset/images/send-icon.png')}}"  alt="send">إرسال</button>
                    </div>

                    <div class="col-md-6">

                        <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d2965.0824050173574!2d-93.63905729999999!3d41.998507000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sWebFilings%2C+University+Boulevard%2C+Ames%2C+IA!5e0!3m2!1sen!2sus!4v1390839289319" width="100%" height="300px" frameborder="0" style="border:0"></iframe>

                    </div>

                </div>


            </form>



        </div>
    </div>
</div>
<!-- End Contact Us -->
@endsection