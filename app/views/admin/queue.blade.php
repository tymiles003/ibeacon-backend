@extends('admin.layout')
@section('content')
<div class="row">
                <div class="col-xs-12">
                    <div class="module">
                        <div class="module_title">
                        Queue
                        </div>
                        <table>
                            <tr>
                                <td>Number</td>
                                <td>Last name</td>
                                <td>Status</td>
                                <td>Time</td>
                                <td>Action</td>
                            </tr>
                            @foreach ($queues as $queue)
                            <tr>
                                <td>{{$queue->id}}</td>
                                <td>{{$queue->lastname}}</td>
                                <td><span id="entered_{{$queue->id}}">{{$queue->entered}}</span></td>
                                <td>{{$queue->created_at}}</td>
                                <td><button id="{{$queue->id}}" class="btn">Enter</button></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
@stop

@section('script')
<script>
    maxID = {{$maxQueueNumber}};
    function refreshQueue(id){
        $.get( "/queues/listQueue/"+id, function( data ) {
              for(i=0;i<data.length;i++){
                temp = data[i];
                $('table').append("<tr><td>"+temp.id+"</td><td>"+temp.lastname+"</td><td><span id='entered_"+temp.id+"'>"+temp.entered+"</span></td><td>"+temp.created_at+"</td><td><button id='"+temp.id+"' class='btn'>Enter</button></td></tr>");
              }
              if(data.length >= 1){
                 maxID = data[i-1].id;
              }
        });
    }
    $(document).ready(function($) {
        setInterval(function(){refreshQueue(maxID)}, 1000);
        $('body').on('click', 'button', function(){
            var thisID = $(this).attr('id');
            $.ajax({
              url: "/queue/"+thisID,
              type: "PUT"
            }).done(function(){
                var targetID = "#entered_"+thisID;
                $(targetID).html('<b>Entered</b>');
            });
        });
    });
    </script>
@stop