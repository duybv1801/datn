<div class="row">
    <div class="col-md-5 mx-auto">
        <!-- user_id Field -->
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <!-- from_datetime Field -->
        <div class="form-group row">
            <label class="col-sm-5 col-form-label" for="from_datetime">{{ trans('remote.from') }}</label>
            <div class="col-sm-5">
                <div class="input-group date datetime_24h" id="from_datetime" data-target-input="nearest">
                    <input type="text" name="from_datetime" class="form-control datetimepicker-input"
                        data-target="#from_datetime"
                        value="{{ (new \DateTime($manager_remotes->from_datetime))->format('d/m/Y H:i') }}"
                        required="required" readonly />
                    <div class="input-group-append" data-target="#from_datetime" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- to_datetime Field -->
        <div class="form-group row">
            <label class="col-sm-5 col-form-label" for="to_datetime">{{ trans('remote.to') }}</label>
            <div class="col-sm-5">
                <div class="input-group date datetime_24h" id="to_datetime" data-target-input="nearest">
                    <input type="text" name="to_datetime" class="form-control datetimepicker-input"
                        data-target="#from_datetime"
                        value="{{ (new \DateTime($manager_remotes->to_datetime))->format('d/m/Y H:i') }}"
                        required="required" readonly />
                    <div class="input-group-append" data-target="#to_datetime" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>


        <!-- evident Field -->
        <div class="form-group row">
            <label class="col-sm-5 col-form-label" for="evident">{{ trans('remote.evident') }}</label>
            <div class="col-sm-5">
                <img id="avatar-preview" src="{{ $manager_remotes->evident }}" alt="Preview" style="max-width: 100px">
            </div>
        </div>

        <!-- resason Field -->
        <div class="form-group row">
            <label class="col-sm-5 col-form-label" for="reason">{{ trans('remote.reason') }}
            </label>
            <div class="col-sm-5">
                <input type="text" name="reason" id="reason" class="form-control"
                    value="{{ $manager_remotes->reason }}" required="required" readonly />
            </div>
        </div>


        <!-- Submit Field -->
        <div class="form-group col-sm-5 ">
            {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('manager_remote.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
        </div>

    </div>

    <div class="col-md-5 mx-auto">
        <!-- Dependent Approve Field -->
        <div class="form-group row">
            <label class="col-sm-5 col-form-label" for="status">{{ trans('remote.options') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-5">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="approveRadio" value="1"
                        {{ $manager_remotes->status == 1 ? 'checked' : '' }}>
                    <label class="form-check-label rounded-circle" for="approveRadio">
                        {{ trans('Approve') }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="rejectRadio" value="3"
                        {{ $manager_remotes->status == 3 ? 'checked' : '' }}>
                    <label class="form-check-label rounded-circle" for="rejectRadio">
                        {{ trans('Reject') }}
                    </label>
                </div>
            </div>
        </div>


        <!-- comment Field -->
        <div class="form-group row">
            <label class="col-sm-5 col-form-label" for="comment">{{ trans('remote.comment') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-5">
                <input type="text" name="comment" id="comment" class="form-control"
                    value="{{ $manager_remotes->comment }}" />
            </div>
        </div>


    </div>


</div>
