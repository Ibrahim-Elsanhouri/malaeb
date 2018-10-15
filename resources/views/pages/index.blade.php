@extends('layouts.header')

@section('content')
<div class="home pages" id="home">
    <div class="container wow bounceInRight">
        <div class="row">
            <div class="overlay pages">
                <div class="data pages">
                    <h1 class="text-center">{{$data->name}}</h1>
                    <p class="lead center-block">
                        {!! $data->content!!}
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection