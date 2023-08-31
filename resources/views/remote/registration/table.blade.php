<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                {{-- search --}}
                <form action="{!! route('remote.index') !!}" method="GET" enctype="multipart/form-data">
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
                                                value="{{ request('start_date',now()->startOfYear()->format(config('define.date_show'))) }}" />
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
                                                value="{{ request('end_date',now()->endOfYear()->format(config('define.date_show'))) }}" />
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
                                        <label for="filter">{{ trans('Filter') }}</label>
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
                                <th>#</th>
                                <th>{{ Form::label('from', trans('remote.from')) }}</th>
                                <th>{{ Form::label('to', trans('remote.to')) }}</th>
                                <th>{{ Form::label('total_hours', trans('remote.total_hours')) }}</th>
                                <th>{{ Form::label('approver', trans('remote.approver')) }}</th>
                                <th>{{ Form::label('status', trans('remote.status.name')) }}</th>
                                <th>{{ Form::label('functions', trans('Funtions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = $remotes->firstItem(); ?>
                            @foreach ($remotes as $remote)
                                @if ($remote->user_id == Auth::id())
                                    <tr>
                                        <td> {{ $i++ }}</td>
                                        <td>{{ $remote->from_datetime->format('d/m/Y H:i') }}</td>
                                        <td>{{ $remote->to_datetime->format('d/m/Y H:i') }}</td>
                                        <td>{{ $remote->total_hours }}</td>
                                        <td>{{ $remote->getApprove() }}</td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    1 => 'badge badge-primary',
                                                    2 => 'badge badge-success',
                                                    3 => 'badge badge-danger',
                                                    4 => 'badge badge-warning',
                                                ];
                                                $statusClass = $statusClasses[$remote->status] ?? '';
                                            @endphp
                                            <span class="{{ $statusClass }}">
                                                {{ $remote->status == 1
                                                    ? trans('remote.status.regist')
                                                    : ($remote->status == 2
                                                        ? trans('remote.status.approve')
                                                        : ($remote->status == 3
                                                            ? trans('remote.status.ban')
                                                            : ($remote->status == 4
                                                                ? trans('remote.status.cancel')
                                                                : ''))) }}
                                            </span>
                                        </td>
                                        <td>
                                            {!! Form::open(['route' => ['remote.cancel', $remote->id], 'method' => 'put']) !!}
                                            <div class="btn-group">
                                                <a href="{!! route('remote.edit', [$remote->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>{{ trans('Edit') }}
                                                </a>
                                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>' . trans('Cancel'), [
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'onclick' => 'confirmCancel(event)',
                                                ]) !!}
                                            </div>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination justify-content-center">
                        {{ $remotes->appends([
                                'start_date' => request()->input('start_date'),
                                'end_date' => request()->input('end_date'),
                                'sort_by' => request()->input('sort_by'),
                                'order_by' => request()->input('order_by'),
                            ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
