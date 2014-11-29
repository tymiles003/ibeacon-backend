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
                    <div class="form-group">
                        {{ Form::label('mallsystem', 'Mall System URL', array('class' => 'col-xs-3')) }}
                        {{ Form::text('mallsystem', Setting::getMallSystem(), array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('malluser', 'Mall API Username', array('class' => 'col-xs-3')) }}
                        {{ Form::text('malluser', Setting::getMallUser(), array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('mallpw', 'Mall API Password', array('class' => 'col-xs-3')) }}
                        {{ Form::password('mallpw', array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('ip', 'IP', array('class' => 'col-xs-3')) }}
                        {{ Form::text('ip', Setting::getIP(), array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('port', 'Websocket Port', array('class' => 'col-xs-3')) }}
                        {{ Form::text('port', Setting::getPort(), array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('pport', 'ZeroMQ Port', array('class' => 'col-xs-3')) }}
                        {{ Form::text('pport', Setting::getPPort(), array('class' => 'col-xs-3')) }}
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
