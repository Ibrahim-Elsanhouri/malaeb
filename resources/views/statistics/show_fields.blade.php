<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $statistics->id !!}</p>
</div>

<!-- Visitors Field -->
<div class="form-group">
    {!! Form::label('visitors', 'Visitors:') !!}
    <p>{!! $statistics->visitors !!}</p>
</div>

<!-- Booked Fields Field -->
<div class="form-group">
    {!! Form::label('booked_fields', 'Booked Fields:') !!}
    <p>{!! $statistics->booked_fields !!}</p>
</div>

<!-- Fields Added Field -->
<div class="form-group">
    {!! Form::label('fields_added', 'Fields Added:') !!}
    <p>{!! $statistics->fields_added !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $statistics->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $statistics->updated_at !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $statistics->deleted_at !!}</p>
</div>

