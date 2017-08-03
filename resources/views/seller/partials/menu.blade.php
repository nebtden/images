
<li>
    <a href="{{ url('seller/store') }}"><i class="fa fa-shopping-bag"></i>
        <span>@lang('seller.Stores')</span>
    </a>
</li>


<li>
    <a href="{{ url('seller/transition') }}"><i class="fa fa-plane"></i>
        <span>@lang('seller.Transit')</span>
    </a>
</li>
<li>
    <a href="{{url('seller/storage') }}"><i class="fa fa-home"></i>
        <span>@lang('seller.Storage')</span>
    </a>
</li>
{{--<li>
    <a href="{{url('seller/freight') }}"><i class="fa fa-bars"></i>
        <span>派送费</span>
    </a>
</li>--}}
<li>
    <a href="{{url('seller/transaction') }}"><i class="fa fa-usd"></i>
        <span>@lang('seller.Transaction')</span>
    </a>
</li>

<li class="treeview active">
    <a href="#">
        <i class="fa fa-bar-chart-o"></i>
        <span>@lang('seller.Statistics')</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu menu-open" style="display: block;">
        <li>
            <a href="{{url('seller/day') }}"><i class="fa fa-bar-chart-o"></i>
                <span>@lang('seller.Day') @lang('seller.Statistics')</span>
            </a>
        </li>
        <li>
            <a href="{{url('seller/week') }}"><i class="fa fa-bar-chart-o"></i>
                <span>@lang('seller.Week') @lang('seller.Statistics')</span>
            </a>
        </li>
        <li>
            <a href="{{url('seller/month') }}"><i class="fa fa-bar-chart-o"></i>
                <span>@lang('seller.Month') @lang('seller.Statistics')</span>
            </a>
        </li>

    </ul>
</li>

<li>
    <a href="/seller/setting/{{ Seller::user()->id }}/edit"><i class="fa fa-gears"></i>
        <span>@lang('seller.Reset Password')</span>
    </a>
</li>


{{--<li class="treeview">--}}
{{--<a href="#">--}}
{{--<i class="fa {{$item['icon']}}"></i>--}}
{{--<span>{{$item['title']}}</span>--}}
{{--<i class="fa fa-angle-left pull-right"></i>--}}
{{--</a>--}}
{{--<ul class="treeview-menu">--}}
{{--@foreach($item['children'] as $item)--}}
{{--@include('admin.partials.menu', $item)--}}
{{--@endforeach--}}
{{--</ul>--}}
{{--</li>--}}

