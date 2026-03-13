<!-- BEGIN: NEWS -->

<div class="news-box">

	<!-- BEGIN: PAGE_ROW -->
	<div class="news-item">

		<!-- BEGIN: PAGE_ROW_THUMB -->
		<div class="post-header">
			<figure class="post-container">
				<a class="post-img-link" href="{PAGE_ROW_URL}">
					<img class="post-img" src="{PAGE_ROW_THUMB|crop_image(%s, 800, 600)}" />
				</a>
			</figure>
		</div>
		<!-- END: PAGE_ROW_THUMB -->

		<div class="post-category"><a href="{PAGE_ROW_CATURL}">{PAGE_ROW_CATTITLE}</a></div>
		<div class="post-date">{PAGE_ROW_DATE}</div>

		<div class="post-title">
			<h2><a href="{PAGE_ROW_URL}" data-page="{PAGE_ROW_ID}" data-cat="{PAGE_ROW_CAT}">{PAGE_ROW_SHORTTITLE}</a></h2>
		</div>

		<div class="post-text page-text">
			{PAGE_ROW_TEXT}
		</div>

		<div class="post-info">
			<div class="post-author"><a href="{PAGE_ROW_USERURL}">{PAGE_ROW_AVATAR}<span>{PAGE_ROW_AUTHOR}</span></a></div>
			<div class="post-comments"><i class="ic-message-circle"></i><a href="{PAGE_ROW_COMMENTS_URL}">{PAGE_ROW_COMMENTS_COUNT}</a></div>
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