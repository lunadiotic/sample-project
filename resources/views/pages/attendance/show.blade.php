@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary mb-2">Back</a>

            <div class="card">
                <div class="card-header">
                    Attendance Detail
                </div>

                <div class="card-body">
                    <table class="table" id="datatable">
                        <tbody>
                            <tr>
                                <th>Time</th>
                                <td>{{ $attendance->created_at }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $attendance->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Lat, Long</th>
                                <td>{{ $attendance->long }}, {{ $attendance->lat }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $attendance->address }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>
                                    <div style="width: 100%">
                                        <iframe
                                            width="100%"
                                            height="300"
                                            frameborder="0"
                                            scrolling="no"
                                            marginheight="0"
                                            marginwidth="0"
                                            src="https://maps.google.com/maps?width=100%25&amp;height=300&amp;hl=en&amp;q={{ $attendance->long }},{{ $attendance->lat }}&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
                                        </iframe>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Photo</th>
                                <td><img width="350" src="{{ asset('/storage/photo/' . $attendance->photo) }}" alt=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
