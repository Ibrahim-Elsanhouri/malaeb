
@extends('layouts.header')

@section('content')
                <div class="container">
                  <h1>{{ $data->title }}</h1>
                <div class="row">
	                <div class="col-md-12">
	                	<h1>{!! $data->content !!}</h1>
	                </div>
                </div>
                </div>
@endsection