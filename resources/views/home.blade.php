@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        @php
                        $user = \App\User::count();
                        @endphp
                        <h3>{{ $user }}</h3>

                        <p>Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        @php
                            $in = \App\Attendance::where('status', 'in')
                                    ->whereDate('created_at', \Carbon\Carbon::today())
                                    ->count();
                        @endphp

                        <h3>
                        @if ($user != 0 && $in != 0)
                            {{($in/$user)*100}}
                        @else
                            0
                        @endif
                        <sup style="font-size: 20px">%</sup></h3>

                        <p>Total IN: {{ $in }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        @php
                            $out = \App\Attendance::where('status', 'out')
                                ->whereDate('created_at', \Carbon\Carbon::today())
                                ->count();
                        @endphp
                        <h3>
                        @if ($user != 0 && $out != 0)
                            {{($out/$user)*100}}
                        @else
                            0
                        @endif
                        <sup style="font-size: 20px">%</sup></h3>

                        <p>Total IN: {{ $out }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-12">

                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <!-- Attendance Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ion ion-clipboard mr-1"></i>
                            Attendance
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">


                        <div id="chart" style="height: 300px"></div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="button" class="btn btn-info float-right"><i class="fas fa-plus"></i> Add
                            item</button>
                    </div>
                </div>
                <!-- /.card -->
            </section>
            <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script>
    const chart = new Chartisan({
        el: '#chart',
        url: "@chart('attendance_chart')",
        hooks: new ChartisanHooks()
            .colors(['#3490dc', '#e3342f', '#38c172'])
            .legend({ position: 'bottom' })
            .datasets(['bar', 'bar', { type: 'line', fill: false }])
            .tooltip()
    });
</script>
@endpush
