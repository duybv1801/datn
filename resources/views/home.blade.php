@extends('layouts.app')

@section('content')

        <div class="content">
            <h1>Trang chủ</h1>
        <div class="clearfix"></div>
            <div class="card card-warning">
                <div class="card-header" data-card-widget="collapse" title="Collapse">
                    <h1 class="card-title">{{ trans('search') }}</h1>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            @include('flash::message')
                            <h5 class="text-danger" style="margin-bottom: 20px">
                                <span style="margin-right: 30px">Số phép dư: {{Auth::user()->leave_hours_left}}h</span>Phép theo tháng còn:  {{Auth::user()->leave_hours_left_in_month}}h
                            </h5>
                            {!! Form::open(['route' => ['home'], 'method' => 'get']) !!}
                            <div class="form-group row">
                                {!! Form::label('start_date', 'Ngày bắt đầu', ['class' => 'col-lg-2']) !!}
                                {!! Form::text('start_date', $start_date, ['class' => 'form-control col-lg-10']) !!}
                            </div>
                            <div class="form-group row">
                                {!! Form::label('end_date', 'Ngày kết thúc', ['class' => 'col-lg-2']) !!}
                                {!! Form::text('end_date', $end_date, ['class' => 'form-control col-lg-10']) !!}
                            </div>
                            <div class="form-group row">
                                <div class="offset-2">
                                    {!! Form::submit('Tìm kiếm') !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="col-lg-6">
                            <!-- check in out time -->
                        </div>
                    </div>

                </div>
            </div>
            <div class="card card-warning">
                <div class="card-header"  data-card-widget="collapse" title="Collapse">
                    <h1 class="card-title">Timesheets</h1>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="text-danger" style="margin-bottom: 20px">
                        Số giờ tính lương: {{$workingHours}}/{{$totalHours}}
                    </h5>
                        <table class="table table-striped table-bordered table-sm table-data">
                            <tr>
                                <th>#</th>
                                <th>Mã nhân viên</th>
                                <th>Họ tên</th>
                                <th>Ngày</th>
                                <th>Check in</th>
                                <th>Check out</th>
                                <th>Trạng thái</th>
                                <th>Giờ công</th>
                                <th>Giờ OT</th>
                                <th>Giờ phép</th>
                                <th>Action</th>
                            </tr>
                            @php($index = 1)
                            @forelse($timesheetData as $timesheet)
                                <tr>
                                    <td>{{ $index }}</td>
                                    <td>{!! $timesheet->user->code !!}</td>
                                    <td>{!! $timesheet->user->name !!}</td>
                                    <td>{!! $timesheet->record_date !!}</td>
                                    <td>{!! $timesheet->in_time !!}</td>
                                    <td>{!! $timesheet->out_time !!}</td>
                                    <td><span class="<?= $timesheet->status==1 ? 'badge badge-success': 'badge badge-danger' ?> ">{!! __('define.timesheet.status.'.$timesheet->status) !!}</span></td>
                                    <td>{!! $timesheet->working_hours !!}</td>
                                    <td>{!! $timesheet->overtime_hours !!}</td>
                                    <td>{!! $timesheet->leave_hours !!}</td>
                                    <td>
                                        @if($timesheet->status == 2)
                                            <a href="{{route('in_out_forgets.create',['date' => $timesheet->record_date])}}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-bell"></i> In out
                                            </a>
                                            <a href="{{route('leaves.create', ['date' => $timesheet->record_date])}}" class="btn btn-danger btn-sm">
                                                <i class="fas fa-sharp fa-solid fa-house-user"></i> Leave
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @php($index++)
                            @empty
                                <tr>
                                    <td colspan="10">No data</td>
                                </tr>
                            @endforelse
                        </table>

                        {{ $timesheetData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
