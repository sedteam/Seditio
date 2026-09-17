<!-- BEGIN: MAIN -->

<main id="forums">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{FORUMS_TOPICS_SHORTTITLE}</h1>

			<div class="section-desc">
				{FORUMS_TOPICS_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
					<a href="{FORUMS_TOPICS_NEWTOPICURL}" class="btn"><i class="ic-message"></i> {PHP.L.for_newtopic}</a>
					<a href="{FORUMS_TOPICS_NEWTOPICPOLLURL}" class="btn"><i class="ic-polls"></i> {PHP.L.polls_add}</a>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-5 col-lg-5 forums-topics-viewers">
					{FORUMS_TOPICS_VIEWERS} {PHP.L.forumstopics_viewers} &nbsp; {FORUMS_TOPICS_JUMPBOX}
				</div>
			</div>

			<!-- BEGIN: FORUMS_SECTIONS -->

			<div class="table-cells forums-table forums-main-table">

				<div class="table-thead">
					<div class="table-td coltop">{PHP.L.forumssections_subforums}</div>
					<div class="table-td coltop">{PHP.L.forumssections_lastpost}</div>
					<div class="table-td coltop" style="width:90px;">{PHP.L.forumssections_topics}</div>
					<div class="table-td coltop" style="width:90px;">{PHP.L.forumssections_posts}</div>
				</div>

				<div class="table-tbody">

					<!-- BEGIN: FORUMS_SECTIONS_ROW -->

					<!-- BEGIN: FORUMS_SECTIONS_ROW_SECTION -->

					<div class="table-tr">

						<div class="table-td forums-main-info">

							<div class="table-cells table-cells-subtable">

								<div class="table-tr">

									<div class="table-td" style="width:42px;">
										<img src="{FORUMS_SECTIONS_ROW_ICON}" alt="" />
									</div>

									<div class="table-td">
										<h4><a href="{FORUMS_SECTIONS_ROW_URL}">{FORUMS_SECTIONS_ROW_TITLE}</a></h4>
										<div class="desc">{FORUMS_SECTIONS_ROW_DESC}</div>

										<!-- BEGIN: FORUMS_SECTIONS_ROW_SUBFORUMS -->
										<div class="subforums">
											<ul class="subforums-list">
												<!-- BEGIN: FORUMS_SECTIONS_ROW_SUBFORUMS_LIST -->
												<li><a href="{FORUMS_SECTIONS_ROW_SUBFORUMS_URL}">{FORUMS_SECTIONS_ROW_SUBFORUMS_TITLE}</a></li>
												<!-- END: FORUMS_SECTIONS_ROW_SUBFORUMS_LIST -->
											</ul>
										</div>
										<!-- END: FORUMS_SECTIONS_ROW_SUBFORUMS -->

									</div>

								</div>

							</div>

						</div>

						<div class="table-td">
							<div class="forum-lastposter">
								{FORUMS_SECTIONS_ROW_LASTPOST}<br />
								{FORUMS_SECTIONS_ROW_LASTPOSTDATE} {FORUMS_SECTIONS_ROW_LASTPOSTER}
							</div>
							<div class="forum-timeago">
								{FORUMS_SECTIONS_ROW_TIMEAGO}
							</div>
						</div>

						<div class="table-td forums-topiccount">
							{FORUMS_SECTIONS_ROW_TOPICCOUNT_ALL}<br />
							<span class="desc">({FORUMS_SECTIONS_ROW_TOPICCOUNT})</span>
						</div>

						<div class="table-td forums-postcount">
							{FORUMS_SECTIONS_ROW_POSTCOUNT_ALL}<br />
							<span class="desc">({FORUMS_SECTIONS_ROW_POSTCOUNT})</span>
						</div>

					</div>

					<!-- END: FORUMS_SECTIONS_ROW_SECTION -->

					<!-- END: FORUMS_SECTIONS_ROW -->

				</div>
			</div>

			<!-- END: FORUMS_SECTIONS -->

			<!-- BEGIN: FORUMS_TOPICS_PAGINATION_TP -->

			<div class="pagination-box">

				<ul class="pagination">
					<li class="page-item">{FORUMS_TOPICS_PAGEPREV}</li>
					{FORUMS_TOPICS_PAGES}
					<li class="page-item">{FORUMS_TOPICS_PAGENEXT}</li>
				</ul>

			</div>

			<!-- END: FORUMS_TOPICS_PAGINATION_TP -->

			<div class="table-cells forums-table forums-topics-table">

				<div class="table-thead">

					<div class="table-td coltop">{FORUMS_TOPICS_TITLE_TOPICS} <div style="float:right; width:135px;">{FORUMS_TOPICS_TITLE_STARTED}</div>
					</div>
					<div class="table-td coltop" style="width:250px;">{FORUMS_TOPICS_TITLE_LASTPOST}</div>
					<div class="table-td coltop" style="width:90px;">{FORUMS_TOPICS_TITLE_POSTS}</div>
					<div class="table-td coltop" style="width:90px;">{FORUMS_TOPICS_TITLE_VIEWS}</div>

				</div>

				<div class="table-tbody">

					<!-- BEGIN: FORUMS_TOPICS_ROW -->

					<div class="table-tr">

						<div class="table-td forums-topics-info {FORUMS_TOPICS_ROW_ODDEVEN}">

							<div class="table-cells table-cells-subtable">

								<div class="table-tr">

									<div class="table-td centerall" style="width:42px;">
										{FORUMS_TOPICS_ROW_ICON}
									</div>

									<div class="table-td">
										<strong><a href="{FORUMS_TOPICS_ROW_URL}">{FORUMS_TOPICS_ROW_TITLE}</a></strong>
										<div class="desc">{FORUMS_TOPICS_ROW_DESC} {FORUMS_TOPICS_ROW_PAGES}</div>
										<div class="forums-topic-tags">{FORUMS_TOPICS_ROW_TAGS}</div>
									</div>

									<div class="table-td forums-firstposter" style="width:160px;">
										<div class="forum-lastposter">
											{FORUMS_TOPICS_ROW_CREATIONDATE}<br />
											{FORUMS_TOPICS_ROW_FIRSTPOSTER}
										</div>
									</div>

								</div>

							</div>

						</div>

						<div class="table-td {FORUMS_TOPICS_ROW_ODDEVEN} forums-lastpost">
							<div class="forum-lastposter">
								{FORUMS_TOPICS_ROW_UPDATED}
								{FORUMS_TOPICS_ROW_LASTPOSTER}
							</div>
							<div class="forum-timeago">{FORUMS_TOPICS_ROW_TIMEAGO}</div>
						</div>

						<div class="table-td {FORUMS_TOPICS_ROW_ODDEVEN} forums-postcount">
							{FORUMS_TOPICS_ROW_POSTCOUNT}
						</div>

						<div class="table-td {FORUMS_TOPICS_ROW_ODDEVEN} forums-viewcount">
							{FORUMS_TOPICS_ROW_VIEWCOUNT}
						</div>

					</div>

					<!-- END: FORUMS_TOPICS_ROW -->
				</div>

			</div>

			<!-- BEGIN: FORUMS_TOPICS_PAGINATION_BM -->

			<div class="pagination-box">

				<ul class="pagination">
					<li class="page-item">{FORUMS_TOPICS_PAGEPREV}</li>
					{FORUMS_TOPICS_PAGES}
					<li class="page-item">{FORUMS_TOPICS_PAGENEXT}</li>
				</ul>

			</div>

			<!-- END: FORUMS_TOPICS_PAGINATION_BM -->

			<div class="table">

				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts.gif" alt="" /> : {PHP.L.forumstopics_nonewposts}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new.gif" alt="" /> :{PHP.L.forumstopics_newposts}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_sticky.gif" alt="" /> : {PHP.L.forumstopics_sticky}</div>
				</div>

				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_hot.gif" alt="" /> : {PHP.L.forumstopics_nonewpostspopular}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_hot.gif" alt="" /> :{PHP.L.forumstopics_newpostspopular}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_sticky.gif" alt="" /> : {PHP.L.forumstopics_newpostssticky}</div>
				</div>

				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_locked.gif" alt="" /> : {PHP.L.forumstopics_locked}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_locked.gif" alt="" /> : {PHP.L.forumstopics_newpostslocked}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_sticky_locked.gif" alt="" /> : {PHP.L.forumstopics_announcment}</div>
				</div>

				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_moved.gif" alt="" /> : {PHP.L.forumstopics_movedoutofthissection}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_sticky_locked.gif" alt="" /> : {PHP.L.forumstopics_newannouncment}</div>
					<div class="table-td"></div>
				</div>

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->