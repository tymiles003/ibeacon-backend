@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
        Category
        <a href="/admin/category/"><button class="btn pull-left module_btn">Back</button></a>
    </div>
    <div class="main_body">
                <div class="col-xs-12">
                    @if(isset($message))
                        <p class="bg-success">{{$message}}</p>
                    @endif
                    <div class="module">
                        <div class="module_title">
                        Add Category
                        </div>
                        <div class="module_body">
                        {{ Form::open(array('url' => 'admin/category', 'files' => true)) }}  
                        {{ Form::hidden('id') }}
                        <div class="form-group">
                        {{ Form::label('catname', 'Name', array('class' => 'col-xs-3')) }}
                        {{ Form::text('catname', Input::get('catname'), array('class' => 'col-xs-3')) }}
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