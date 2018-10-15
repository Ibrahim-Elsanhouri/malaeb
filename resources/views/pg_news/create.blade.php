@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pg News
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'pgNews.store']) !!}

                        @include('pg_news.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
