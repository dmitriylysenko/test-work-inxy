@extends('layout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Add provider
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        {{Form::open([
            'route' => 'providers.store',
            'files' => true
        ])}}
        <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    @include('errors')
                </div>
                <div class="box-body">
                    <div class="col-md-6">

                        @foreach(['provider', 'location', 'brand_label', 'cpu', 'drive_label', 'price'] as $attribute)
                            <div class="form-group">
                                {{ Form::label($attribute, null, ['class' => 'control-label']) }}
                                {{ Form::text($attribute, old($attribute), ['class' => 'form-control']) }}
                            </div>
                        @endforeach
                    <div class="box-footer">
                        <a href="{{route('providers.index')}}" class="btn btn-warning">Back</a>
                        <button class="btn btn-success pull-right">Add</button>
                    </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <!-- /.box-footer-->
            </div>
            <!-- /.box -->
            {{Form::close()}}
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
