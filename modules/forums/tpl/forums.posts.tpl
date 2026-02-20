<!-- BEGIN: MAIN -->

<main id="forums">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{FORUMS_POSTS_SHORTTITLE}</h1>

			<div class="section-desc">
				{FORUMS_POSTS_TOPICDESC}
			</div>

			<div class="section-subtitle">
				{FORUMS_POSTS_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			{FORUMS_POLL}

			<!-- BEGIN: FORUMS_POSTS_TOPICPRIVATE -->

			<div class="error">
				{PHP.skinlang.forumspost.privatetopic}
			</div>

			<!-- END: FORUMS_POSTS_TOPICPRIVATE -->

			<!-- BEGIN: FORUMS_POSTS_PAGINATION_TP -->

			<div class="pagination-box">

				<ul class="pagination">
					<li class="page-item">{FORUMS_POSTS_PAGEPREV}</li>
					{FORUMS_POSTS_PAGES}
					<li class="page-item">{FORUMS_POSTS_PAGENEXT}</li>
				</ul>

			</div>

			<!-- END: FORUMS_POSTS_PAGINATION_TP -->

			<div class="table-cells forums-table forums-post-table">

				<div class="table-thead">
					<div class="table-td coltop" style="width:160px;">{PHP.skinlang.forumspost.Author}</div>
					<div class="table-td coltop">{PHP.skinlang.forumspost.Message}</div>
				</div>

				<div class="table-tbody">

					<!-- BEGIN: FORUMS_POSTS_ROW -->

					<div class="table-tr cattop-tr">

						<div class="table-td {FORUMS_POSTS_ROW_ODDEVEN} cattop forums-post-author">
							<h4>{FORUMS_POSTS_ROW_POSTERNAME}</h4>
						</div>

						<div class="table-td {FORUMS_POSTS_ROW_ODDEVEN} cattop text-right forums-post-date">
							#{FORUMS_POSTS_ROW_IDURL} {FORUMS_POSTS_ROW_CREATION} {FORUMS_POSTS_ROW_POSTERIP} {FORUMS_POSTS_ROW_ADMIN} {FORUMS_POSTS_ROW_RATE}
						</div>

					</div>

					<div class="table-tr">

						<div class="table-td {FORUMS_POSTS_ROW_ODDEVEN} td-top forums-post-infoposter-td">

							<div class="forums-post-infoposter">

								<div class="forums-post-avatar">
									{FORUMS_POSTS_ROW_AVATAR}
								</div>
								<div class="forums-post-userinfo">
									<p>
										{FORUMS_POSTS_ROW_MAINGRP}<br />
										{FORUMS_POSTS_ROW_COUNTRYFLAG} {FORUMS_POSTS_ROW_MAINGRPSTARS}<br />
										<img src="skins/{PHP.skin}/img/online{FORUMS_POSTS_ROW_USERONLINE}.gif" alt="{PHP.skinlang.forumspost.Onlinestatus}">
									</p>
									<p>
										{FORUMS_POSTS_ROW_POSTCOUNT} {PHP.skinlang.forumspost.posts}<br />
										{FORUMS_POSTS_ROW_WEBSITE}
									</p>
								</div>

							</div>


						</div>

						<div class="table-td {FORUMS_POSTS_ROW_ODDEVEN} td-top forums-message-td">
							<div class="forums-post-message forum-text">
								{FORUMS_POSTS_ROW_TEXT}
								<div class="signature">{FORUMS_POSTS_ROW_USERTEXT}</div>
							</div>
							{FORUMS_POSTS_ROW_UPDATEDBY}
						</div>

					</div>

					<!-- END: FORUMS_POSTS_ROW -->

				</div>

			</div>

			<!-- BEGIN: FORUMS_POSTS_PAGINATION_BM -->

			<div class="pagination-box">

				<ul class="pagination">
					<li class="page-item">{FORUMS_POSTS_PAGEPREV}</li>
					{FORUMS_POSTS_PAGES}
					<li class="page-item">{FORUMS_POSTS_PAGENEXT}</li>
				</ul>

			</div>

			<!-- END: FORUMS_POSTS_PAGINATION_BM -->

			<!-- BEGIN: FORUMS_POSTS_TOPICLOCKED -->

			<div class="error">
				{FORUMS_POSTS_TOPICLOCKED_BODY}
			</div>

			<!-- END: FORUMS_POSTS_TOPICLOCKED -->

			<!-- BEGIN: FORUMS_POSTS_ANTIBUMP -->

			<div>
				{FORUMS_POSTS_ANTIBUMP_BODY}
			</div>

			<!-- END: FORUMS_POSTS_ANTIBUMP -->

			<!-- BEGIN: FORUMS_POSTS_NEWPOST -->

			<!-- BEGIN: FORUMS_POSTS_NEWPOST_ERROR -->

			{FORUMS_POSTS_NEWPOST_ERROR_BODY}

			<!-- END: FORUMS_POSTS_NEWPOST_ERROR -->

			<form action="{FORUMS_POSTS_NEWPOST_SEND}" method="post" name="newpost">

				<ul class="form responsive-form">
					<li class="form-row">
						<div class="form-field-100">
							<h4>{PHP.L.for_quickpost}</h4>
							{FORUMS_POSTS_NEWPOST_TEXT}
						</div>
					</li>
					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn btn-big">{PHP.skinlang.forumspost.Reply}</button>
						</div>
					</li>
				</ul>

			</form>

			<!-- END: FORUMS_POSTS_NEWPOST -->

			<div class="jumpbox">

				{FORUMS_POSTS_JUMPBOX}

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->