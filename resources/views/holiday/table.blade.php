@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card card-primary">
    <div class="card-body p-0">
        <div class="my-4">
            <form action="{!! route('holidays.index') !!}" method="GET" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="row">
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

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="sort_by">{{ trans('Sort by') }}</label>
                                    <select class="select2 form-control" style="width: 100%;" name="sort_by"
                                        id="sort_by">
                                        <option value="asc" {{ request('sort_by') === 'asc' ? 'selected' : '' }}>
                                            {{ trans('ASC') }}</option>
                                        <option value="desc" {{ request('sort_by') === 'desc' ? 'selected' : '' }}>
                                            {{ trans('DESC') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="order_by">{{ trans('Order by') }}</label>
                                    <select class="select2 form-control" style="width: 100%;" name="order_by"
                                        id="order_by">
                                        <option value="date" {{ request('order_by') === 'date' ? 'selected' : '' }}>
                                            {{ trans('holiday.date') }}</option>
                                        <option value="updated_at"
                                            {{ request('order_by') === 'updated_at' ? 'selected' : '' }}>
                                            {{ trans('Updated at') }}</option>
                                        <option value="created_at"
                                            {{ request('order_by') === 'created_at' ? 'selected' : '' }}>
                                            {{ trans('Created at') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="keywords">{{ trans('Keywords') }}</label>
                                    <div class="input-group">
                                        <input type="search" class="form-control"
                                            placeholder="{{ trans('Keywords') }}" name="query" id="keywords"
                                            value="{{ request('query') ? request('query') : '' }}">
                                    </div>
                                </div>
                            </div>
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
        </div>
        <div class="table-responsive">
            <table class="table holiday-table" id="holidayTable">
                <thead>
                    <tr>
                        <th class="col-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkAllFunctions">
                                <label class="custom-control-label" for="checkAllFunctions"></label>
                            </div>
                        </th>
                        <th class="col-1">{{ trans('No.') }}</th>
                        <th class="col-3">{{ trans('holiday.title') }}</th>
                        <th class="col-3">{{ trans('holiday.date') }}</th>
                        <th class="col-2">{{ trans('Funtions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $i = $holidays->firstItem();
                    @endphp
                    @foreach ($holidays as $holiday)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input custom-control-input-danger" type="checkbox"
                                        id="customCheckbox{{ $holiday->id }}" unchecked>
                                    <label for="customCheckbox{{ $holiday->id }}"
                                        class="custom-control-label"></label>
                                </div>
                            </td>
                            <td>
                                {{ $i++ }}
                            </td>
                            <td>
                                {!! $holiday->title !!}
                            </td>
                            <td>
                                {!! $holiday->date->format(config('define.date_show')) !!}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm" data-id="{{ $holiday->id }}"
                                        id="edit_holiday">{{ trans('Edit') }}</button>
                                    <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST"
                                        class="btn btn-danger btn-sm">
                                        @csrf
                                        @method('DELETE')
                                        <a type="submit" onclick="return confirmDelete(event)" class="text-white"
                                            href="">{{ trans('Delete') }}</a>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ trans('holiday.edit_holiday') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @include('adminlte-templates::common.errors')
                                        <div class="box box-primary">
                                            <div class="box-body">
                                                {!! Form::model($holiday, [
                                                    'route' => ['holidays.update', '__id__'],
                                                    'method' => 'put',
                                                    'enctype' => 'multipart/form-data',
                                                ]) !!}
                                                <div class="form-group">
                                                    {!! Form::label('title', trans('holiday.title')) !!}
                                                    {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'titleHoliday', 'required']) !!}
                                                </div>
                                                <div class="form-group" id="dateRangeField">
                                                    {!! Form::label('date', trans('holiday.date_range')) !!}
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="far fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                        {!! Form::text('daterange', null, [
                                                            'class' => 'form-control float-right reservation',
                                                            'id' => 'dateHoliday',
                                                            'required',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-4"></div>
                                                    <div class="col-sm-8">
                                                        {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
                                                        <a href="{!! route('holidays.index') !!}"
                                                            class="btn btn-default">{{ trans('Cancel') }}</a>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination justify-content-center">
                {{ $holidays->appends([
                        'start_date' => request()->input('start_date'),
                        'end_date' => request()->input('end_date'),
                        'sort_by' => request()->input('sort_by'),
                        'order_by' => request()->input('order_by'),
                    ])->links() }}
            </div>


        </div>
    </div>
    <!-- /.card-body -->
</div>
