<div class="row">
    <div class="col-md-5 mx-auto">

        <!-- user_id Field -->
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <!-- from_datetime Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="from_datetimenew">{{ trans('remote.from') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <div class="input-group date datetime_24h" id="from_datetime" data-target-input="nearest">
                    <input type="text" name="from_datetime"id="from_datetimenew"
                        class="form-control datetimepicker-input" data-target="#from_datetime"
                        value="{{ \Carbon\Carbon::parse($remote->from_datetime)->format(config('define.datetime')) }}"
                        required="required" />
                    <div class="input-group-append" data-target="#from_datetime" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- to_datetime Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="to_datetimenew">{{ trans('remote.to') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <div class="input-group date datetime_24h" id="to_datetime" data-target-input="nearest">
                    <input type="text" name="to_datetime" id="to_datetimenew"
                        class="form-control datetimepicker-input" data-target="#from_datetime"
                        value="{{ \Carbon\Carbon::parse($remote->to_datetime)->format(config('define.datetime')) }}"
                        required="required" />
                    <div class="input-group-append" data-target="#to_datetime" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- resason Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="reason">{{ trans('remote.reason') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <textarea name="reason" id="reason" class="form-control" required="required">{{ $remote->reason }}</textarea>
            </div>
        </div>

        <!-- total hour Field -->
        <input type="hidden" name="total_hours" value="{{ $remote->total_hours }}" />


        <!-- approver_id Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="approver_id">{{ trans('remote.approver') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <select name="approver_id" id="approver_id" class="form-control">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $remote->approver_id == $user->id ? 'selected' : '' }}>
                            {{ $user->code }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- cc Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="cc">{{ trans('remote.cc') }}</label>
            <div class="col-sm-8">
                <select id="cc" class="form-control" name="cc[]" multiple>
                    @foreach ($codes as $code)
                        <option value="{{ $code }}"
                            {{ in_array($code, (array) old('cc', [])) ? 'selected' : '' }}>
                            {{ $code }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- evident Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="evident">{{ trans('remote.evident') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <div class="custom-file">
                    <input type="file" class="form-control" id="evident" name="evident" required="required"
                        onchange="previewAvatar(event)">
                    <label class="custom-file-label" for="evident">{{ trans('remote.evident') }}</label>
                </div>
                <img id="avatar-preview" src="{{ $remote->evident }}" alt="Preview"
                    style="max-width: 200px; margin-top: 10px; ">
            </div>
        </div>

        <!-- Submit Field -->
        <div class="form-group col-sm-5 ">
            {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('remote.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
        </div>
    </div>
</div>
