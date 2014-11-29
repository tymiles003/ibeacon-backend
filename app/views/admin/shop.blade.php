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
                    {{ Form::open(array('url' => 'admin/shop', 'files' => true)) }}
                    {{ Form::hidden('id', '1') }}
                    <div class="form-group">
                        {{ Form::label('name', 'Name', array('class' => 'col-xs-3')) }}
                        {{ Form::text('name', $shop->name, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('category_id', 'Category', array('class' => 'col-xs-3')) }}
                        {{ Form::select('category_id', $catOption, Input::get('category_id')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('phone', 'Phone', array('class' => 'col-xs-3')) }}
                        {{ Form::text('phone', $shop->phone, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('address', 'Address', array('class' => 'col-xs-3')) }}
                        {{ Form::text('address', $shop->address, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('intro', 'Introduction', array('class' => 'col-xs-3')) }}
                        {{ Form::textarea('intro', $shop->intro, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('queue', 'Queue Server URL', array('class' => 'col-xs-3')) }}
                        {{ Form::text('queue', $shop->queue, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('opentime', 'Open Time (hh:mm)', array('class' => 'col-xs-3')) }}
                        {{ Form::text('opentime', $shop->opentime, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('closetime', 'Close Time(hh:mm)', array('class' => 'col-xs-3')) }}
                        {{ Form::text('closetime', $shop->closetime, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('beacon_major', 'Beacon major', array('class' => 'col-xs-3')) }}
                        {{ Form::text('beacon_major', $shop->beacon_major, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('beacon_minor', 'Beacon minor', array('class' => 'col-xs-3')) }}
                        {{ Form::text('beacon_minor', $shop->beacon_minor, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('logo', 'Logo', array('class' => 'col-xs-3')) }}
                        {{ Form::file('logo', $attributes = array()); }}
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
