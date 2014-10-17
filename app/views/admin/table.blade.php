@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
                    Table
                    <a href="/admin/table/create"><button class="btn pull-left module_btn"><i class="fa fa-plus-circle"></i> Add Table</button></a>
                </div>
                <div class="main_body">
                <div class="col-xs-12">
                
                    <div class="module">
                        <div class="module_title">
                        Table
                        </div>
                        <table>
                            <tr>
                                <td>Table ID</td>
                                <td>Table Number</td>
                                <td>Capacity</td>
                                <td>Last Modified</td>
                                <td>Action</td>
                            </tr>
                            @foreach ($tables as $table)
                            <tr>
                                <td>{{$table->id}}</td>
                                <td>{{$table->tableno}}</td>
                                <td>$ {{$table->capacity}}</td>
                                <td>{{$table->updated_at}}</td>
                                <td>
                                    <a href="/admin/table/{{$table->id}}"><button class="btn"><i class="fa fa-pencil"></i> Edit</button></a>
                                    <button id="delete_btn" data-id="{{$table->id}}" class="btn"><i class="fa fa-remove"></i> Delete</button>
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
              url: "/table/"+$(this).attr('data-id'),
              type: "DELETE"
            }).done(function(){
                $this.parent().parent().hide();
            });
    });
});
</script>
@stop