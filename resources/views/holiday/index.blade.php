@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('holiday.list_holiday') }}</h1>
            </div>
            <div class="col-md-6">
                <div class="dropdown">
                    <button class="btn btn-primary float-right dropdown-toggle" type="button" id="addNewHolidayButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ trans('Options') }}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="addNewHolidayButton">
                        <a class="dropdown-item" href="#" id="formOption">
                            {{ trans('holiday.date_range') }}
                        </a>
                        <a class="dropdown-item" href="#" id="dateOption">
                            {{ trans('holiday.file') }}
                        </a>
                        {{-- <a class="dropdown-item" href="#">{{ trans('Export') }}</a> --}}
                    </div>
                    <div>
                        <div>
                            {!! Form::open(['route' => ['holidays.multi_delete'], 'method' => 'post', 'id' => 'multiDeleteForm']) !!}
                            {!! Form::close() !!}
                        </div>
                        <button class="btn btn-danger float-right mr-1" id="deleteSelectedButton">
                            {{ trans('Delete Selected') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <!-- Modal nhập form -->
        <div class="modal" id="formModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('holiday.add_holiday') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('adminlte-templates::common.errors')
                        <div class="box box-primary">
                            <div class="box-body">
                                <form action="{!! route('holidays.store') !!}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title">{{ trans('holiday.title') }}</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="form-group" id="dateRangeField">
                                        <label for="reservation">{{ trans('holiday.date_range') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control float-right" id="reservation"
                                                name="daterange" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-8">
                                            <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                            <a href="{!! route('holidays.index') !!}"
                                                class="btn btn-default">{{ trans('Cancel') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal import -->
        <div class="modal" id="dateModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('holiday.file') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('adminlte-templates::common.errors')
                        <div class="box box-primary">
                            <div class="box-body">
                                <form action="{!! route('holidays.import') !!}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="csv_file">{{ trans('holiday.file') }}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="form-control" id="csv_file" name="csv_file"
                                                    required="required">
                                                <label class="custom-file-label"
                                                    for="csv_file">{{ trans('holiday.choose') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-8">
                                            <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                            <a href="{!! route('holidays.index') !!}"
                                                class="btn btn-default">{{ trans('Cancel') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                @include('holiday.table')
            </div>
        </div>
    </div>
@endsection
