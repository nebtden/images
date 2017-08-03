<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图片展示</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/baguetteBox.min.css">
    <link rel="stylesheet" href="css/gallery-clean.css">
    <style>
        .jq22-demo {
            text-align: center;
            margin-top: 30px;
        }

        .jq22-demo a {
            padding-left: 20px;
            padding-right: 20px;
        }
    </style>
</head>
<body>
<div class="htmleaf-container">
    <div class="container gallery-container">

        <h1>Bootstrap 3 Gallery</h1>

        <div class="jq22-demo">
            <a href="index.html" class="current">Clean Layout</a>
            <a href="index2.html">Fluid Layout</a>
            <a href="index3.html">Grid Layout</a>
            <a href="index4.html">Thumbnails</a>
        </div>

        <p class="page-description text-center">Clean Layout With Minimal Styles</p>

        <div class="tz-gallery">

            <div class="row">
                @foreach($images as $image)

                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <a class="lightbox" href="images/park.jpg">
                                <img src="{{url('images')}}/{{$image->img}}" alt="Park">
                            </a>
                            <div class="caption">
                                <h3>{{$image->title}}</h3>

                                <span></span>
                                <p>
                                    微信：
                                    @if (Auth::check())
                                        {{$image->weixin}}
                                    @else
                                        请登录之后查看
                                    @endif
                                </p>

                                <p>
                                    QQ:
                                    @if (Auth::check())
                                        {{$image->weixin}}
                                    @else
                                        请登录之后查看
                                    @endif
                                </p>
                                <p>{{$image->desc}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
            {{ $images->links() }}
        </div>

    </div>

</div>

<script type="text/javascript" src="js/baguetteBox.min.js"></script>
<script type="text/javascript">
    baguetteBox.run('.tz-gallery');
</script>
</body>
</html>