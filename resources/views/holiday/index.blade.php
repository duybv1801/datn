@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans('Manage Holidays') }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="sticky-top mb-3">
                        <div class="card card-primary">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @elseif (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="card-header">
                                <h3 class="card-title">{{ trans('holiday.file') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{!! route('holidays.import') !!}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputYear">{{ trans('holiday.year') }}</label>
                                        <select class="form-control" id="inputYear" name="year">
                                            <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                                            <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">{{ trans('holiday.file') }}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="csv_file"
                                                    name="csv_file" required="required">
                                                <label class="custom-file-label"
                                                    for="csv_file">{{ trans('holiday.choose') }}</label>
                                            </div>

                                            <div class="input-group-append">
                                                <a href="{{ asset('sample_csv.csv') }}"
                                                    class="btn btn-outline-secondary">{{ trans('holiday.sample_csv') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" class="btn btn-primary">
                                </form>
                            </div>
                        </div>
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ trans('holiday.add_holiday') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{!! route('holidays.store') !!}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title">{{ trans('holiday.title') }}</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="selectOption">{{ trans('holiday.select_option') }}</label>
                                        <select class="form-control" id="selectOption" name="select_option">
                                            <option value="1">{{ trans('holiday.option_single_day') }}</option>
                                            <option value="2">{{ trans('holiday.option_multiple_days') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="dateField">
                                        <label for="datepick">{{ trans('holiday.date') }}</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate" data-toggle="datetimepicker" id="datepick"
                                                name="date" />
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
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
                                                name="daterange" required disabled>
                                        </div>
                                    </div>
                                    <input type="submit" class="btn btn-primary">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.col -->
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ trans('holiday.list_holiday') }}</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table holiday-table">
                                    <thead>
                                        <tr>
                                            <th class="col-1">#</th>
                                            <th class="col-4">{{ Form::label('title', trans('holiday.title')) }}</th>
                                            <th class="col-3">{{ Form::label('date', trans('holiday.date')) }}</th>
                                            <th class="col-2">
                                                {{ trans('Funtions') }}
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="checkAllFunctions">
                                                    <label class="custom-control-label" for="checkAllFunctions"></label>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $i = $holidays->firstItem(); ?>
                                        @foreach ($holidays as $holiday)
                                            <tr>
                                                <td> {{ $i++ }}</td>
                                                <td>
                                                    <p>{!! $holiday->title !!}</p>
                                                </td>
                                                <td>
                                                    <p>{!! $holiday->date->format('d-m-Y') !!}</p>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        {!! Form::open(['route' => ['holidays.destroy', $holiday->id], 'method' => 'delete']) !!}
                                                        {!! Form::button(trans('Delete'), [
                                                            'type' => 'submit',
                                                            'class' => 'btn btn-danger btn-sm mr-2',
                                                            'onclick' => "return confirm('" . trans('Are you sure you want to delete?') . "')",
                                                        ]) !!}
                                                        {!! Form::close() !!}

                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input custom-control-input-danger"
                                                                type="checkbox" id="customCheckbox{{ $holiday->id }}"
                                                                unchecked>
                                                            <label for="customCheckbox{{ $holiday->id }}"
                                                                class="custom-control-label"></label>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination justify-content-center">
                                    {{ $holidays->links() }}
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
