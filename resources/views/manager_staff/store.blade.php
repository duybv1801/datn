<div class="row">
    <div class="col-md-5 mx-auto">
        <!-- Username Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="name">{{ trans('staff.name.name') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="name" name="name" id="name" class="form-control" value="{{ old('name') }}" />
            </div>
        </div>

        <!-- Email Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="email">{{ trans('staff.email') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" />
            </div>
        </div>

        <!-- Code Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="code">{{ trans('staff.code') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" />
            </div>
        </div>

        <!-- Role Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="role">{{ trans('staff.role.name') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <select name="role" id="role" class="form-control">
                    <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>{{ trans('staff.role.1') }}</option>
                    <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>{{ trans('staff.role.2') }}
                    </option>
                    <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>{{ trans('staff.role.3') }}
                    </option>
                    <option value="4" {{ old('role') == 4 ? 'selected' : '' }}>{{ trans('staff.role.4') }}
                    </option>
                </select>
            </div>
        </div>
        <!-- Password Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="password">{{ trans('passwords.password_input') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="password" name="password" id="password" class="form-control"
                    value="{{ old('password') }}" />
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label"
                for="password_confirmation">{{ trans('passwords.password_confirm') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    value="{{ old('password_confirmation') }}" />
            </div>
        </div>

        <!-- Submit Field -->
        <div class="form-group row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                <a href="{!! route('manager_staff.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
            </div>
        </div>
    </div>
</div>
