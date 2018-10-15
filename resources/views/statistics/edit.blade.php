@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Statistics
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($statistics, ['route' => ['statistics.update', $statistics->id], 'method' => 'patch']) !!}

                        @include('statistics.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection