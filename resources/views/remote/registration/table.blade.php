<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                {{-- search --}}
                <form action="{!! route('remote.index') !!}" method="GET">
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
                                <th>{{ Form::label('name', '#') }}</th>
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
                                        <td>{{ $remote->from_datetime->format(config('define.datetime')) }}</td>
                                        <td>{{ $remote->to_datetime->format(config('define.datetime')) }}</td>
                                        <td>{{ $remote->total_hours }}</td>
                                        <td>{{ $remote->getApprove() }}</td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    config('define.remotes.pending') => 'badge badge-primary',
                                                    config('define.remotes.approved') => 'badge badge-success',
                                                    config('define.remotes.rejected') => 'badge badge-danger',
                                                    config('define.remotes.cancelled') => 'badge badge-warning',
                                                ];
                                                $statusClass = $statusClasses[$remote->status] ?? '';
                                            @endphp
                                            <span class="{{ $statusClass }}">
                                                {{ $remote->status == config('define.remotes.pending')
                                                    ? trans('remote.status.regist')
                                                    : ($remote->status == config('define.remotes.approved')
                                                        ? trans('remote.status.approve')
                                                        : ($remote->status == config('define.remotes.rejected')
                                                            ? trans('remote.status.ban')
                                                            : ($remote->status == config('define.remotes.cancelled')
                                                                ? trans('remote.status.cancel')
                                                                : ''))) }}
                                            </span>
                                        </td>
                                        <td>
                                            {!! Form::open(['route' => ['remote.cancel', $remote->id], 'method' => 'put']) !!}
                                            <div class="btn-group">
                                                @php
                                                    $currentTime = now();
                                                    $registrationTime = $remote->from_datetime;
                                                @endphp
                                                @if ($remote->status == config('define.remotes.pending') && !$currentTime->greaterThanOrEqualTo($registrationTime))
                                                    <a href="{!! route('remote.edit', [$remote->id]) !!}" class="btn btn-primary btn-sm">
                                                        <i class="glyphicon glyphicon-edit"></i>{{ trans('Edit') }}
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="modal" data-target="#cancelModal">
                                                        <i class="glyphicon glyphicon-trash"></i> {{ trans('Cancel') }}
                                                    </button>
                                                @endif
                                            </div>
                                            <div id="cancelModal" class="modal fade" tabindex="-1" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                {{ trans('Confirm cancellation!') }}
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label for="reservation_modal">{{ trans('remote.reason') }}
                                                                <span class="text-danger">*</span>
                                                            </label>

                                                            <textarea name="comment" id="comment" required="required" class="form-control"
                                                                placeholder="{{ trans('Enter your reason!') }}"></textarea>
                                                        </div>
                                                        <!-- Submit Field -->
                                                        <div class="form-group row text-center">
                                                            <div class="col-sm-12">
                                                                <button type="submit"
                                                                    class="btn btn-primary">{{ trans('Save') }}</button>
                                                                <a href="{!! route('remote.index') !!}"
                                                                    class="btn btn-default">{{ trans('Cancel') }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
