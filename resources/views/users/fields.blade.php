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

<<<<<<< HEAD
<!-- Password Field -->
<div class="form-group col-sm-6 ">
    {!! Form::label('password', 'Password') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>

<!-- Confirmation Password Field -->
<div class="form-group col-sm-6 ">
      {!! Form::label('password_confirmation', 'Password Confirmation') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
=======
<!-- Avatar Field -->
<div class="form-group col-sm-6">
    {!! Form::label('avatar', trans('staff.avatar')) !!}
    {!! Form::file('avatar', ['class' => 'form-control', 'onchange' => 'previewAvatar(event)']) !!}
    <img id="avatar-preview" src="#" alt="Preview" style="max-width: 200px; margin-top: 10px; display: none;">
>>>>>>> 2f483590b841b591e0eb9ecc64e6d81d2bb1f1b9
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
</div>
