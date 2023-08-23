<div class="row">
    <div class="col-md-5 mx-auto">

        <!-- user_id Field -->
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <!-- from_datetime Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="from_datetime">{{ trans('remote.from') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="date" name="from_datetime" id="from_datetime" class="form-control"
                    value="{{ old('from_datetime') }}" />
            </div>
        </div>

        <!-- to Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="remote.to">{{ trans('remote.to') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="date" name="to_datetime" id="to_datetime" class="form-control"
                    value="{{ old('to_datetime') }}" />
            </div>
        </div>

        <!-- resason Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="reason">{{ trans('remote.reason') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="text" name="reason" id="reason" class="form-control" value="{{ old('reason') }}" />
            </div>
        </div>

        <!-- total hour Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="total_hours">{{ trans('remote.total_hours') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="number" name="total_hours" id="total_hours" class="form-control"
                    value="{{ old('total_hours') }}" />
            </div>
        </div>

        <!-- cc Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="cc">{{ trans('remote.cc') }}
            </label>
            <div class="col-sm-8">
                <input type="email" name="" id="cc" class="form-control" value="{{ old('cc') }}" />
            </div>
        </div>


        <!-- approver_id Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="approver_id">{{ trans('remote.approver') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <select name="approver_id" id="approver_id" class="form-control">
                    <option value="1" {{ old('approver_id') == 1 ? 'selected' : '' }}>
                        {{ auth()->user()->find(1)->code }}
                    </option>
                    <option value="69" {{ old('approver_id') == 69 ? 'selected' : '' }}>
                        {{ auth()->user()->find(69)->code }}
                    </option>
                    <option value="70" {{ old('approver_id') == 70 ? 'selected' : '' }}>
                        {{ auth()->user()->find(70)->code }}
                    </option>
                    <option value="71" {{ old('approver_id') == 71 ? 'selected' : '' }}>
                        {{ auth()->user()->find(71)->code }}
                    </option>
                </select>
            </div>
        </div>

        <!-- evident Field -->
        <div class="form-group row">
            <div class="form-group col-sm-4">
                {!! Form::label('evident', trans('remote.envident')) !!}
                {!! Form::file('evident', ['class' => 'form-control', 'onchange' => 'previewAvatar(event)']) !!}
                <img id="avatar-preview" src="#" alt="Preview"
                    style="max-width: 200px; margin-top: 10px; display: none;">
            </div>
        </div>

        <!-- Submit Field -->
        <div class="form-group row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                <a href="{!! route('registration.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
            </div>
        </div>
    </div>
</div>
