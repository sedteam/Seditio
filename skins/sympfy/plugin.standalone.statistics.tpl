<!-- BEGIN: MAIN -->

<main id="plugins">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PLUGIN_STATISTICS_TITLE}</h1>

		</div>

		<div class="section-body">

			<div class="table cells striped">

				<div class="table-body">

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_maxwasreached} {PLUGIN_STATISTICS_MAX_DATE}, {PLUGIN_STATISTICS_MAX_HITS} {PHP.L.plu_pagesdisplayedthisday}</div>
						<div class="table-td text-center" style="width:100px;"></div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_totalpagessince} {PLUGIN_STATISTICS_SINCE}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALPAGES}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_registeredusers}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALDBUSERS}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.Pages}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALDBPAGES}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.Comments}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALDBCOMMENTS}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_totalmails}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALMAILSENT}</div>
					</div>

				</div>

			</div>

			<h4>{PHP.L.Private_Messages}:</h4>

			<div class="table cells striped">

				<div class="table-body">

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_totalpms}</div>
						<div class="table-td text-center" style="width:100px;">{PLUGIN_STATISTICS_TOTALPMSENT}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_totalactivepms}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALPMACTIVE}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_totalarchivedpms}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALPMARCHIVED}</div>
					</div>

				</div>

			</div>

			<h4>{PHP.L.Forums}:</h4>

			<div class="table cells striped">

				<div class="table-body">

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_viewsforums}</div>
						<div class="table-td text-center" style="width:100px;">{PLUGIN_STATISTICS_TOTALDBVIEWS}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_postsforums} ({PHP.L.plu_pruned})</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALDBPOSTS_AND_TOTALDBPOSTSPRUNED} ({PLUGIN_STATISTICS_TOTALDBPOSTSPRUNED})</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_topicsforums} ({PHP.L.plu_pruned})</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALDBTOPICS_AND_TOTALDBTOPICSPRUNED} ({PLUGIN_STATISTICS_TOTALDBTOPICSPRUNED})</div>
					</div>

				</div>

			</div>

			<h4>{PHP.L.plu_pollsratings}:</h4>

			<div class="table cells striped">

				<div class="table-body">

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_pagesrated}</div>
						<div class="table-td text-center" style="width:100px;">{PLUGIN_STATISTICS_TOTALDBRATINGS}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_votesratings}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALDBRATINGSVOTES}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_polls}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALDBPOLLS}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_votespolls}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALDBPOLLSVOTES}</div>
					</div>

				</div>

			</div>

			<h4>{PHP.L.PFS}:</h4>

			<div class="table cells striped">

				<div class="table-body">

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_pfsspace}</div>
						<div class="table-td text-center" style="width:100px;">{PLUGIN_STATISTICS_TOTALDBFILES}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.plu_pfssize}, {PHP.L.kb}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALDBFILESIZE}</div>
					</div>

				</div>

			</div>

			<h4>{PHP.L.plu_contributions}:</h4>

			<div class="table cells striped">

				<div class="table-body">

					<!-- BEGIN: PLUGIN_STATISTICS_IS_USER -->
					<div class="table-row">
						<div class="table-td">{PHP.L.Posts}</div>
						<div class="table-td text-center" style="width:100px;">{PLUGIN_STATISTICS_USER_POSTSCOUNT}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.Topics}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_USER_TOPICSCOUNT}</div>
					</div>

					<div class="table-row">
						<div class="table-td">{PHP.L.Comments}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_USER_COMMENTS}</div>
					</div>
					<!-- END: PLUGIN_STATISTICS_IS_USER -->

					<!-- BEGIN: PLUGIN_STATISTICS_IS_NOT_USER -->
					<div class="table-row">
						<div class="table-td">{PHP.L.plu_notloggedin}</div>
						<div class="table-td"></div>
					</div>
					<!-- END: PLUGIN_STATISTICS_IS_NOT_USER -->

				</div>

			</div>

			<h4>{PHP.L.plu_membersbycountry}:</h4>

			<div class="table cells striped">

				<div class="table-head">

					<div class="table-row">
						<div class="table-th coltop text-center" style="width:50px;">{PHP.L.plu_flag}</div>
						<div class="table-th coltop text-left"><a href="{PLUGIN_STATISTICS_PLU_URL}">{PHP.out.ic_down}</a> {PHP.L.Country}</div>
						<div class="table-th coltop text-center" style="width:100px;"><a href="{PLUGIN_STATISTICS_SORT_BY_USERCOUNT}">{PHP.out.ic_down}</a> {PHP.L.Users}</div>
					</div>

				</div>

				<div class="table-body">

					<!-- BEGIN: PLUGIN_STATISTICS_ROW_COUNTRY -->
					<div class="table-row">
						<div class="table-td">{PLUGIN_STATISTICS_COUNTRY_FLAG}</div>
						<div class="table-td">{PLUGIN_STATISTICS_COUNTRY_NAME}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_COUNTRY_COUNT}</div>
					</div>
					<!-- END: PLUGIN_STATISTICS_ROW_COUNTRY -->

					<div class="table-row">
						<div class="table-td"><img src="system/img/flags/f-00.gif" alt="" /></div>
						<div class="table-td">{PHP.L.plu_unknown}</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_UNKNOWN_COUNT}</div>
					</div>

					<div class="table-row">
						<div class="table-td"></div>
						<div class="table-td text-right">{PHP.L.Total}:</div>
						<div class="table-td text-center">{PLUGIN_STATISTICS_TOTALUSERS}</div>
					</div>

				</div>

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->