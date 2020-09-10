@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <div id="chart" style="height: 300px"></div>
                </div>
            </div>
        </div>
    </div>
</div>

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
