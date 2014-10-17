@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
        Table
        <a href="/admin/table/"><button class="btn pull-left module_btn">Back</button></a>
    </div>
    <div class="main_body">
                <div class="col-xs-12">
                    @if(isset($message))
                        <p class="bg-success">{{$message}}</p>
                    @endif
                    <div class="module">
                        <div class="module_title">
                        Add Table
                        </div>
                        <div class="module_body">
                        {{ Form::open(array('url' => 'admin/table')) }}  
                        {{ Form::hidden('id') }}
                        <div class="form-group">
                        {{ Form::label('tableno', 'Table Number', array('class' => 'col-xs-3')) }}
                        {{ Form::text('tableno', Input::get('tableno'), array('class' => 'col-xs-3')) }}
                        </div>
                        <div class="form-group">
                        {{ Form::label('capacity', 'Capacity', array('class' => 'col-xs-3')) }}
                        {{ Form::text('capacity', Input::get('capacity'), array('class' => 'col-xs-3')) }}
                        </div>
                        <div class="col-xs-3">
                        </div>
                        <div class="col-xs-4">
                        {{ Form::submit('Add', array('class' => 'btn')) }}
                        </div>
                        {{ Form::close() }}
                        </div>
                    </div>
                </div>
                </div>
            </div>
@stop