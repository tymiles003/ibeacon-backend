@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
                    Types of queue
                    <a href="/admin/queue_type/create"><button class="btn pull-left module_btn"><i class="fa fa-plus-circle"></i> Add Type</button></a>
                </div>
                                <div class="main_body">
                <div class="col-xs-12">
                
                    <div class="module">
                        <div class="module_title">
                        Types of queue
                        </div>
                        <table>
                            <tr>
                                <td>Name</td>
                                <td>Capacity</td>
                                <td>Last Modified</td>
                                <td>Action</td>
                            </tr>
                            @foreach ($queueTypes as $queueType)
                            <tr>
                                <td>{{$queueType->name}}</td>
                                <td>{{$queueType->capacity}}</td>
                                <td>{{$queueType->updated_at}}</td>
                                <td>
                                    <a href="/admin/table/{{$queueType->id}}"><button class="btn"><i class="fa fa-pencil"></i> Edit</button></a>
                                    <button id="delete_btn" data-id="{{$queueType->id}}" class="btn"><i class="fa fa-remove"></i> Delete</button>
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
              url: "/queueType/"+$(this).attr('data-id'),
              type: "DELETE"
            }).done(function(){
                $this.parent().parent().hide();
            });
    });
});
</script>
@stop