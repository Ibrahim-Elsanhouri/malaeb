@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Playgrounds
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($playgrounds, ['route' => ['playgrounds.update', $playgrounds->id], 'method' => 'patch']) !!}

                        @include('playgrounds.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection