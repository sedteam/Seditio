<!-- BEGIN: MAIN -->

<!-- BEGIN: SLIDER -->

<section id="slider-section">

	<div id="slider">

		<!-- BEGIN: SLIDER_ROW -->

		<div class="slide-item" style="background-image:url({SLIDER_ROW_THUMB|crop_image(%s, 1920, 1080)})">
			<div class="slide-body" data-cat="{SLIDER_ROW_CAT}" data-page="{SLIDER_ROW_ID}">
				<div class="slider-container">

					<div class="slider-content">
						<div class="slider-info">
							<div class="slider-category">{SLIDER_ROW_CATPATH}</div>
							<div class="slider-date">{SLIDER_ROW_DATE}</div>
						</div>
						<div class="slider-title">
							<h2><a href="{SLIDER_ROW_URL}">{SLIDER_ROW_TITLE}</a></h2>
						</div>
						<div class="slider-desc">
							<p>{SLIDER_ROW_DESC|strip_tags}</p>
						</div>
						<div class="slider-info">
							<div class="slider-author"><a href="{SLIDER_ROW_USERURL}">{SLIDER_ROW_AVATAR}<span>{SLIDER_ROW_AUTHOR}</span></a></div>
							<div class="slider-comments"><i class="ic-message-circle"></i><a href="{SLIDER_ROW_COMMENTS_URL}">{SLIDER_ROW_COMMENTS_COUNT}</a></div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<!-- END: SLIDER_ROW -->

	</div>
	<div class="home-slider-arrows">
		<button class="slick-down slick-arrow" aria-label="Down" type="button" style="display: block;">Down</button>
	</div>

</section>

<!-- END: SLIDER -->

<main id="home" class="{SLIDER_NOACTIVE}">

	<div class="container">

		<div class="section-title">
			<h2>News</h2>
		</div>

		<div class="section-menu">
			<div class="inline-menu">
				{PHP.sed_menu.4.childrens}
			</div>
		</div>

		<div id="primary-container">

			<div id="primary">

				{INDEX_NEWS}

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