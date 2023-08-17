@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans('staff.staff_edit') }}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                {!! Form::model($user, ['route' => ['manager_staff.update', $user->id], 'method' => 'patch']) !!}
                @include('manager_staff.fields')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
