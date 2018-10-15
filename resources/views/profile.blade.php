
@extends('layouts.header')

@section('content')


        @if (count($reser) === 0)
            <div class="alert alert-danger">
                 <strong>لا يوجد حجوزات </strong>
            </div>
        @else


<?php $i=1?>

                <div class="container profile-reservation">
                   <center><h2> عرض الحجوزات </h2> </center>

                    @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
                    <table class="table table-striped">
                    <style>
                        .table-padding td{
                            padding: 3px 8px;
                        }
                    </style>
                    <tr>
                        <td>#</td>
                        <td> إسم الملعب</td>
                        <td> الوقت</td>
                        <td> حالة الحجز</td>


                    </tr>
                    @foreach($reser as $reservation)

                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$reservation->pg_name}}</td>
                        <td>{{$reservation->time}} {{ date('F d, Y', strtotime($reservation->from_datetime))}}   {{$reservation->am_pm}}  </td>
                         @if($reservation->confirmed == 1)
                            <td class="alert alert-success" ><span class="glyphicon glyphicon-ok"></span> مؤكد </td>

                            @elseif( $reservation->attendance == 1 )
                            <td class="alert alert-warning" ><span class="glyphicon glyphicon-warning-sign"></span> مؤكد وتم الحضور  </td>
                            @else
                            <td class="alert alert-warning" ><span class="glyphicon glyphicon-warning-sign"></span> في إنتظار التأكيد   <a href="http://malaeb.com/reservation/delete/{{ $reservation->ID }}" class="btn btn-danger">إلغاء الحجز</a></td>
                            @endif



                    </tr>
                            <?php $i++?>
                    @endforeach

                </table>

                </div>

        @endif
    <!--
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <img src="/uploads/avatars/{{ $user->name }}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">
                <h2>{{ $user->name }}'s Profile</h2>
                <form enctype="multipart/form-data" action="/profile" method="POST">
                    <label>Update Profile Image</label>
                    <input type="file" name="avatar">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="pull-right btn btn-sm btn-primary">
                </form>
            </div>
        </div>
    </div>
-->


@endsection