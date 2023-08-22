@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <section class="content-header">
                        <h1>
                            {{ trans('Account Edit') }}
                        </h1>
                    </section>
                    <div class="content">
                        @include('adminlte-templates::common.errors')
                        <div class="box box-primary">
                            <div class="box-body">
<<<<<<< HEAD
<<<<<<< HEAD
                                {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}

                                @include('users.fields')

=======
                                {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'post', 'files' => true]) !!}
                                @include('users.fields')
>>>>>>> 2f483590b841b591e0eb9ecc64e6d81d2bb1f1b9
=======
                                {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'post', 'files' => true]) !!}
                                @include('users.fields')
>>>>>>> 2f483590b841b591e0eb9ecc64e6d81d2bb1f1b9
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
