@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('Home') }}</h1>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary float-right" type="button" data-toggle="modal" data-target="#importTimesheet">
                    {{ trans('holiday.file') }}
                </button>
                <div>
                    {!! Form::open(['route' => ['timesheet.export'], 'method' => 'post']) !!}
                    {!! Form::hidden('start_date', request()->input('start_date')) !!}
                    {!! Form::hidden('end_date', request()->input('end_date')) !!}
                    {!! Form::hidden('user_id', request()->input('user_id')) !!}

                    <button class="btn btn-success float-right mr-1" type="submit">
                        {{ trans('holiday.export') }}
                    </button>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <!-- column -->
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- search --}}
                                <form action="{!! route('timesheet.manage') !!}" method="GET" id="ot_search">
                                    <div class="row">
                                        <div class="col-md-10 offset-md-1">
                                            <div class="row">
                                                {{-- from date --}}
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <label for="search_from">{{ trans('From Date') }}</label>
                                                        <div class="input-group date reservationdate"
                                                            id="reservationdate_from" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input"
                                                                data-target="#reservationdate_from"
                                                                data-toggle="datetimepicker" name="start_date"
                                                                id="search_from"
                                                                value="{{ request('start_date',now()->subMonth()->startOfMonth()->format(config('define.date_show'))) }}" />
                                                            <div class="input-group-append"
                                                                data-target="#reservationdate_from"
                                                                data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- todate --}}
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <label for="search_to">{{ trans('To Date') }}</label>
                                                        <div class="input-group date reservationdate"
                                                            id="reservationdate_to" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input"
                                                                data-target="#reservationdate_to"
                                                                data-toggle="datetimepicker" name="end_date" id="search_to"
                                                                value="{{ request('end_date',now()->endOfMonth()->format(config('define.date_show'))) }}" />
                                                            <div class="input-group-append"
                                                                data-target="#reservationdate_to"
                                                                data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- key word --}}
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <label for="user">{{ trans('overtime.user') }}</label>
                                                        <div class="input-group">
                                                            <select name="user_id" id="user"
                                                                class="form-control select2">
                                                                <option hidden></option>
                                                                @foreach ($users as $user)
                                                                    @if (!empty($user))
                                                                        <option value="{{ $user['id'] }}"
                                                                            {{ $user['id'] == request('user_id') ? 'selected' : '' }}>
                                                                            {{ $user['name'] }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- search --}}
                                                <div class="col-1">
                                                    <div class="form-group">
                                                        <label for="filter">&nbsp;</label>
                                                        <div class="input-group">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fa fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table user-table">
                                        <thead>
                                            <tr>
                                                <th>{{ Form::label('#', trans('No.')) }}</th>
                                                <th> {{ Form::label('name', trans('timesheet.name')) }} </th>
                                                <th> {{ Form::label('date', trans('timesheet.date')) }} </th>
                                                <th>{{ Form::label('check_in', trans('timesheet.check_in')) }}</th>
                                                <th>{{ Form::label('check_out', trans('timesheet.check_out')) }}</th>
                                                <th>{{ Form::label('work_time', trans('timesheet.work_time')) }}</th>
                                                <th>{{ Form::label('remote_time', trans('timesheet.remote_time')) }}</th>
                                                <th>{{ Form::label('ot_time', trans('timesheet.ot_time')) }}</th>
                                                <th>{{ Form::label('leave_time', trans('timesheet.leave_time')) }}</th>
                                                <th>{{ Form::label('status', trans('timesheet.status')) }}</th>
                                                <th>{{ Form::label('functions', trans('Funtions')) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = $timesheetData->firstItem(); ?>
                                            @forelse ($timesheetData as $timesheet)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{!! $timesheet->user->name !!}</td>
                                                    <td>{!! $timesheet->record_date !!}</td>
                                                    <td>{!! $timesheet->in_time !!}</td>
                                                    <td>{!! $timesheet->out_time !!}</td>
                                                    <td>{!! $timesheet->working_hours !!}</td>
                                                    <td>{!! $timesheet->remote_hours !!}</td>
                                                    <td>{!! $timesheet->overtime_hours !!}</td>
                                                    <td>{!! $timesheet->leave_hours !!}</td>
                                                    <td><span
                                                            class="<?= $timesheet->status == config('define.timesheet.normal') ? 'badge badge-success' : 'badge badge-danger' ?> ">{!! __('define.timesheet.status.' . $timesheet->status) !!}</span>
                                                    </td>
                                                    <td>
                                                        @if ($timesheet->status == config('define.timesheet.reconfirm'))
                                                            <div class="btn-group">
                                                                <a href="{{ route('in_out_forgets.create', ['date' => $timesheet->record_date]) }}"
                                                                    class="btn btn-primary btn-sm">
                                                                    {{ trans('In out') }}
                                                                </a>
                                                                <a href="{{ route('leaves.create', ['date' => $timesheet->record_date]) }}"
                                                                    class="btn btn-danger btn-sm">
                                                                    {{ trans('Leaves') }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10">{{ trans('No data') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="pagination justify-content-center">
                                    {{ $timesheetData->appends([
                                            'start_date' => request()->input('start_date'),
                                            'end_date' => request()->input('end_date'),
                                        ])->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal import -->
    <div class="modal" id="importTimesheet" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('holiday.file') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box box-primary">
                        <div class="box-body">
                            <form action="{!! route('timesheet.import') !!}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="csv_file">{{ trans('holiday.file') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="csv_file" name="csv_file"
                                                required="required">
                                            <label class="custom-file-label"
                                                for="csv_file">{{ trans('holiday.choose') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row text-center">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                        <a href="{!! route('timesheet.manage') !!}"
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
@endsection
