<fieldset>
    <div class="form-group">
        <label class="col-xs-3 control-label">Title</label>
        <div class="col-xs-6">
            {{ Form::text('offer-wall-title', $offerWall ? $offerWall->getTitle() : null, ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-3 control-label">Description</label>
        <div class="col-xs-6">
            {{ Form::textarea('offer-wall-description', $offerWall ? $offerWall->getDescription() : null, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-xs-4">
            <label class="control-label">Banner 1</label>
            {{ Form::select('offer-wall-banner1', $banners, $offerWall && $offerWall->getBanner1() ? $offerWall->getBanner1()->getId() : null, ['class' => 'form-control']) }}
        </div>
        <div class="col-xs-4">
            <label class="control-label">Banner 2</label>
            {{ Form::select('offer-wall-banner2', $banners, $offerWall && $offerWall->getBanner2() ? $offerWall->getBanner2()->getId() : null, ['class' => 'form-control']) }}
        </div>
        <div class="col-xs-4">
            <label class="control-label">Banner 3</label>
            {{ Form::select('offer-wall-banner3', $banners, $offerWall && $offerWall->getBanner3() ? $offerWall->getBanner3()->getId() : null, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-xs-4">
            <label class="control-label">Banner 4</label>
            {{ Form::select('offer-wall-banner4', $banners, $offerWall && $offerWall->getBanner4() ? $offerWall->getBanner4()->getId() : null, ['class' => 'form-control']) }}
        </div>
        <div class="col-xs-4">
            <label class="control-label">Banner 5</label>
            {{ Form::select('offer-wall-banner5', $banners, $offerWall && $offerWall->getBanner5() ? $offerWall->getBanner5()->getId() : null, ['class' => 'form-control']) }}
        </div>
        <div class="col-xs-4">
            <label class="control-label">Banner 6</label>
            {{ Form::select('offer-wall-banner6', $banners, $offerWall && $offerWall->getBanner6() ? $offerWall->getBanner6()->getId() : null, ['class' => 'form-control']) }}
        </div>
    </div>

</fieldset>
