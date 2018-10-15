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
@endsection