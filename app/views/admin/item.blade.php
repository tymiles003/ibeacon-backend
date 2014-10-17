@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
                    Item
                    <a href="/admin/item/create"><button class="btn pull-left module_btn"><i class="fa fa-plus-circle"></i> Add Item</button></a>
                </div>
                                <div class="main_body">
                <div class="col-xs-12">
                
                    <div class="module">
                        <div class="module_title">
                        Item
                        </div>
                        <table>
                            <tr>
                                <td>Item ID</td>
                                <td>Item Name</td>
                                <td>Price</td>
                                <td>Last Modified</td>
                                <td>Action</td>
                            </tr>
                            @foreach ($items as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->itemname}}</td>
                                <td>$ {{$item->price}}</td>
                                <td>{{$item->updated_at}}</td>
                                <td>
                                    <a href="/admin/item/{{$item->id}}"><button class="btn"><i class="fa fa-pencil"></i> Edit</button></a>
                                    <button id="delete_btn" data-id="{{$item->id}}" class="btn"><i class="fa fa-remove"></i> Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                </div>
            </div>
@stop

@section('script')
<script>
$(document).ready(function() {
    $('body').on('click', '#delete_btn', function(){
        var $this = $(this);
        $.ajax({
              url: "/item/"+$(this).attr('data-id'),
              type: "DELETE"
            }).done(function(){
                $this.parent().parent().hide();
            });
    });
});
</script>
@stop