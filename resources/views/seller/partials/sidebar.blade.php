<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">


        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            {{--<li class="header">{{ trans('admin::lang.menu') }}</li>--}}
            @include('seller.partials.menu')
            {{--@each('admin.partials.menu', Admin::menu(), 'item')--}}

        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
