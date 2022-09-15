<!-- Checkout popup modal -->
<div style="display:none;" id="payment-popup" class="modal fade payment-popups" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="payment-popup" aria-hidden="true" data-show-zero="0">
	<div id="payment-wizard" class="modal-dialog container pack">
		<div class="modal-content row text-center">
            <div class="navbar hidden">
                <div class="navbar-inner">
                    <ul class="nav nav-pills">
                        <li><a href="#step1" data-toggle="tab" data-step="1">Step 1</a></li>
                        <li><a href="#step2" data-toggle="tab" data-step="2">Step 2</a></li>
                        <li><a href="#step3" data-toggle="tab" data-step="3">Step 3</a></li>
                        <li><a href="#missionTabPayment" data-toggle="tab" data-step="4">Missions</a></li>
                        <li><a href="#step4" data-toggle="tab" data-step="5">Step 4</a></li>
                        <li><a href="#missionCongratulationsTab" data-toggle="tab" data-step="6">Missions 2</a></li>
                        <li><a href="#rafMissionTab" data-toggle="tab" data-step="7">Missions 3</a></li>
                    </ul>
                </div>
            </div>
			<!-- TAB CONTENT -->
            <div class="tab-content">
                <!-- +++++++++++++++++++++++++++++++++++++++ -->
				@include('includes/payment-step1')
                <!-- +++++++++++++++++++++++++++++++++++++++ -->
				@include('includes/payment-step2')
                <!-- +++++++++++++++++++++++++++++++++++++++ -->
				@include('includes/payment-step3')
            </div>
			<!-- / tab content -->
		</div>
	</div>
</div>
