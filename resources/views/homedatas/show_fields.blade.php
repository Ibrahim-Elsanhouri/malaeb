<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $homedata->id !!}</p>
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{!! $homedata->title !!}</p>
</div>

<!-- Image Field -->
<div class="form-group">
    {!! Form::label('image', 'Image:') !!}
    <p>{!! $homedata->image !!}</p>
</div>

<!-- Visitors Field -->
<div class="form-group">
    {!! Form::label('visitors', 'Visitors:') !!}
    <p>{!! $homedata->visitors !!}</p>
</div>

<!-- Booked Fields Field -->
<div class="form-group">
    {!! Form::label('booked_fields', 'Booked Fields:') !!}
    <p>{!! $homedata->booked_fields !!}</p>
</div>

<!-- Fields Added Field -->
<div class="form-group">
    {!! Form::label('fields_added', 'Fields Added:') !!}
    <p>{!! $homedata->fields_added !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $homedata->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $homedata->updated_at !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $homedata->deleted_at !!}</p>
</div>

