<!-- firstName Field -->
<div class="form-group col-sm-6  ">
    {!! Form::label('first_name', trans('staff.name.first_name')) !!}

    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
</div>

<!-- lastName Field -->
<div class="form-group col-sm-6  ">
    {!! Form::label('last_name', trans('staff.name.last_name')) !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Avatar Field -->
<div class="form-group col-sm-6">
    {!! Form::label('avatar', trans('staff.avatar')) !!}
    {!! Form::file('avatar', ['class' => 'form-control', 'onchange' => 'previewAvatar(event)']) !!}
    <img id="avatar-preview" src="#" alt="Preview" style="max-width: 200px; margin-top: 10px; display: none;">
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
</div>
