@extends('admin.layout')
@section('content')
    <div class="row">
        <div class="main_header">
            Category
            <a href="/admin/category/create"><button class="btn pull-left module_btn"><i class="fa fa-plus-circle"></i> Add Category</button></a>
        </div>
            <div class="main_body">
                <div class="col-xs-12">
                    <div class="module">
                        <div class="module_title">
                            Item
                        </div>
                        <table>
                                <tr>
                                    <td>Category ID</td>
                                    <td>Category Name</td>
                                    <td>Last Modified</td>
                                    <td>Action</td>
                                </tr>
                                @foreach ($cats as $cat)
                                <tr>
                                    <td>{{$cat->id}}</td>
                                    <td>{{$cat->catname}}</td>
                                    <td>{{$cat->updated_at}}</td>
                                    <td>
                                        <a href="/admin/category/{{$cat->id}}"><button class="btn"><i class="fa fa-pencil"></i> Edit</button></a>
                                        <button id="delete_btn" data-id="{{$cat->id}}" class="btn"><i class="fa fa-remove"></i> Delete</button>
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
              url: "/category/"+$(this).attr('data-id'),
              type: "DELETE"
            }).done(function(){
                $this.parent().parent().hide();
            });
    });
});
</script>
@stop