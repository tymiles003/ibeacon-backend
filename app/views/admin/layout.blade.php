<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{Setting::getName()}} Admin Panel</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <!--jQuery 1.11.0-->
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="sidebar">
        <ul>
            <li><a href="/admin/setting"><i class="fa fa-dashboard"></i><span>Setting</span></a></li>
            <li><a href="/admin/queue_type"><i class="fa fa-dashboard"></i><span>Queue Type</span></a></li>
            <li><a href="#"><i class="fa fa-users"></i><span>Queue</span></a>
                <ul>
                    @foreach (QueueType::all() as $queuetype)
                    <li><a href="/admin/queue/{{$queuetype->id}}">
                        <i class="fa fa-users"></i>
                        <span>{{ucfirst($queuetype->name)}}</span></a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li><a href="/admin/access"><i class="fa fa-key"></i><span>API Access</span></a></li>
        </ul>
    </nav>
    <div class="main">
        @yield('content')
    </div>
</body>
@yield('script')
</html>
