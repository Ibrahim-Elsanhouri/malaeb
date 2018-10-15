$(document).ready(function() {
     $(document).on('click', 'a.map-tab', function(event) {
                                            initMap()
                                        });
    $('input.pg-time').datetimepicker({
        format: 'DD/MM/YYYY'

    });

    $('a.btn.book-now').click(function(e) {
        e.preventDefault()
        $('.nav-tabs a[href="#pg-tab3"]').tab('show')
    });

    $('form#contact-form').submit(function(event) {
        event.preventDefault();
        var data = {
            'name': $('input[name=name]').val(),
            'email': $('input[name=email]').val(),
            'message': $('input[name=message]').val()
        };
        console.log(data)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: homeUrl + '/contactus',
            type: 'POST',
            data: data,
            success: function(data) {
                $('.contact-form').html('<div class="alert alert-success fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>شكرا لك!</strong> تم ارسال الرسالة بنجاح.</div>');
            }
        });

        return false;
        /* Act on the event */
    });

    $('form#times-table').submit(function(e) {
        e.preventDefault();
        var val = [];
        $('form#times-table :checkbox:checked').each(function(i) {
            val[i] = $(this).val();
        });
        var $this = $('button.book-now');
        $this.button('loading');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data = {
            'time_ids': val,
            'pg_id': $('input[name=pg_id]').val()

        };
        $.ajax({
            url: homeUrl + '/booknow',
            type: 'POST',
            data: data,
            success: function(data) {
                $this.button('reset');
                if (!data.success && data.message == 'User not logged in.') {
                    swal({
                            title: lang_vars.sorry,
                            text: lang_vars.notLoggedin,
                            type: "error",
                            confirmButtonText: lang_vars.login
                        },
                        function() {
                            window.location.href = '/login';
                        });


                } else if (data.success) {
                    console.log(11111111111)
                    swal({

                            title: lang_vars.bookingSuccess,
                            type: "success",
                            confirmButtonText: lang_vars.toBookingPage
                        },
                        function() {
                            window.location.href = '/profile/' + data.data;
                        });
                }

            },
            error: function(data) {
                // Error...
                var errors = data.responseJSON;
                if (error.message == "User not logged in.") {}
                console.log(11111111111111111)
                console.log(errors);

                $.each(errors, function(index, value) {
                    $.gritter.add({
                        title: 'Error',
                        text: value
                    });
                });
            }
        });

        console.log($(this).attr('data-id'));
    });

    if ($('#confirmation-success').length) {
        swal({
            title: 'تمت عملية التسجيل بنجاح',
            text: 'باستطاعتك الان تصفح الموقع والملاعب والحجز.',
            type: "success",
            confirmButtonText: 'إغلاق'
        });
    }


    // Multi slider

    // Instantiate the Bootstrap carousel
    $('.multi-item-carousel').carousel({
        interval: false
    });

    // for every slide in carousel, copy the next slide's item in the slide.
    // Do the same for the next, next item.
    $('.multi-item-carousel .item').each(function() {
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        if (next.next().length > 0) {
            next.next().children(':first-child').clone().appendTo($(this));
        } else {
            $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
        }
    });


});





function initialize(lat, log) {

    var lat = lat;
    var lng = log;

    var myOptions = {
        center: new google.maps.LatLng(lat, lng),
        zoom: 8,
        // mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    var myMarkerLatlng = new google.maps.LatLng(lat, lng);
    var marker = new google.maps.Marker({
        position: myMarkerLatlng,
        map: map,
        title: 'Hello World!'
    });

    //  $('#map_canvas').html('<iframe src="initialize()" width="100%" height="100%" frameborder="0" style="border:0"></iframe>');

}

$('#myModal').on('show.bs.modal', function(e) {
    cosol.log(e)
    var mapOptions = {
        center: new google.maps.LatLng(19.0606917, 72.83624970000005),
        zoom: 18,
        //                mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map($("#map_canvas")[0], mapOptions);

});