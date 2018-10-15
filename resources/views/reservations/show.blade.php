@extends('layouts.header')

@section('content')

    <div class="reserved" id="reserved">
        <div class="row">
            <br/>
        </div>
        <div class="container">


        <div class="panel panel-default">
            <div class="panel-heading" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;"><h3> إحــجــز الأن </h3></div>
            <div class="panel-body"  style="color: #42a104;background-color: #f7fbfb">

                   <div class="form-group">

                       <div ><label for="sel1">إختر الملعب</label></div>
                       <div >
                           <select class="form-control" id="sel1">
                        @foreach($play_grounds as $ground)
                            <option>{{$ground->pg_name}}</option>

                        @endforeach

                           </select></div>
                    </div>

            </div>
        </div>

    </div>


    @endsection