<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                {{-- search --}}
                <form action="{!! route('overtimes.index') !!}" method="GET">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="row">
                                {{-- from date --}}
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="search_from">{{ trans('From Date') }}</label>
                                        <div class="input-group date reservationdate" id="reservationdate_from"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate_from" data-toggle="datetimepicker"
                                                name="start_date" id="search_from"
                                                value="{{ request('start_date',now()->startOfMonth()->format(config('define.date_show'))) }}" />
                                            <div class="input-group-append" data-target="#reservationdate_from"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- todate --}}
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="search_to">{{ trans('To Date') }}</label>
                                        <div class="input-group date reservationdate" id="reservationdate_to"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate_to" data-toggle="datetimepicker"
                                                name="end_date" id="search_to"
                                                value="{{ request('end_date',now()->endOfMonth()->format(config('define.date_show'))) }}" />
                                            <div class="input-group-append" data-target="#reservationdate_to"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
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
                                <th>{{ Form::label('from', trans('No.')) }}</th>
                                <th>{{ Form::label('approver', trans('overtime.approver')) }}</th>
                                <th>{{ Form::label('from', trans('overtime.from')) }}</th>
                                <th>{{ Form::label('to', trans('overtime.to')) }}</th>
                                <th>{{ Form::label('total_hours', trans('overtime.total_hours')) }}</th>
                                <th>{{ Form::label('total_hours', trans('overtime.salary_hours')) }}</th>
                                <th>{{ Form::label('reason', trans('overtime.reason')) }}</th>
                                <th>{{ Form::label('status', trans('overtime.status')) }}</th>
                                <th>{{ Form::label('functions', trans('Funtions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = $overtimes->firstItem(); ?>
                            @foreach ($overtimes as $overtime)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        {!! $overtime->approver_id !!}
                                    </td>
                                    <td>
                                        {!! $overtime->from_datetime->format(config('define.datetime')) !!}
                                    </td>
                                    <td>
                                        {!! $overtime->to_datetime->format(config('define.datetime')) !!}
                                    </td>
                                    <td>
                                        {!! $overtime->total_hours !!}
                                    </td>
                                    <td>
                                        {!! $overtime->salary_hours !!}
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;">
                                            {!! $overtime->reason !!}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="{{ $statusData[$overtime->status]['class'] }}">
                                            {{ $statusData[$overtime->status]['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        {!! Form::open(['route' => ['overtimes.cancel', $overtime->id], 'method' => 'put']) !!}
                                        <div class="btn-group">
                                            @if ($overtime->from_datetime < \Carbon\Carbon::now() || $overtime->status != 1)
                                            @else
                                                <a href="{!! route('overtimes.edit', [$overtime->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>{{ trans('Edit') }}
                                                </a>
                                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>' . trans('Cancel'), [
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'onclick' => 'confirmCancel(event)',
                                                ]) !!}
                                            @endif
                                        </div>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination justify-content-center">
                        {{ $overtimes->appends([
                                'start_date' => request()->input('start_date'),
                                'end_date' => request()->input('end_date'),
                            ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
