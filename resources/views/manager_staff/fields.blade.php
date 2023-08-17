<div class="row">
    <div class="col-md-5 mx-auto">
        <!-- Username Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="name">{{ trans('staff.name.name') }}</label>
            <input type="name" name="name" id="name" class="form-control col-sm-5" value="{{ $user->name }}" />
        </div>

        <!-- First name Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="first_name">{{ trans('staff.name.first_name') }}</label>
            <input type="name" name="first_name" id="first_name" class="form-control col-sm-5"
                value="{{ $user->first_name }}" />
        </div>

        <!-- Last name Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="last_name">{{ trans('staff.name.last_name') }}</label>
            <input type="name" name="last_name" id="last_name" class="form-control col-sm-5"
                value="{{ $user->last_name }}" />
        </div>

        <!-- Email Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="email">{{ trans('staff.email') }}</label>
            <input type="email" name = "email" id="email" class="form-control col-sm-5"
                value="{{ $user->email }}" />
        </div>

        <!-- Code Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="code">{{ trans('staff.code') }}</label>
            <input type="text" name="code" id="code" class="form-control col-sm-5"
                value="{{ $user->code  ?? null}}" />
        </div>

        <!-- Start Date Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="start_date">{{ trans('staff.start_date') }}</label>
            <input type="date" name="start_date" id="start_date" class="form-control col-sm-5"
                value="{{ $user->start_date }}" />
        </div>

        <!-- Official Start Date Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label"
                for="official_start_date">{{ trans('staff.official_start_date') }}</label>
            <input type="date" name="official_start_date" id="official_start_date" class="form-control col-sm-5"
                value="{{ $user->official_start_date }}" />
        </div>

        <!-- Gender Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="gender">{{ trans('staff.genders.name') }}</label>
            <select name="gender" id="gender" class="form-control col-sm-5">
                <option value="1" {{ $user->gender == 1 ? 'selected' : '' }}>{{ trans('staff.genders.male') }}
                </option>
                <option value="2" {{ $user->gender == 2 ? 'selected' : '' }}>{{ trans('staff.genders.female') }}
                </option>
            </select>
        </div>
        
         <!-- Submit Field -->
         <div class="form-group col-sm-5 ">
            {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('manager_staff.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
        </div>
    </div>

    <div class="col-md-5 mx-auto">
        <!-- Dependent Person Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="dependent_person">{{ trans('staff.dependent_person') }}</label>
            <input type="text" name="dependent_person" id="dependent_person" class="form-control col-sm-5"
                value="{{ $user->dependent_person }}" readonly />
        </div>

        <!-- Contract Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="contract">{{ trans('staff.contract.name') }}</label>
            <select name="contract" id="contract" class="form-control col-sm-5">
                <option value="1" {{ $user->contract == 1 ? 'selected' : '' }}>
                    {{ trans('staff.contract.fresher') }}
                </option>
                <option value="2" {{ $user->contract == 2 ? 'selected' : '' }}>
                    {{ trans('staff.contract.staff') }}
                </option>
                <option value="3" {{ $user->contract == 3 ? 'selected' : '' }}>
                    {{ trans('staff.contract.intern') }}
                </option>
            </select>
        </div>

        <!-- Birthday Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="birthday">{{ trans('staff.birthday') }}</label>
            <input type="date" name="birthday" id="birthday" class="form-control col-sm-5"
                value="{{ $user->birthday }}" />
        </div>

        <!-- Phone Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="phone">{{ trans('staff.phone') }}</label>
            <input type="number" name="phone" id="phone" class="form-control col-sm-5"
                value="{{ $user->phone }}" />
        </div>

        <!-- Status Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="status">{{ trans('staff.status.name') }}</label>
            <select name="status" id="status" class="form-control col-sm-5">
                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>{{ trans('staff.status.active') }}
                </option>
                <option value="2" {{ $user->status == 2 ? 'selected' : '' }}>
                    {{ trans('staff.status.inactive') }}
                </option>
            </select>
        </div>

        <!-- Position Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="position">{{ trans('staff.position.name') }}</label>
            <select name="position" id="position" class="form-control col-sm-5">
                <option value="1" {{ $user->position == 1 ? 'selected' : '' }}>{{ trans('staff.position.1') }}
                </option>
                <option value="2" {{ $user->position == 2 ? 'selected' : '' }}>{{ trans('staff.position.2') }}
                </option>
                <option value="3" {{ $user->position == 3 ? 'selected' : '' }}>{{ trans('staff.position.3') }}
                </option>
                <option value="4" {{ $user->position == 4 ? 'selected' : '' }}>{{ trans('staff.position.4') }}
                </option>
            </select>
        </div>

        <!-- Avatar Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="avatar">{{ trans('staff.avatar') }}</label>
            <input type="text" name="avatar" id="avatar" class="form-control col-sm-5"
                value="{{ $user->avatar }}" />
        </div>

        <!-- Role Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="role">{{ trans('staff.role.name') }}</label>
            <select name="role" id="role" class="form-control col-sm-5">
                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>{{ trans('staff.role.1') }}</option>
                <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>{{ trans('staff.role.2') }}</option>
                <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>{{ trans('staff.role.3') }}</option>
                <option value="4" {{ $user->role == 4 ? 'selected' : '' }}>{{ trans('staff.role.4') }}</option>
            </select>
        </div>
    </div>


</div>
