@extends('layouts.header')

@section('content')

    <div class="reserved" id="reserved">
        <div class="row">
            <br/>
        </div>
        <div class="container">


            <div class="panel panel-default">

                <div class="panel-heading" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;"><h3> <span class="glyphicon glyphicon-edit" style="font-size: 19px;"></span>&nbsp; {{__('users.confirmForm')}} </h3> </div>
                <div class="panel-body"  style="color: #42a104;background-color: #f7fbfb">
                @if ( $success )
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{__('users.codeSent')}}
                </div>
                @endif

                 {{Form::open(array('url' => '/confirm', 'class' => 'form-horizontal' , 'method' => 'post','style'=>'margin-right: 18%;margin-left: 22%;margin-top: 25px'))}}
                 {!! csrf_field() !!}

                            @if ( $error )
                                <div class="alert alert-danger">
                                  {{$error}}
                                </div>
                            @endif

                            @if ( $message )
                                <div class="alert alert-{{$alertType}}">
                                  {{$message}}
                                </div>
                            @else
                               <div class="form-group">
                                    <label for="name">{{__('users.confirmCode')}}</label>
                                    <input type="text" class="form-control" name="code" value="" placeholder="{{__('users.confirmCodeInput')}}" required>
                                    <input type="hidden" name="id" value="{{$user_id}}">
                               </div>

                               <div class="form-group">
                                 {{ Form::submit(__('users.confirmSubmit'), array('class'=>'btn btn-large btn-primary btn-block'))}}
                               </div>
                            @endif



                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection