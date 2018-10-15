<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Map Lon Field -->
<div class="form-group col-sm-6">
    {!! Form::label('map_lon', 'Map Lon:') !!}
    {!! Form::number('map_lon', null, ['class' => 'form-control']) !!}
</div>

<!-- Map Lat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('map_lat', 'Map Lat:') !!}
    {!! Form::number('map_lat', null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price:') !!}
    {!! Form::number('price', null, ['class' => 'form-control']) !!}
</div>

<!-- Featured Field -->
<div class="form-group col-sm-6">
    {!! Form::label('featured', 'Featured:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('featured', false) !!}
        {!! Form::checkbox('featured', '1', null) !!} 1
    </label>
</div>

<!-- Image Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('image', 'Image:') !!}
    {!! Form::textarea('image', null, ['class' => 'form-control']) !!}
</div>

<!-- Image2 Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('image2', 'Image2:') !!}
    {!! Form::textarea('image2', null, ['class' => 'form-control']) !!}
</div>

<!-- Image3 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image3', 'Image3:') !!}
    {!! Form::text('image3', null, ['class' => 'form-control']) !!}
</div>

<!-- Ground Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ground_type', 'Ground Type:') !!}
    {!! Form::text('ground_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Light Available Field -->
<div class="form-group col-sm-6">
    {!! Form::label('light_available', 'Light Available:') !!}
    {!! Form::text('light_available', null, ['class' => 'form-control']) !!}
</div>

<!-- Football Available Field -->
<div class="form-group col-sm-6">
    {!! Form::label('football_available', 'Football Available:') !!}
    {!! Form::text('football_available', null, ['class' => 'form-control']) !!}
</div>

<!-- Fields Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fields_count', 'Fields Count:') !!}
    {!! Form::text('fields_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Rating Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rating', 'Rating:') !!}
    {!! Form::number('rating', null, ['class' => 'form-control']) !!}
</div>

<!-- Subtitle Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('subtitle', 'Subtitle:') !!}
    {!! Form::textarea('subtitle', null, ['class' => 'form-control']) !!}
</div>

<!-- Reservation Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reservation_count', 'Reservation Count:') !!}
    {!! Form::number('reservation_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('playgrounds.index') !!}" class="btn btn-default">Cancel</a>
</div>
