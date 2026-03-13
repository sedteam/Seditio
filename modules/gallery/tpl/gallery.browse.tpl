<!-- BEGIN: MAIN -->

<main id="page">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{GALLERY_BROWSE_TITLE}</h1>

			<div class="section-desc">
				{GALLERY_BROWSE_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: GALLERY -->

			<div class="row row-flex">

				<!-- BEGIN: ROW -->

				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

					<div class="gallery-item">

						<figure class="gallery-container">
							<a class="gallery-img-link" href="{GALLERY_BROWSE_ROW_VIEWURL}">
								<img class="gallery-img" src="{GALLERY_BROWSE_ROW_THUMB|crop_image(%s, 800, 600)}" />
							</a>
							<figcaption class="gallery-content">
								<div class="gallery-info">
									<div class="gallery-date">{GALLERY_BROWSE_ROW_DATE}</div>
									<div class="gallery-admin">{GALLERY_BROWSE_ROW_ADMIN}</div>
								</div>
								<div class="gallery-title">
									<h3><a href="{GALLERY_BROWSE_ROW_VIEWURL}">{GALLERY_BROWSE_ROW_TITLE}</a></h3>
								</div>
								<!-- BEGIN: GALLERY_BROWSE_ROW_DESC -->
								<div class="gallery-desc">
									<p>{GALLERY_BROWSE_ROW_DESC|strip_tags}</p>
								</div>
								<!-- END: GALLERY_BROWSE_ROW_DESC -->
							</figcaption>
						</figure>

					</div>

				</div>

				<!-- END: ROW -->

			</div>

			<!-- END: GALLERY -->

		</div>

	</div>

</main>

<!-- END: MAIN -->