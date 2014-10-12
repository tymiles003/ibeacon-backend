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
                        Category Edit
                        </div>
                        <div class="module_body">
                        {{ Form::model($cat, array('files' => true, 'url' => 'admin/category/'.$cat->id, $cat->id)) }}  
                        {{ Form::hidden('id') }}
                        <div class="form-group">
                        {{ Form::label('catname', 'Name', array('class' => 'col-xs-3')) }}
                        {{ Form::text('catname', $cat->catname, array('class' => 'col-xs-3')) }}
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