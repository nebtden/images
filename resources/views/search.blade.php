<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="{{ URL::asset('css/googleapi.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ URL::asset('css/bootstrap/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
        <script type="text/javascript" src="{{ URL::asset('js/jquery.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/layer.js') }}"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Microsoft YaHei';
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref search_height">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-10">
                        <div class="input-group">
                            <input type="text" class="form-control" style="width: 650px" id="waybill_no" placeholder="please input the tracking number">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary">search</button>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <!--列表单-->
        <div class="track_block">
            @if(isset($list['response']) && !empty($list['response']))
                @foreach($list['response'] as $key=>$value)
                    <dl class="dl-horizontal dl_height">
                        <dt> {{date('Y-m-d H:i',$value['track_time'])}}</dt>
                        <dd>
                            contactName：{{$value['contact_name']}}，
                            telphone：{{$value['contact_phone']}},
                            location: {{$value['location']}}
                        </dd>
                    </dl>
                    <br />
                @endforeach
            @else
                <dl class="dl-horizontal dl_height">
                    <dt></dt>
                    <dd>
                       @if(!isset($list))
                           Please enter the tracking number you need search.
                       @elseif(isset($list['error']))
                            {{$list['error']}}
                       @endif
                    </dd>
                </dl>
            @endif
        </div>
    </body>
</html>
<script>
    $(".btn").click(function(){
        var waybill_no = $("#waybill_no").val();
        if(waybill_no == '' || typeof(waybill_no)=="undefined"){
            //信息框-例1
            layer.alert('Please input the tracking number', {icon: 5});
        }else{
            location.href = '/search/' + waybill_no;;
        }
    });
</script>
