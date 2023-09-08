@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <section class="content-header">
                    <h1>
                        {{ trans('Approve') }}
                    </h1>
                </section>
                <div class="content">
                    @include('adminlte-templates::common.errors')
                    <div class="box box-primary">
                        <div class="box-body">
                            {!! Form::model($overtime, [
                                'route' => ['overtimes.approveAction', $overtime->id],
                                'method' => 'put',
                            ]) !!}

                            <div class="row">
                                <div class="col-md-5 mx-auto">

                                    <!-- user_id Field -->
                                    <!-- from_datetime Field -->

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="from_datetime">
                                            {{ trans('overtime.from') }}
                                        </label>
                                        <div class="input-group date reservationdatetime col-sm-8" id="from_date"
                                            data-target-input="nearest">
                                            <input type="text" id="from_datetime"
                                                class="form-control datetimepicker-input" data-target="#from_date"
                                                data-toggle="datetimepicker" name="from_datetime" readonly
                                                value="{{ $overtime->from_datetime->format(config('define.datetime')) }}" />
                                            <div class="input-group-append" data-target="#from_date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- to_datetime Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="to_datetime">{{ trans('overtime.to') }}
                                        </label>
                                        <div class="input-group date reservationdatetime col-8" id="to_date"
                                            data-target-input="nearest">
                                            <input type="text" id="to_datetime" class="form-control datetimepicker-input"
                                                data-target="#to_date" data-toggle="datetimepicker" name="to_datetime"
                                                readonly
                                                value="{{ $overtime->to_datetime->format(config('define.datetime')) }}" />
                                            <div class="input-group-append" data-target="#to_date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- resason Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="reason">{{ trans('overtime.reason') }}

                                        </label>
                                        <div class="col-sm-8">
                                            <textarea name="reason" id="reason" class="form-control" readonly>{{ $overtime->reason }}</textarea>
                                        </div>
                                    </div>

                                    <!-- total hour Field -->

                                    <!-- cc Field -->
                                    {{-- <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"
                                            for="cc">{{ trans('overtime.cc') }}</label>
                                        <div class="col-sm-8">
                                            <select id="cc" class="form-control" name="cc[]" multiple>

                                            </select>
                                        </div>
                                    </div> --}}

                                    <!-- evident Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"
                                            for="evident">{{ trans('overtime.evident') }}</label>
                                        <div class="col-sm-8">
                                            <a data-fancybox="gallery" href="{{ $overtime->evident }}">
                                                <img class="img-thumbnail" src="{{ $overtime->evident }}" alt="Preview">
                                            </a>
                                        </div>
                                    </div>
                                    {{-- approve --}}
                                    <div class="form-group row">
                                        <label class="col-5 col-form-label" for="status">{{ trans('overtime.options') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-3 mt-2">
                                            <input required="required" class="form-check-input" type="radio"
                                                name="status" id="approveRadio"
                                                value="{{ config('define.overtime.approved') }}" />
                                            <label class="form-check-label rounded-circle"
                                                for="approveRadio">{{ trans('overtime.approve') }}</label>
                                        </div>
                                        <div class="col-3 mt-2">
                                            <input required="required" class="form-check-input" type="radio"
                                                name="status" id="rejectRadio"
                                                value="{{ config('define.overtime.rejected') }}" />
                                            <label class="form-check-label rounded-circle"
                                                for="rejectRadio">{{ trans('overtime.rejected') }}</label>
                                        </div>
                                    </div>


                                    <!-- comment Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"
                                            for="comment">{{ trans('overtime.comment') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-8">
                                            <textarea name="comment" id="comment" required="required" class="form-control">{{ old('comment') }}</textarea>
                                        </div>
                                    </div>
                                    <!-- Submit Field -->
                                    <div class="form-group row">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-8">
                                            <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                            <a href="{!! route('overtimes.manage') !!}"
                                                class="btn btn-default">{{ trans('Cancel') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
