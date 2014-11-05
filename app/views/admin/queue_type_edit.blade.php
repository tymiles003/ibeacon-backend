@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
        Types of Queue
        <a href="/admin/queue_type/"><button class="btn pull-left module_btn">Back</button></a>
    </div>
    <div class="main_body">
                <div class="col-xs-12">
                    @if(isset($message))
                        <p class="bg-success">{{$message}}</p>
                    @endif
                    <div class="module">
                        <div class="module_title">
                        Queue type edit
                        </div>
                        <div class="module_body">
                        {{ Form::model($queueType, array('url' => 'admin/queue_type/'.$queueType->id, $queueType->id)) }}
                        {{ Form::hidden('id') }}
                        <div class="form-group">
                        {{ Form::label('name', 'Name', array('class' => 'col-xs-3')) }}
                        {{ Form::text('name', $queueType->name, array('class' => 'col-xs-3')) }}
                        </div>
                        <div class="form-group">
                        {{ Form::label('capacity', 'Capacity', array('class' => 'col-xs-3')) }}
                        {{ Form::text('capacity', $queueType->capacity, array('class' => 'col-xs-3')) }}
                        </div>
                        <div class="form-group">
                        {{ Form::label('disabled', 'Status', array('class' => 'col-xs-3')) }}
                        {{ Form::select('disabled', array("0" => "Open", "1" => "Close"), $queueType->disabled) }}
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