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
					{FORUMS_TOPICS_VIEWERS} {PHP.skinlang.forumstopics.Viewers} &nbsp; {FORUMS_TOPICS_JUMPBOX}
				</div>
			</div>

			<!-- BEGIN: FORUMS_SECTIONS -->

			<div class="table-cells forums-table forums-main-table">

				<div class="table-thead">
					<div class="table-td coltop">{PHP.skinlang.forumssections.Subforums}</div>
					<div class="table-td coltop">{PHP.skinlang.forumssections.Lastpost}</div>
					<div class="table-td coltop" style="width:90px;">{PHP.skinlang.forumssections.Topics}</div>
					<div class="table-td coltop" style="width:90px;">{PHP.skinlang.forumssections.Posts}</div>
				</div>

				<div class="table-tbody">

					<!-- BEGIN: FORUMS_SECTIONS_ROW -->

					<!-- BEGIN: FORUMS_SECTIONS_ROW_SECTION -->

					<div class="table-tr">

						<div class="table-td forums-main-info">

							<div class="table-cells table-cells-subtable">

								<div class="table-tr">

									<div class="table-td" style="width:32px;">
										<img src="{FORUMS_SECTIONS_ROW_ICON}" alt="" />
									</div>

									<div class="table-td">
										<h4><a href="{FORUMS_SECTIONS_ROW_URL}">{FORUMS_SECTIONS_ROW_TITLE}</a></h4>
										<div class="desc">{FORUMS_SECTIONS_ROW_DESC}</div>
									</div>

								</div>

							</div>

						</div>

						<div class="table-td">
							{FORUMS_SECTIONS_ROW_LASTPOST}<br />
							{FORUMS_SECTIONS_ROW_LASTPOSTDATE} {FORUMS_SECTIONS_ROW_LASTPOSTER}<br />
							{FORUMS_SECTIONS_ROW_TIMEAGO}
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

									<div class="table-td centerall" style="width:32px;">
										{FORUMS_TOPICS_ROW_ICON}
									</div>

									<div class="table-td">
										<strong><a href="{FORUMS_TOPICS_ROW_URL}">{FORUMS_TOPICS_ROW_TITLE}</a></strong>
										<div class="desc">{FORUMS_TOPICS_ROW_DESC} {FORUMS_TOPICS_ROW_PAGES}</div>
									</div>

									<div class="table-td forums-firstposter" style="width:160px;">
										{FORUMS_TOPICS_ROW_CREATIONDATE}<br />{FORUMS_TOPICS_ROW_FIRSTPOSTER}
									</div>

								</div>

							</div>

						</div>

						<div class="table-td {FORUMS_TOPICS_ROW_ODDEVEN} forums-lastpost">
							{FORUMS_TOPICS_ROW_UPDATED} {FORUMS_TOPICS_ROW_LASTPOSTER}<br class="brnomobile" />
							{FORUMS_TOPICS_ROW_TIMEAGO}
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
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts.gif" alt="" /> : {PHP.skinlang.forumstopics.Nonewposts}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new.gif" alt="" /> :{PHP.skinlang.forumstopics.Newposts}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_sticky.gif" alt="" /> : {PHP.skinlang.forumstopics.Sticky}</div>
				</div>

				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_hot.gif" alt="" /> : {PHP.skinlang.forumstopics.Nonewpostspopular}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_hot.gif" alt="" /> :{PHP.skinlang.forumstopics.Newpostspopular}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_sticky.gif" alt="" /> : {PHP.skinlang.forumstopics.Newpostssticky}</div>
				</div>

				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Locked}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Newpostslocked}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_sticky_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Announcment}</div>
				</div>

				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_moved.gif" alt="" /> : {PHP.skinlang.forumstopics.Movedoutofthissection}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_sticky_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Newannouncment}</div>
					<div class="table-td"></div>
				</div>

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->