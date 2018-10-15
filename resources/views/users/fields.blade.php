<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Mobile Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mobile', 'Mobile:') !!}
    {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
</div>

<!-- City Field -->
<div class="form-group col-sm-6">
    {!! Form::label('city', 'City:') !!}
    {!! Form::text('city', null, ['class' => 'form-control']) !!}
</div>

<!-- Area Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area', 'Area:') !!}
    {!! Form::text('area', null, ['class' => 'form-control']) !!}
</div>

<!-- Pg Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pg_type', 'Pg Type:') !!}
    {!! Form::text('pg_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- Team Field -->
<div class="form-group col-sm-6">
    {!! Form::label('team', 'Team:') !!}
    {!! Form::number('team', null, ['class' => 'form-control']) !!}
</div>

<!-- Likes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('likes', 'Likes:') !!}
    {!! Form::number('likes', null, ['class' => 'form-control']) !!}
</div>

<!-- Birth Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('birth_date', 'Birth Date:') !!}
    {!! Form::date('birth_date', null, ['class' => 'form-control']) !!}
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

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>

<!-- Remember Token Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remember_token', 'Remember Token:') !!}
    {!! Form::text('remember_token', null, ['class' => 'form-control']) !!}
</div>

<!-- Api Token Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_token', 'Api Token:') !!}
    {!! Form::text('api_token', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
</div>
