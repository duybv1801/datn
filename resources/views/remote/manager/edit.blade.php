@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <section class="content-header">
                    <h1>
                        {{ trans('Approve') }}
                    </h1>
                </section>
                <div class="content">
                    @include('adminlte-templates::common.errors')
                    <div class="box box-primary">
                        <div class="box-body">
                            {!! Form::model($manager_remotes, [
                                'route' => ['manager_remote.approve', $manager_remotes->id],
                                'method' => 'put',
                                'enctype' => 'multipart/form-data',
                            ]) !!}

                            @include('remote.manager.approve')

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
