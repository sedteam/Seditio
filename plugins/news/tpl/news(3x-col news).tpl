<!-- BEGIN: NEWS -->

<div class="row row-flex">

	<!-- BEGIN: PAGE_ROW -->
	<div class="col-xs-12 col-sm-6 col-md-4 post-col">

		<div class="post-item">
			<figure class="post-container">
				<a class="post-img-link" href="{PAGE_ROW_URL}" data-page="{PAGE_ROW_ID}">
					<picture>
						<source type="image/webp" srcset="{PAGE_ROW_THUMB|crop_image(%s, 800, 600, 0, 1)}">
						<img class="post-img" src="{PAGE_ROW_THUMB|crop_image(%s, 800, 600)}" />
					</picture>
				</a>
				<figcaption class="post-content">
					<div class="post-info">
						<div class="post-category" data-category="{PAGE_ROW_CATID}"><a href="{PAGE_ROW_CATURL}">{PAGE_ROW_CATTITLE}</a></div>
						<div class="post-date">{PAGE_ROW_DATE}</div>
					</div>
					<div class="post-title">
						<h3><a href="{PAGE_ROW_URL}" data-page="{PAGE_ROW_ID}">{PAGE_ROW_SHORTTITLE}</a></h3>
					</div>
					<div class="post-desc">
						<p>{PAGE_ROW_DESC|strip_tags}</p>
					</div>
					<div class="post-info">
						<div class="post-author"><a href="{PAGE_ROW_USERURL}">{PAGE_ROW_AVATAR}<span>{PAGE_ROW_AUTHOR}</span></a></div>
						<div class="post-comments"><i class="ic-message-circle"></i><a href="{PAGE_ROW_COMMENTS_URL}">{PAGE_ROW_COMMENTS_COUNT}</a></div>
					</div>
				</figcaption>
			</figure>
		</div>

	</div>
	<!-- END: PAGE_ROW -->

</div>

<!-- BEGIN: NEWS_PAGINATION_BM -->

<div class="pagination-box">

	<ul class="pagination">
		<li class="page-item">{NEWS_PAGEPREV}</li>
		{NEWS_PAGINATION}
		<li class="page-item">{NEWS_PAGENEXT}</li>
	</ul>

</div>

<!-- END: NEWS_PAGINATION_BM -->

<!-- END: NEWS -->