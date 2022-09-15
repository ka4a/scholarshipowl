
<div class="row">
	<div class="col-xs-12">
		<nav>
			<ul class="pagination">
				@for ($i = 1; $i <= ($pages > 3 ? 3 : $pages); $i++)
					@if ($i != $page)
						<li><a href="{{ $url }}?page={{ $i }}&{{ http_build_query($url_params) }}">{{ $i }}</a></li>
					@else
						<li><span class="active">{{ $i }}</span></li>
					@endif
				@endfor
                @if ($page >= 3)
                    @if ($page > 7 && $page < $pages - 3)
                        <li><span>...</span></li>
                    @endif
                    @for ($i = $page - 3; $i <= $pages && $i <= $page + 3; $i++)
                        @if ($i > 3 && $i < $pages - 3)
                            @if ($i != $page)
                                <li><a href="{{ $url }}?page={{ $i }}&{{ http_build_query($url_params) }}">{{ $i }}</a></li>
                            @else
                                <li><span class="active">{{ $i }}</span></li>
                            @endif
                        @endif
                    @endfor
                @endif
                @if ($pages > 3)
                    <li><span>...</span></li>
                    @for ($i = $pages - 3; $i <= $pages; $i++)
                        @if ($i != $page)
                            <li><a href="{{ $url }}?page={{ $i }}&{{ http_build_query($url_params) }}">{{ $i }}</a></li>
                        @else
                            <li><span class="active">{{ $i }}</span></li>
                        @endif
                    @endfor
                @endif
			</ul>
		</nav>
	</div>
</div>

