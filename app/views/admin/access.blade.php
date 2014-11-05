@extends('admin.layout')
@section('content')
    <div class="row">
        <div class="main_header">
            API Access
            <a href="/admin/access/create"><button class="btn pull-left module_btn"><i class="fa fa-plus-circle"></i> Add Users</button></a>
        </div>
            <div class="main_body">
                <div class="col-xs-12">
                    <div class="module">
                        <div class="module_title">
                            Credentials
                        </div>
                        <table>
                                <tr>
                                    <td>User ID</td>
                                    <td>Email</td>
                                    <td>Permission</td>
                                    <td>Last Modified</td>
                                    <td>Action</td>
                                </tr>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                    @if($user->right == 1)
                                    Administrator
                                    @else
                                    API Access
                                    @endif
                                    </td>
                                    <td>{{$user->updated_at}}</td>
                                    <td>
                                        <a href="/admin/access/{{$user->id}}"><button class="btn"><i class="fa fa-pencil"></i> Edit</button></a>
                                        <button id="delete_btn" data-id="{{$user->id}}" class="btn"><i class="fa fa-remove"></i> Delete</button>
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
              url: "/admin/access/delete/"+$(this).attr('data-id'),
              type: "GET"
            }).done(function(){
                $this.parent().parent().hide();
            });
    });
});
</script>
@stop