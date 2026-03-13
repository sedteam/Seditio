<!-- BEGIN: MAIN -->

<main id="page">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{GALLERY_HOME_TITLE}</h1>

			<div class="section-desc">
				{GALLERY_HOME_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: GALLERIES -->

			<div class="row row-flex">

				<!-- BEGIN: ROW -->

				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

					<div class="gallery-item">

						<figure class="gallery-container">
							<a class="gallery-img-link" href="{GALLERY_HOME_GALLERIES_ROW_URL}">
								<img class="gallery-img" src="{GALLERY_HOME_GALLERIES_ROW_THUMB|crop_image(%s, 800, 600)}" />
							</a>
							<figcaption class="gallery-content">
								<div class="gallery-info">
									<div class="gallery-date">{GALLERY_HOME_GALLERIES_ROW_UPDATED}</div>
								</div>
								<div class="gallery-title">
									<h3><a href="{GALLERY_HOME_GALLERIES_ROW_URL}">{GALLERY_HOME_GALLERIES_ROW_SHORTTITLE}</a></h3>
								</div>
								<div class="gallery-desc">
									<p>{GALLERY_HOME_GALLERIES_ROW_DESC|strip_tags}</p>
								</div>
								<div class="gallery-info">
									<div class="gallery-author"><a href="{GALLERY_HOME_GALLERIES_ROW_USERURL}">{GALLERY_HOME_GALLERIES_ROW_AVATAR}<span>{GALLERY_HOME_GALLERIES_ROW_OWNER}</span></a></div>
									<div class="gallery-count"><i class="ic-info"></i> {GALLERY_HOME_GALLERIES_ROW_COUNT} photos</div>
								</div>
							</figcaption>
						</figure>

					</div>

				</div>

				<!-- END: ROW -->

			</div>

			<!-- END: GALLERIES -->

		</div>

	</div>

</main>

<!-- END: MAIN -->