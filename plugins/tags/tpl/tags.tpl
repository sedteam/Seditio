<!-- BEGIN: MAIN -->
<main id="plugins">

	<div class="container">
		<div class="section-title">
			{TAGS_BREADCRUMBS}
			<h1>{TAGS_PAGETITLE}</h1>
		</div>
		<div class="section-body">

			<div class="filters-tags">
				<div class="filters-tags-form">
					<form action="{TAGS_FORM_ACTION}" method="get">
						<input type="hidden" name="e" value="tags" />
						<div>
							{TAGS_FORM_INPUT}
							<small class="text-muted tags-form-hint">{TAGS_FORM_HINT}</small>
						</div>
						<div>{TAGS_FORM_AREA}</div>
						<div><button type="submit" class="btn btn-primary">{PHP.L.tags_search}</button></div>
					</form>
				</div>
			</div>

			<!-- BEGIN: TAGS_RESULTS -->
			<div class="tags-results-section">
				<h3>{TAGS_RESULTS_TITLE}</h3>
				<!-- BEGIN: TAGS_PAGINATION_TOP -->
				<div class="pagination-box"><ul class="pagination">{TAGS_PAGINATION}</ul></div>
				<!-- END: TAGS_PAGINATION_TOP -->

				<!-- BEGIN: TAGS_RESULT_PAGES -->
				<div class="tags-results-pages">
					<h4>{TAGS_RESULT_PAGES_TITLE}</h4>
					<div class="table cells striped resp-table">
						<div class="table-head resp-table-head">
							<div class="table-row resp-table-row">
								<div class="table-th coltop text-left">{PHP.L.Title}</div>
								<div class="table-th coltop text-left">{PHP.L.Category}</div>
								<div class="table-th coltop text-left">{PHP.L.Date}</div>
								<div class="table-th coltop text-left">{PHP.L.tags_tags}</div>
							</div>
						</div>
						<div class="table-body resp-table-body">
							<!-- BEGIN: TAGS_RESULT_PAGE_ROW -->
							<div class="table-row resp-table-row tags-result-item">
								<div class="table-td text-left resp-table-td" data-label="{PHP.L.Title}"><a href="{TAGS_RESULT_PAGE_URL}">{TAGS_RESULT_PAGE_TITLE}</a></div>
								<div class="table-td text-left resp-table-td" data-label="{PHP.L.Category}">{TAGS_RESULT_PAGE_CAT}</div>
								<div class="table-td text-left resp-table-td" data-label="{PHP.L.Date}">{TAGS_RESULT_PAGE_DATE}</div>
								<div class="table-td text-left resp-table-td" data-label="{PHP.L.tags_tags}">{TAGS_RESULT_PAGE_TAGS}</div>
							</div>
							<!-- END: TAGS_RESULT_PAGE_ROW -->
						</div>
					</div>
				</div>
				<!-- END: TAGS_RESULT_PAGES -->

				<!-- BEGIN: TAGS_RESULT_FORUMS -->
				<div class="tags-results-forums">
					<h4>{TAGS_RESULT_FORUMS_TITLE}</h4>
					<div class="table cells striped resp-table">
						<div class="table-head resp-table-head">
							<div class="table-row resp-table-row">
								<div class="table-th coltop text-left">{PHP.L.Title}</div>
								<div class="table-th coltop text-left">{PHP.L.Section}</div>
								<div class="table-th coltop text-left">{PHP.L.Date}</div>
								<div class="table-th coltop text-left">{PHP.L.Posts}</div>
								<div class="table-th coltop text-left">{PHP.L.tags_tags}</div>
							</div>
						</div>
						<div class="table-body resp-table-body">
							<!-- BEGIN: TAGS_RESULT_TOPIC_ROW -->
							<div class="table-row resp-table-row tags-result-item">
								<div class="table-td text-left resp-table-td" data-label="{PHP.L.Title}"><a href="{TAGS_RESULT_TOPIC_URL}">{TAGS_RESULT_TOPIC_TITLE}</a></div>
								<div class="table-td text-left resp-table-td" data-label="{PHP.L.Section}">{TAGS_RESULT_TOPIC_SECTION}</div>
								<div class="table-td text-left resp-table-td" data-label="{PHP.L.Date}">{TAGS_RESULT_TOPIC_DATE}</div>
								<div class="table-td text-left resp-table-td" data-label="{PHP.L.Posts}">{TAGS_RESULT_TOPIC_POSTS}</div>
								<div class="table-td text-left resp-table-td" data-label="{PHP.L.tags_tags}">{TAGS_RESULT_TOPIC_TAGS}</div>
							</div>
							<!-- END: TAGS_RESULT_TOPIC_ROW -->
						</div>
					</div>
				</div>
				<!-- END: TAGS_RESULT_FORUMS -->

				<!-- BEGIN: TAGS_NORESULTS -->
				<div class="alert alert-info">{TAGS_NORESULTS_BODY}</div>
				<!-- END: TAGS_NORESULTS -->
				<!-- BEGIN: TAGS_PAGINATION_BOTTOM -->
				<div class="pagination-box"><ul class="pagination">{TAGS_PAGINATION}</ul></div>
				<!-- END: TAGS_PAGINATION_BOTTOM -->

			</div>
			<!-- END: TAGS_RESULTS -->

			<!-- BEGIN: TAGS_CLOUD -->
			<div class="tags-cloud-section">
				<h4>{TAGS_CLOUD_TITLE}</h4>
				{TAGS_CLOUD_BODY}
			</div>
			<!-- END: TAGS_CLOUD -->

		</div>
	</div>

</main>
<!-- END: MAIN -->
