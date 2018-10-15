@extends('layouts.header')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{__('users.resetPassword')}}</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (  app('request')->input('success') )
                        <div class="alert alert-success">
                            {{__('users.passwordSent')}}
                        </div>
                    @endif

                    @if ( $message )
                        <div class="alert alert-{{$alertType}}">
                          {{$message}}
                        </div>
                    @endif

                    @if ( !app('request')->input('success') )
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('reset-password') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{__('users.mobileNumber')}}</label>

                            <div class="col-md-6">
                                {{Form::text( 'mobile','' ,['class' => 'form-control'])}}
                           </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{__('users.sendPassword')}}
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
