@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
        API Access
        <a href="/admin/access/"><button class="btn pull-left module_btn">Back</button></a>
    </div>
    <div class="main_body">
                <div class="col-xs-12">
                    @if(isset($message))
                        <p class="bg-success">{{$message}}</p>
                    @endif
                    <div class="module">
                        <div class="module_title">
                        Edit User
                        </div>
                        <div class="module_body">
                        {{ Form::model($user, array('url' => 'admin/access/'.$user->id, $user->id)) }}
                        {{ Form::hidden('id') }}
                        <div class="form-group">
                        {{ Form::label('email', 'Email', array('class' => 'col-xs-3')) }}
                        {{ Form::text('email', $user->email, array('class' => 'col-xs-3')) }}
                        </div>
                        <div class="form-group">
                        {{ Form::label('password', 'Password', array('class' => 'col-xs-3')) }}
                        {{ Form::password('password', array('class' => 'col-xs-3')) }}
                        </div>
                        <div class="form-group">
                        {{ Form::label('right', 'Right', array('class' => 'col-xs-3')) }}
                        {{ Form::select('right', array('1' => 'Administrator', '2' => 'API Access'), $user->right) }}
                        </div>
                        <div class="col-xs-3">
                        </div>
                        <div class="col-xs-4">
                        {{ Form::submit('Edit', array('class' => 'btn')) }}
                        </div>
                        {{ Form::close() }}
                        </div>
                    </div>
                </div>
                </div>
            </div>
@stop