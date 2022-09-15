<div class="modal fade congratulations-popup">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-xs-12">
                            <img src="{!! url('') !!}/assets/img/V.png" alt="V icon">
                            <h3>Congratulations!</h3>
                            <h4 class="success-message">{!! $message ?? null !!}</h4>
                            <script>
                                window.dataLayer = window.dataLayer || [];
                                window.dataLayer.push({
                                    'event': 'CongratulationsPopup'
                                });
                            </script>
                            <img src='//d.adroll.com/ipixel/K3IVFQQR6VGYBD4SUG2Y7J/Q5UPORP7KFHBRG4HXDH4N5?name=f3d189e8' width='1' height='1' />
                            <img src='//d.adroll.com/fb/ipixel/K3IVFQQR6VGYBD4SUG2Y7J/Q5UPORP7KFHBRG4HXDH4N5?name=f3d189e8' width='1' height='1' />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('tracking.sale')
</div>
