<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-{{ $boxIcon ?? 'university' }}"></i>
                    {{ $boxName ?? '' }}
                </div>

                <div class="box-icons">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                        <i class="fa fa-expand"></i>
                    </a>
                </div>

                <div class="no-move"></div>
            </div>

            <div class="box-content">
                @yield('box-content')
            </div>
        </div>
    </div>
</div>
