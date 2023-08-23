<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table user-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ Form::label('name', trans('remote.creator')) }}</th>
                                <th>{{ Form::label('from', trans('remote.from')) }}</th>
                                <th>{{ Form::label('to', trans('remote.to')) }}</th>
                                <th>{{ Form::label('total_hours', trans('remote.total_hours')) }} </th>
                                <th>{{ Form::label('approver', trans('remote.approver')) }}</th>
                                <th>{{ Form::label('status', trans('remote.status.name')) }}</th>
                                <th>{{ Form::label('funtions', trans('Funtions')) }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $i = $remotes->firstItem(); ?>
                            @foreach ($remotes as $remote)
                                <tr>
                                    <td> {{ $i++ }}</td>
                                    <td>{{ $remote->getName() }}</td>
                                    <td>{{ $remote->from_datetime }}</td>
                                    <td>{{ $remote->to_datetime }}</td>
                                    <td>{{ $remote->total_hours }}</td>
                                    <td>{{ $remote->getApprove() }}</td>
                                    <td>
                                        {{ trans('remote.status.' . ($remote->status >= 1 && $remote->status <= 4 ? $remote->status : '')) }}
                                    </td>
                                    <td>
                                        {!! Form::open(['route' => ['registration.destroy', $remote->id], 'method' => 'delete']) !!}
                                        <div class="btn-group">
                                            <a href="{!! route('registration.edit', [$remote->id]) !!}" class="btn btn-primary btn-sm">
                                                <i class="glyphicon glyphicon-edit"></i>{{ trans('Edit') }}
                                            </a>
                                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>' . trans('Delete'), [
                                                'type' => 'submit',
                                                'class' => 'btn btn-danger btn-sm',
                                                'onclick' => 'confirmDelete(event)',
                                            ]) !!}
                                        </div>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination justify-content-center">
                        {{ $remotes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
