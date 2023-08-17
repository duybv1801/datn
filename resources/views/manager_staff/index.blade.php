@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left"> {{ trans('Manager Staff') }}</h1>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                @include('manager_staff.table')
            </div>
        </div>
    </div>
@endsection
