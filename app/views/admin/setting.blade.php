@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
        Setting
    </div>
    <div class="main_body">
        <div class="col-xs-12">
            @if(isset($message))
            <p class="bg-success">{{$message}}</p>
            @endif
            <div class="module">
                <div class="module_title">
                    Configuration
                </div>
                <div class="module_body">
                    {{ Form::open(array('url' => 'admin/setting')) }}
                    <div class="form-group">
                        {{ Form::label('sitename', 'Name', array('class' => 'col-xs-3')) }}
                        {{ Form::text('sitename', Setting::getName(), array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('uuid', 'UUID', array('class' => 'col-xs-3')) }}
                        {{ Form::text('uuid', Setting::getUUID(), array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">

                        {{ Form::label('beaconmajor', 'Beaon major', array('class' => 'col-xs-3')) }}
                        {{ Form::text('beaconmajor', Setting::getMajor(), array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('beaconminor', 'Beacon minor', array('class' => 'col-xs-3')) }}
                        {{ Form::text('beaconminor', Setting::getMinor(), array('class' => 'col-xs-3')) }}
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
