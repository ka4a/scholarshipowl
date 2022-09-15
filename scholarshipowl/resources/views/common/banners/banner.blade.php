<div class="banner-container">
    <a href="{!! $banner->getUrl() !!}" target="_blank">
        @if ($banner->getType() === \App\Entity\Banner::TYPE_IMAGE)
            <div class="banner banner-image">
                @if ($banner->getHeaderContent())
                    <h2 class="banner-title">
                        {!! $banner->getHeaderContent() !!}
                    </h2>
                @endif
                @if ($banner->getUrlDisplay())
                    <span class="visible-link">{!! $banner->getUrlDisplay() !!}</span>
                @endif
                <img height="250" src="{!! $banner->getImage()->getPublicUrl() !!}" />
            </div>
        @endif
        @if ($banner->getType() === \App\Entity\Banner::TYPE_TEXT)
            <div class="banner banner-text">
                @if ($banner->getHeaderContent())
                    <h2 class="banner-title">
                        {!! $banner->getHeaderContent() !!}
                    </h2>
                @endif
                @if ($banner->getUrlDisplay())
                    <span class="visible-link">{!! $banner->getUrlDisplay() !!}</span>
                @endif
                <div class="banner-content">
                    {!! $banner->getText() !!}
                </div>
            </div>
        @endif
    </a>
</div>
