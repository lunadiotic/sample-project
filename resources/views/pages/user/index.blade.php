@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="#" class="btn btn-sm btn-primary mb-2">Add</a>
            <div class="card">
                <div class="card-header">
                    User
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>eMail</th>
                                <th>Photo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <img width="50" height="50" src="{{ asset('storage/profile/'.$user->photo) }}" alt="">
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
