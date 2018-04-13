<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <!-- Left Side Of Navbar -->
    <ul class="navbar-nav mr-auto">

    </ul>

    <!-- Right Side Of Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        @guest
            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</div>
<p>Edit Tasks</p>
@foreach($tasks as $task)
    <form method="post" action="update_task">
        <input hidden name="id" value="{{$task->id}}">
        <p>Task ID {{$task->id}}</p>
        <p>Task Description <input name="description" value="{{$task->description}}"></p>
        @if($task->type == 0)
            <p>Task Type is Time</p>

        <?php
            $task->date = str_replace(' ', 'T', $task->date);
        ?>
            <p>Task Time <input type="datetime-local" name="date" value="{{$task->date}}"></p>
        @else
            <p>Task Type is Location</p>
            <p>Task Location Longitude is <input name="locationX" value="{{$task->locationX}}"></p>
            <p>Task Location Latitude is <input name="locationY" value="{{$task->locationY}}"></p>
        @endif
        <input type="submit">
    </form>
    <a href="delete_task?id={{$task->id}}">Delete Task</a>
    <br>
    <br>
@endforeach
<form method="post" action="create_task">
    <p>Add a new task</p>
    <p>Type: <select id="type" name="type">
                <option value="0">Date</option>
                <option value="1">Location</option>
            </select>
    </p>
    <p>Description: <input required name="description" type="text"></p>
    <p id="date">Date: <input type="datetime-local" name="date"></p>
    <p id="Xcord" hidden>Location Longitude Cord: <input name="locationX"></p>
    <p id="Ycord" hidden>Location Latitude Cord: <input name="locationY"></p>
    <input type="submit">
</form>
<script>
    $(document).ready(function(){
        $("#type").change(function(){
            if($("#type").val() == 0){
                $("#Xcord").hide();
                $("#Ycord").hide();
                $("#date").show();
            }
            else {
                $("#Xcord").show();
                $("#Ycord").show();
                $("#date").hide();
            }
        });
        window.setInterval(function(){
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
                function showPosition(position) {
                    var x = position.coords.longitude;
                    var y = position.coords.latitude;
                    $.post("trigger",
                        {
                            date: "{{\Carbon\Carbon::now()}}",
                            locationX: x,
                            locationY: y
                        },function(data){
                        if(data['dates'] == "none"){

                        }
                        else {
                            data['dates'].forEach(function (element) {
                                alert("Task Id: " + element.id + " Task Description: " + element.description);
                            });
                        }
                        if(data['locations'] == "none"){

                        }
                        else {
                            data['locations'].forEach(function (element) {
                                alert("Task Id: " + element.id + " Task Description: " + element.description);
                            });
                        }
                        });
                }
            }
        }, 5000);
    });
</script>
