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

			<style>
			.search-results { margin-top: 1em; }
			.search-result-item { margin-bottom: 1.5em; padding-bottom: 1em; border-bottom: 1px solid #eee; }
			.search-result-item:last-child { border-bottom: 0; }
			.search-result-title { margin: 0 0 0.25em; font-size: 1.15em; font-weight: 600; }
			.search-result-title a { text-decoration: none; }
			.search-result-meta { font-size: 0.85em; color: #666; margin-bottom: 0.5em; }
			.search-result-snippet { line-height: 1.5; color: #444; }
			.search-result-snippet .word,
			.search-result-title .word { background: #fff3a0; font-weight: 600; padding: 0 2px; }
			</style>

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

			<div class="search-results">

				<!-- BEGIN: PLUGIN_SEARCH_PAGES_ROW -->

				<article class="search-result-item">
					<h3 class="search-result-title">
						<a href="{PLUGIN_SEARCH_ROW_PAGE_URL}">{PLUGIN_SEARCH_ROW_PAGE_TITLE}</a>
					</h3>
					<div class="search-result-meta">
						<a href="{PLUGIN_SEARCH_ROW_PAGE_CATEGORY_URL}">{PLUGIN_SEARCH_ROW_PAGE_CATEGORY_TITLE}</a>
					</div>
					<div class="search-result-snippet">{PLUGIN_SEARCH_ROW_PAGE_SNIPPET}</div>
				</article>

				<!-- END: PLUGIN_SEARCH_PAGES_ROW -->

			</div>

			<!-- END: PLUGIN_SEARCH_PAGES -->


			<!-- BEGIN: PLUGIN_SEARCH_FORUMS -->

			<H4>{PHP.L.Forums}</H4>

			<div class="descr">{PHP.L.plu_found} {PLUGIN_SEARCH_FORUM_FOUND} {PHP.L.plu_match}</div>

			<div class="search-results">

				<!-- BEGIN: PLUGIN_SEARCH_FORUMS_ROW -->

				<article class="search-result-item">
					<h3 class="search-result-title">
						<a href="{PLUGIN_SEARCH_ROW_FORUM_TOPIC_URL}">{PLUGIN_SEARCH_ROW_FORUM_TOPIC_TITLE}</a>
					</h3>
					<div class="search-result-meta">
						{PLUGIN_SEARCH_ROW_FORUM_SECTION}
					</div>
					<div class="search-result-snippet">{PLUGIN_SEARCH_ROW_FORUM_SNIPPET}</div>
				</article>

				<!-- END: PLUGIN_SEARCH_FORUMS_ROW -->

			</div>

			<!-- END: PLUGIN_SEARCH_FORUMS -->

		</div>

	</div>

</main>

<!-- END: MAIN -->