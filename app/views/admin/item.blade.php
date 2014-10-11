@extends('admin.layout')
@section('content')
<div class="row">
                <div class="col-xs-12">
                <a href="/admin/item/create"><button class="btn pull-right module_btn">Add Item</button></a>
                    <div class="module">
                        <div class="module_title">
                        Item
                        </div>
                        <table>
                            <tr>
                                <td>Number</td>
                                <td>Last name</td>
                                <td>Status</td>
                                <td>Last Modified</td>
                                <td>Action</td>
                            </tr>
                            @foreach ($items as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->itemname}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->updated_at}}</td>
                                <td><a href="/admin/item/{{$item->id}}"><button class="btn">Edit</button></a></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
@stop