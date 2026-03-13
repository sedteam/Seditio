<!-- BEGIN: MAIN -->

<main id="list">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1 data-category="{LIST_ID}">{LIST_SHORTTITLE}</h1>

		</div>

		<div class="section-desc">
			{LIST_CATDESC}
		</div>

		<!-- BEGIN: LIST_AUTHUSER -->

		<div class="section-admin">
			{LIST_SUBMITNEWPAGE}
		</div>

		<!-- END: LIST_AUTHUSER -->

		<div class="section-menu">
			<div class="inline-menu">
				<ul>
					<!-- BEGIN: LIST_ROWCAT -->
					<li><a href="{LIST_ROWCAT_URL}" data-category="{LIST_ROWCAT_ID}">{LIST_ROWCAT_TITLE} <span>{LIST_ROWCAT_COUNT}</span></a></li>
					<!-- END: LIST_ROWCAT -->
				</ul>
			</div>
		</div>

		<div id="primary-container">

			<div id="primary">

				<!-- BEGIN: LIST_CATTEXT -->

				<div class="page-text">

					{LIST_CATTEXT}

				</div>

				<!-- END: LIST_CATTEXT -->

				<div class="row row-flex">

					<!-- BEGIN: LIST_ROW -->

					<div class="col-xs-12 col-sm-6 col-md-4 post-col">

						<div class="post-item">
							<figure class="post-container">

								<!-- BEGIN: LIST_ROW_THUMB -->
								<a class="post-img-link" href="{LIST_ROW_URL}" data-page="{LIST_ROW_ID}">
									<picture>
										<source type="image/webp" srcset="{LIST_ROW_THUMB|crop_image(%s, 600, 500, 0, 1)}">
										<img class="post-img" src="{LIST_ROW_THUMB|crop_image(%s, 600, 500)}" alt="{LIST_ROW_TITLE}" />
									</picture>
								</a>
								<!-- END: LIST_ROW_THUMB -->

								<figcaption class="post-content">
									<div class="post-info">
										<div class="post-category"><a href="{LIST_ROW_CATURL}" data-category="{LIST_ROW_ID}">{LIST_ROW_CATTITLE}</a></div>
										<div class="post-date">{LIST_ROW_DATE}</div>
									</div>
									<div class="post-title">
										<h3><a href="{LIST_ROW_URL}" data-page="{LIST_ROW_ID}" data-cat="{LIST_ROW_CAT}">{LIST_ROW_TITLE}</a></h3>
									</div>
									<div class="post-desc">
										<p>{LIST_ROW_DESC}</p>
									</div>
									<div class="post-info">
										<div class="post-author">{LIST_ROW_OWNER_AVATAR}{LIST_ROW_OWNER}</div>
										<div class="post-comments"><i class="ic-message-circle"></i><a href="{LIST_ROW_COMURL}">{LIST_ROW_COMCOUNT}</a></div>
										<!-- BEGIN: LIST_ROW_FILE -->
										<div class="post-file"><i class="ic-document-file-{LIST_ROW_FILEICON}"></i></div>
										<!-- END: LIST_ROW_FILE -->
									</div>
								</figcaption>
							</figure>
						</div>

					</div>

					<!-- END: LIST_ROW -->

				</div>

				<!-- BEGIN: LIST_PAGINATION_BM -->

				<div class="pagination-box">

					<ul class="pagination">
						<li class="page-item">{LIST_TOP_PAGEPREV}</li>
						{LIST_TOP_PAGINATION}
						<li class="page-item">{LIST_TOP_PAGENEXT}</li>
					</ul>

				</div>

				<!-- END: LIST_PAGINATION_BM -->

			</div>

			<aside id="sidebar">

				<div class="sidebar-box">

					<div class="sidebar-title">
						<h3>{PHP.skinlang.index.Activity}</h3>
					</div>

					<div class="sidebar-menu">
						<div class="inline-menu">
							<ul class="tabs-nav">
								<li class="active"><a href="#tab-1">{PHP.skinlang.index.Lastcomments}</a></li>
								<li><a href="#tab-2">{PHP.skinlang.index.Lasttopics}</a></li>
								<li><a href="#tab-3">{PHP.skinlang.index.Lastpages}</a></li>
							</ul>
						</div>
					</div>

					<div id="tab-1" class="tab" style="display: block;">

						<!-- BEGIN: LATEST_COMMENTS -->

						<ul class="recent-items">

							<!-- BEGIN: LATEST_COMMENTS_ROW -->

							<li class="recent-item">
								<div class="recent-info">
									<div class="recent-author">{LATEST_COMMENTS_ROW_AVATAR}{LATEST_COMMENTS_ROW_AUTHORLINK}</div>
									<div class="recent-date">{LATEST_COMMENTS_ROW_DATE}</div>
								</div>
								<div class="recent-title">
									{LATEST_COMMENTS_ROW_LNK} {PHP.cfg.separator} {LATEST_COMMENTS_ROW_TEXT}
								</div>
							</li>

							<!-- END: LATEST_COMMENTS_ROW -->

						</ul>

						<!-- END: LATEST_COMMENTS -->

					</div>

					<div id="tab-2" class="tab" style="display: none;">

						<!-- BEGIN: LATEST_TOPICS -->

						<ul class="recent-items">

							<!-- BEGIN: LATEST_TOPICS_ROW -->

							<li class="recent-item">
								<div class="recent-info">
									<div class="recent-author"><a href="{LATEST_TOPICS_ROW_USERURL}">{LATEST_TOPICS_ROW_AVATAR}<span>{LATEST_TOPICS_ROW_AUTHOR}</span></a></div>
									<div class="recent-date">{LATEST_TOPICS_ROW_DATE}</div>
								</div>
								<div class="recent-title">
									{LATEST_TOPICS_ROW_FORUMPATH} {PHP.cfg.separator} <a href="{LATEST_TOPICS_ROW_URL}">{LATEST_TOPICS_ROW_SHORTTITLE}</a>
									<span class="recent-comments"><i class="ic-message-circle"></i><a href="{LATEST_TOPICS_ROW_URL}">{LATEST_TOPICS_ROW_TOPIC_COUNT}</a></span>
								</div>
							</li>

							<!-- END: LATEST_TOPICS_ROW -->

						</ul>

						<!-- END: LATEST_TOPICS -->

					</div>

					<div id="tab-3" class="tab" style="display: none;">

						<!-- BEGIN: LATEST_PAGES -->

						<ul class="recent-items">

							<!-- BEGIN: LATEST_PAGES_ROW -->

							<li class="recent-item">
								<div class="recent-info">
									<div class="recent-author"><a href="{LATEST_PAGES_ROW_USERURL}">{LATEST_PAGES_ROW_AVATAR}<span>{LATEST_PAGES_ROW_AUTHOR}</span></a></div>
									<div class="recent-date">{LATEST_PAGES_ROW_DATE}</div>
								</div>
								<div class="recent-title">
									{LATEST_PAGES_ROW_CATPATH} {PHP.cfg.separator} <a href="{LATEST_PAGES_ROW_URL}">{LATEST_PAGES_ROW_SHORTTITLE}</a>
									<span class="recent-comments"><i class="ic-message-circle"></i><a href="{LATEST_PAGES_ROW_COMMENTS_URL}">{LATEST_PAGES_ROW_COMMENTS_COUNT}</a></span>
								</div>
							</li>

							<!-- END: LATEST_PAGES_ROW -->

						</ul>

						<!-- END: LATEST_PAGES -->

					</div>

				</div>

				<div class="sidebar-box">

					<div class="sidebar-title">
						<h3>{PHP.skinlang.index.Polls}</h3>
					</div>

					<div class="sidebar-body">

						{PLUGIN_LATESTPOLL}

					</div>

				</div>

				<div class="sidebar-box">

					<div class="sidebar-title">
						<h3>{PHP.skinlang.index.Online}</h3>
					</div>

					<div class="sidebar-body">

						<a href="{PHP.out.whosonline_link}">{PHP.out.whosonline}</a> : {PHP.out.whosonline_reg_list}

					</div>

				</div>

			</aside>

		</div>

	</div>

</main>

<!-- END: MAIN -->