<!-- BEGIN: MAIN -->

<main id="page">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{GALLERY_DETAILS_TITLE}</h1>

			<div class="page-info">
				<div class="page-date"><i class="ic-clock"></i> {GALLERY_DETAILS_DATE}</div>
			</div>

			<div class="section-desc">
				{GALLERY_DETAILS_SUBTITLE}
			</div>

		</div>

		<!-- BEGIN: GALLERY_DETAILS_ADMIN -->
		<div class="section-admin">
			{GALLERY_DETAILS_ADMIN}
		</div>
		<!-- END: GALLERY_DETAILS_ADMIN -->

		<div class="section-body">

			<div class="row row-flex">

				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

					<div class="gallery-bigimg">

						<figure class="gallery-container">
							<a class="gallery-img-link" href="{GALLERY_DETAILS_VIEWURL}">
								<img class="gallery-img" src="{GALLERY_DETAILS_THUMB|resize_image(%s, 1200, 1200)}" />
							</a>
						</figure>

					</div>

					<div class="gallery-arrows">
						<div class="gallery-arrows-col">{GALLERY_DETAILS_PREV}</div>
						<div class="gallery-arrows-col">{GALLERY_DETAILS_BACK}</div>
						<div class="gallery-arrows-col">{GALLERY_DETAILS_ZOOM}</div>
						<div class="gallery-arrows-col">{GALLERY_DETAILS_NEXT}</div>
					</div>

					<div class="gallery-browser">{GALLERY_DETAILS_BROWSER}</div>

				</div>

				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

					<!-- BEGIN: GALLERY_DETAILS_DESC -->
					<div class="gallery-text">
						{GALLERY_DETAILS_DESC}
					</div>
					<!-- END: GALLERY_DETAILS_DESC -->

					<div class="page-comments spoiler-container {GALLERY_DETAILS_COMMENTS_ISSHOW}">

						<div class="comments-box-title">
							<h3><a href="{GALLERY_DETAILS_COMMENTS_URL}">{PHP.skinlang.page.Comments} <i class="ic-socialbtn"></i> <span class="comments-amount">({GALLERY_DETAILS_COMMENTS_COUNT})</span>{GALLERY_DETAILS_COMMENTS_JUMP}</a></h3>
						</div>

						<div class="comments-box spoiler-body">
							{GALLERY_DETAILS_COMMENTS_DISPLAY}
						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->