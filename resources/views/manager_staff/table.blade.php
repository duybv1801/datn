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
                                <th>{{ Form::label('name', trans('staff.name.name')) }}</th>
                                <th>{{ Form::label('email', trans('staff.email')) }}</th>
                                <th>{{ Form::label('code', trans('staff.code')) }}</th>
                                <th>{{ Form::label('gender', trans('staff.genders.name')) }} </th>
                                <th>{{ Form::label('birthday', trans('staff.birthday')) }}</th>
                                <th>{{ Form::label('phone', trans('staff.phone')) }} </th>
                                <th>{{ Form::label('funtions', trans('Funtions')) }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $i = $users->firstItem(); ?>
                            @foreach ($users as $user)
                                <tr>
                                    <td> {{ $i++ }}</td>
                                    <td>
                                        <p>{!! $user->name !!}</p>
                                    </td>
                                    <td>
                                        <p>{!! $user->email !!}</p>
                                    </td>
                                    <td>
                                        <p>{!! $user->code !!}</p>
                                    </td>
                                    <td>
                                        <p>
                                            {{ $user->gender == 1 ? trans('staff.genders.male') : trans('staff.genders.female') }}
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            {!! $user->birthday !!}
                                        </p>
                                    </td>
                                    <td>
                                        <p>{!! $user->phone !!}</p>
                                    </td>
                                    <td>
                                        {!! Form::open(['route' => ['manager_staff.destroy', $user->id], 'method' => 'delete']) !!}
                                        <div class="btn-group">
                                            @can('update', App\Models\User::class)
                                                <a href="{!! route('manager_staff.edit', [$user->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>{{ trans('Edit') }}
                                                </a>
                                            @endcan
                                            @can('delete', App\Models\User::class)
                                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>' . trans('Delete'), [
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'onclick' => 'confirmDelete(event)',
                                                ]) !!}
                                            @endcan
                                        </div>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
