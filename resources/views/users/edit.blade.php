@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans('Account Edit') }}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}
           
                    @include('users.fields')
             
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
