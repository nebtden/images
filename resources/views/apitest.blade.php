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
        .content_one{margin-left: 40%}
        .input{margin-top:10px;margin-bottom: 40px}
    </style>
</head>
<body>
<div class="position-ref search_height">

    <div class="content_one">
        <label>物流轨迹查询接口</label>
        <form action="{{url('/api/search/')}}" method="post">
            <input class="input" type="text" name="waybill">
            <input type="submit" value="提交">
        </form>
    </div>

    <div class="content_one">
        <label>物流轨迹写入接口</label>
        <form action="{{url('/api/write/')}}" method="post">
            <input class="hidden" type="text" name="status_code" value="1">
            <input class="input" type="text" name="waybill_number" placeholder="运单号">
            <input class="input" type="text" name="position" placeholder="位置">
            <input class="input" type="date" name="add_time">
            <input class="input" type="text" name="remark" placeholder="详细信息">
            <input class="input" type="text" name="contact_name" placeholder="联系人姓名">
            <input class="input" type="text" name="contact_phone" placeholder="联系电话">
            <input type="submit" value="提交">
        </form>
    </div>

</div>
</body>
</html>

