<!-- Pg Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pg_id', 'Pg Id:') !!}
    {!! Form::number('pg_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('time', 'Time:') !!}
    {!! Form::text('time', null, ['class' => 'form-control']) !!}
</div>

<!-- Am Pm Field -->
<div class="form-group col-sm-6">
    {!! Form::label('am_pm', 'Am Pm:') !!}
    {!! Form::text('am_pm', null, ['class' => 'form-control']) !!}
</div>

<!-- From Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('from_datetime', 'From Datetime:') !!}
    {!! Form::date('from_datetime', null, ['class' => 'form-control']) !!}
</div>

<!-- To Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('to_datetime', 'To Datetime:') !!}
    {!! Form::date('to_datetime', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('pgtimes.index') !!}" class="btn btn-default">Cancel</a>
</div>
