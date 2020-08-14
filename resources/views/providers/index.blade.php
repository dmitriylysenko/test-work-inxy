@extends('layout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i>Home</a></li>
                /
                <li><a href="{{route('providers.index')}}">Providers</a></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        <!-- Default box -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Providers</h3>
                    @include('errors')
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group">
                        <a href="{{route('providers.create')}}" class="btn btn-success">Add</a>
                        <a href="{{route('providers.load')}}" class="btn btn-info">Load</a>
                    </div>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Provider</th>
                            <th>Brand</th>
                            <th>Location</th>
                            <th>CPU</th>
                            <th>Drive</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($providers as $provider)
                            <tr>
                                <td>{{$provider->provider}}</td>
                                <td>{{$provider->brand_label}}</td>
                                <td>{{$provider->location}}</td>
                                <td>{{$provider->cpu}}</td>
                                <td>{{$provider->drive_label}}</td>
                                <td>{{$provider->price}}</td>
                                <td>
                                    <a href="{{route('providers.edit', $provider->id)}}" class="fa fa-pencil"></a>
                                    {!! Form::open([
                                        'route' => ['providers.destroy', $provider->id],
                                        'method' => 'delete'
                                    ]) !!}
                                    <button onclick="return confirm('are you sure?')" type="submit" class="delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $providers->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
