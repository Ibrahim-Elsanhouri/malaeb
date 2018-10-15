@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Teams
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($teams, ['route' => ['teams.update', $teams->id], 'method' => 'patch']) !!}

                        @include('teams.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection