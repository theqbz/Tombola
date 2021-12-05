@extends('admin.main')

@section('content')
    <div class="container-lg">
        <!--aloldal fejléc-->
        <div class="row align-items-center mb-2">
            <div class="col text-md-start mb-3">
                <h1>
                    <div class="display-4">{{__('Users')}}</div>
                </h1>
            </div>
        </div>
        <!--nyeremények-->
        <style>
            table, th, td {
                border: 1px solid black;
            }

            table.center {
                margin-left: auto;
                margin-right: auto;
            }
        </style>
        <div class="row">
            <table border="1">
                <thead>
                <tr>
                    <th>id</th>
                    <th>first_name</th>
                    <th>last_name</th>
                    <th>email</th>
                    <th>status</th>
                    <th>address</th>
                    <th>dashboard_url</th>
                    <th>date_of_birth</th>
                    <th>hash</th>
                    <th colspan="2"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)



                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->first_name}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->status}}</td>
                        <td>{{$user->address}}</td>
                        <td>{{$user->dashboard_url}}</td>
                        <td>{{$user->date_of_birth}}</td>
                        <td>{{$user->hash}}</td>
                        <td>
                            {{Form::model($user, array('route' => array('admin.users.delete', ['id'=>$user->id]),'enctype'=>"multipart/form-data"))}}
                            {{Form::submit(__('Delete'),array('class'=>'btn btn-danger'))}}
                            {{ Form::close()}}
                        </td>

                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection