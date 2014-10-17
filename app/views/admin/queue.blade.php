@extends('admin.layout')
@section('content')
<div class="row">
<div class="main_header">
        Queue
    </div>
    <div class="main_body">
        <div class="col-xs-4">
            <div class="tile tile_red">
                <div class="tile_content" id="no_of_queues">
                Loading....
                </div>
                <div class="tile_heading">
                    Number of queues
                    <div class="tile_icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="tile tile_blue">
                <div id="avg_time" class="tile_content">
                Loading....
                </div>
                <div class="tile_heading">
                    Average waiting time
                    <div class="tile_icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-4">
        <a href="/admin/queue_stat/{{$type}}">
            <div class="tile tile_orange">
                <div class="tile_content">
                Stat
                </div>
                <div class="tile_heading">
                    Get Detailed statistics
                    <div class="tile_icon">
                        <i class="fa fa-line-chart"></i>
                    </div>
                </div>
            </div>
            </a>
        </div>
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
                                <td>{{$queue->queue_number}}</td>
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
            </div>
@stop

@section('script')
<script>
    maxID = {{$maxQueueNumber}};
    type = {{$type}};
    noOfQueue = $('table tr').length-1;
    function refreshQueue(number){
        $.get( "/queues/listQueue/"+number+"/type/"+type, function( data ) {
              for(i=0;i<data.length;i++){
                temp = data[i];
                $('table').append("<tr><td>"+temp.queue_number+"</td><td>"+temp.lastname+"</td><td><span id='entered_"+temp.id+"'>"+temp.entered+"</span></td><td>"+temp.created_at+"</td><td><button id='"+temp.id+"' class='btn'>Enter</button></td></tr>");
              }
              if(data.length >= 1){
                 maxID = data[i-1].id;
              }
              noOfQueue += data.length;
            $('#no_of_queues').html(noOfQueue);
        });
        $.get( "/queues/waitingTime/"+type, function(data) {

            $('#avg_time').html(data.time+" seconds");
        });
    }
    $(document).ready(function($) {
        $('#no_of_queues').html(noOfQueue);
        setInterval(function(){refreshQueue(maxID)}, 1000);
        $('body').on('click', 'button', function(){
            var $this = $(this);
            var thisID = $(this).attr('id');
            $.ajax({
              url: "/queue/"+thisID,
              type: "PUT"
            }).done(function(){
                $this.attr("disabled", true);
                var targetID = "#entered_"+thisID;
                $(targetID).html('<b>Entered</b>');
                noOfQueue--;
                $('#no_of_queues').html(noOfQueue);
            });
        });
    });
    </script>
@stop