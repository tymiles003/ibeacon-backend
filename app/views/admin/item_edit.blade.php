@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
        Item
        <a href="/admin/item/"><button class="btn pull-left module_btn">Back</button></a>
    </div>
    <div class="main_body">
        <div class="col-xs-12">
            @if(isset($message))
            <p class="bg-success">{{$message}}</p>
            @endif
            <div class="module">
                <div class="module_title">
                    Item Edit
                </div>
                <div class="module_body">
                    {{ Form::model($item, array('files' => true, 'url' => 'admin/item/'.$item->id, $item->id)) }}
                    {{ Form::hidden('id') }}
                    <div class="form-group">
                        {{ Form::label('itemname', 'Name', array('class' => 'col-xs-3')) }}
                        {{ Form::text('itemname', $item->itemname, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('category_id', 'Category', array('class' => 'col-xs-3')) }}
                        {{ Form::select('category_id', $catOption, $item->category_id) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('price', 'Price', array('class' => 'col-xs-3')) }}
                        {{ Form::text('price', $item->price, array('class' => 'col-xs-3')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('itemimg', 'Image', array('class' => 'col-xs-3')) }}
                        {{ Form::file('itemimg', $attributes = array()); }}
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
