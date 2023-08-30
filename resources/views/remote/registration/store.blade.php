<div class="row">
    <div class="col-md-5 mx-auto">

        <!-- user_id Field -->
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <!-- from_datetime Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="from_datetime">{{ trans('remote.from') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <div class="input-group date datetime_24h" id="from_datetime" data-target-input="nearest">
                    <input type="text" name="from_datetime" class="form-control datetimepicker-input"
                        data-target="#from_datetime" value="{{ old('from_datetime') }}" required="required" />
                    <div class="input-group-append" data-target="#from_datetime" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- to_datetime Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="to_datetime">{{ trans('remote.to') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <div class="input-group date datetime_24h" id="to_datetime" data-target-input="nearest">
                    <input type="text" name="to_datetime" class="form-control datetimepicker-input"
                        data-target="#to_datetime" value="{{ old('to_datetime') }}" required="required" />
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
                <input type="text" name="reason" id="reason" class="form-control" value="{{ old('reason') }}" />
            </div>
        </div>

        <!-- total hour Field -->
        <input type="hidden" name="total_hours" value="{{ old('total_hours') }}" />


        <!-- approver_id Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="approver_id">{{ trans('remote.approver') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <select name="approver_id" id="approver_id" class="form-control">
                    @foreach (\App\Models\User::whereIn('role_id', [1, 4])->get() as $user)
                        <option value="{{ $user->id }}" {{ old('approver_id') == $user->id ? 'selected' : '' }}>
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
                    @foreach (\App\Models\Team::distinct('manager')->get(['manager']) as $team)
                        <optgroup label="{{ $team->manager }}">
                            @foreach (\App\Models\Team::where('manager', $team->manager)->get() as $subTeam)
                                <option value="{{ $subTeam->id }}"
                                    {{ in_array($subTeam->id, old('cc', [])) ? 'selected' : '' }}>
                                    {{ $subTeam->name }}
                                </option>
                            @endforeach
                        </optgroup>
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
                <img id="avatar-preview" src="#" alt="Preview"
                    style="max-width: 200px; margin-top: 10px; display: none;">
            </div>
        </div>

        <!-- Submit Field -->
        <div class="form-group row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                <a href="{!! route('remote.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
            </div>
        </div>
    </div>
</div>
