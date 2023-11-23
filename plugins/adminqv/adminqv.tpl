<!-- BEGIN: ADMIN_QV -->

<div class="row row-flex">

	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

		<div class="content-box">

			<div class="content-box-header">
				<h3>{PHP.L.plu_pastdays}</h3>
			</div>

			<div class="content-box-content content-table">

				<div class="table cells striped">

					<div class="table-body">

						<!-- BEGIN: ADMIN_QV_NEWUSERS -->
						<div class="table-row">
							<div class="table-td text-left">
								<a href="{QV_NEWUSERS_URL}">{PHP.L.plu_newusers}</a>
							</div>
							<div class="table-td text-center">
								{QV_NEWUSERS}
							</div>
						</div>
						<!-- END: ADMIN_QV_NEWUSERS -->

						<!-- BEGIN: ADMIN_QV_NEWPAGES -->
						<div class="table-row">
							<div class="table-td"><a href="{QV_NEWPAGES_URL}">{PHP.L.plu_newpages}</a></div>
							<div class="table-td text-center">{QV_NEWPAGES}</div>
						</div>
						<!-- END: ADMIN_QV_NEWPAGES -->

						<!-- BEGIN: ADMIN_QV_NEWONFORUMS -->
						<div class="table-row">
							<div class="table-td"><a href="{QV_NEWFORUMS_URL}">{PHP.L.plu_newtopics}</a></div>
							<div class="table-td text-center">{QV_NEWTOPICS}</div>
						</div>
						<div class="table-row">
							<div class="table-td"><a href="{QV_NEWFORUMS_URL}">{PHP.L.plu_newposts}</a></div>
							<div class="table-td text-center">{QV_NEWPOSTS}</div>
						</div>
						<!-- END: ADMIN_QV_NEWONFORUMS -->

						<!-- BEGIN: ADMIN_QV_NEWCOMMENTS -->
						<div class="table-row">
							<div class="table-td"><a href="{QV_NEWCOMMENTS_URL}">{PHP.L.plu_newcomments}</a></div>
							<div class="table-td text-center">{QV_NEWCOMMENTS}</div>
						</div>
						<!-- END: ADMIN_QV_NEWCOMMENTS -->

						<!-- BEGIN: ADMIN_QV_NEWPM -->
						<div class="table-row">
							<div class="table-td">{PHP.L.plu_newpms}</div>
							<div class="table-td text-center">{QV_NEWPMS}</div>
						</div>
						<!-- END: ADMIN_QV_NEWPM -->

					</div>

				</div>

			</div>

		</div>

	</div>
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

		<div class="content-box">

			<div class="content-box-header">
				<h3>{PHP.L.plu_db}</h3>
			</div>

			<div class="content-box-content content-table">

				<!-- BEGIN: ADMIN_QV_DB -->

				<div class="table cells striped">

					<div class="table-body">

						<div class="table-row">
							<div class="table-td">{PHP.L.plu_db_rows}</div>
							<div class="table-td text-center">{QV_DB_ROWS}</div>
						</div>

						<div class="table-row">
							<div class="table-td">{PHP.L.plu_db_indexsize}</div>
							<div class="table-td text-center">{QV_DB_INDEXSIZE}</div>
						</div>

						<div class="table-row">
							<div class="table-td">{PHP.L.plu_db_datassize}</div>
							<div class="table-td text-center">{QV_DB_DATASSIZE}</div>
						</div>

						<div class="table-row">
							<div class="table-td">{PHP.L.plu_db_totalsize}</div>
							<div class="table-td text-center">{QV_DB_TOTALSIZE}</div>
						</div>

						<div class="table-row">
							<div class="table-td">{PHP.L.plu_db_fragmented}</div>
							<div class="table-td text-center">{QV_DB_TOTALFRAGMENTED}</div>
						</div>

					</div>

				</div>

				<!-- END: ADMIN_QV_DB -->

			</div>

		</div>

	</div>

</div>

<!-- BEGIN: ADMIN_QV_HITS -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.plu_hitsmonth}</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-body resp-table-body">

				<!-- BEGIN: ADMIN_QV_HITS_DAYLIST -->
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td aqv-day" style="width:90px;">{QV_HITS_DAY}</div>
					<div class="table-td resp-table-td text-right aqv-count" style="width:138px;">{QV_HITS_COUNT} {PHP.L.Hits}</div>
					<div class="table-td resp-table-td text-right aqv-hits" style="width:40px;">{QV_HITS_PERCENTBAR}%</div>
					<div class="table-td resp-table-td aqv-graph">
						<div style="width:100%;">
							<div class="bar_back">
								<div class="bar_front" style="width:{QV_HITS_PERCENTBAR}%;"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- END: ADMIN_QV_HITS_DAYLIST -->

			</div>

		</div>

		<div class="table-btn text-center">
			<a href="{QV_HITS_URL}" class="btn">{PHP.L.More}</a>
		</div>

		<!-- END: ADMIN_QV_HITS -->

	</div>

</div>

<!-- END: ADMIN_QV -->