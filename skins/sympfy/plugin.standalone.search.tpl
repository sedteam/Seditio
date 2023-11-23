<!-- BEGIN: MAIN -->

<main id="plugins">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PLUGIN_SEARCH_TITLE}</h1>

			<div class="section-desc">
				{PLUGIN_SEARCH_DESC}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: PLUGIN_SEARCH_ERROR -->

			{PLUGIN_SEARCH_ERROR_BODY}

			<!-- END: PLUGIN_SEARCH_ERROR -->

			<!-- BEGIN: PLUGIN_SEARCH_FORM -->

			<form action="{PLUGIN_SEARCH_FORM_SEND}" method="post" name="search" id="search">

				<ul class="form responsive-form search-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.plu_searchin}</label></div>
						<div class="form-field">{PLUGIN_SEARCH_FORM_INPUT}</div>
					</li>

					<!-- BEGIN: PLUGIN_SEARCH_FORM_PAGES -->
					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Pages}</label></div>
						<div class="form-field">{PLUGIN_SEARCH_FORM_PAGES}</div>
					</li>
					<!-- END: PLUGIN_SEARCH_FORM_PAGES -->

					<!-- BEGIN: PLUGIN_SEARCH_FORM_FORUMS -->
					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Forums}</label></div>
						<div class="form-field">{PLUGIN_SEARCH_FORM_FORUMS}</div>
					</li>
					<!-- END: PLUGIN_SEARCH_FORM_FORUMS -->

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.L.Search}</button>
						</div>
					</li>

				</ul>

			</form>

			<!-- END: PLUGIN_SEARCH_FORM -->

			<!-- BEGIN: PLUGIN_SEARCH_PAGES -->

			<H4>{PHP.L.Pages}</H4>

			<div class="descr">{PHP.L.plu_found} {PLUGIN_SEARCH_PAGE_FOUND} {PHP.L.plu_match}</div>

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">

					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left">{PHP.L.Category}</div>
						<div class="table-th coltop text-left">{PHP.L.Page}</div>
						<div class="table-th coltop text-left" style="width:150px;">{PHP.L.Date}</div>
						<div class="table-th coltop text-left" style="width:100px;">{PHP.L.Owner}</div>
					</div>

				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: PLUGIN_SEARCH_PAGES_ROW -->

					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td pl-search-category">
							<a href="{PLUGIN_SEARCH_ROW_PAGE_CATEGORY_URL}">{PLUGIN_SEARCH_ROW_PAGE_CATEGORY_TITLE}</a>
						</div>
						<div class="table-td text-left resp-table-td pl-search-page">
							<a href="{PLUGIN_SEARCH_ROW_PAGE_URL}"><strong>{PLUGIN_SEARCH_ROW_PAGE_TITLE}</strong></a>
						</div>
						<div class="table-td text-left resp-table-td pl-search-date">
							{PLUGIN_SEARCH_ROW_PAGE_DATE}
						</div>
						<div class="table-td text-left resp-table-td pl-search-owner">
							{PLUGIN_SEARCH_ROW_PAGE_OWNER}
						</div>

					</div>

					<!-- END: PLUGIN_SEARCH_PAGES_ROW -->

				</div>

			</div>

			<!-- END: PLUGIN_SEARCH_PAGES -->


			<!-- BEGIN: PLUGIN_SEARCH_FORUMS -->

			<H4>{PHP.L.Forums}</H4>

			<div class="descr">{PHP.L.plu_found} {PLUGIN_SEARCH_FORUM_FOUND} {PHP.L.plu_match}</div>

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">

					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left">{PHP.L.Section}</div>
						<div class="table-th coltop text-left">{PHP.L.Topic}</div>
						<div class="table-th coltop text-left" style="width:150px;">{PHP.L.Date}</div>
						<div class="table-th coltop text-left" style="width:100px;">{PHP.L.Poster}</div>
					</div>

				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: PLUGIN_SEARCH_FORUMS_ROW -->

					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td pl-search-category">
							{PLUGIN_SEARCH_ROW_FORUM_SECTION}
						</div>
						<div class="table-td text-left resp-table-td pl-search-page">
							<a href="{PLUGIN_SEARCH_ROW_FORUM_TOPIC_URL}"><strong>{PLUGIN_SEARCH_ROW_FORUM_TOPIC_TITLE}</strong></a>
						</div>
						<div class="table-td text-left resp-table-td pl-search-date">
							{PLUGIN_SEARCH_ROW_FORUM_DATE}
						</div>
						<div class="table-td text-left resp-table-td pl-search-owner">
							{PLUGIN_SEARCH_ROW_FORUM_POSTER}
						</div>

					</div>

					<!-- END: PLUGIN_SEARCH_FORUMS_ROW -->

				</div>

			</div>

			<!-- END: PLUGIN_SEARCH_FORUMS -->

		</div>

	</div>

</main>

<!-- END: MAIN -->