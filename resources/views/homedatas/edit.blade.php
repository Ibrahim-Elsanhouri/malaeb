@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Homedata
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($homedata, ['route' => ['homedatas.update', $homedata->id], 'method' => 'patch']) !!}

                        @include('homedatas.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection