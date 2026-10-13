<!-- BEGIN: ADMIN_HITS -->

<div class="title">
	<span><i class="ic-hits"></i></span>
	<h2>{ADMIN_HITS_TITLE}</h2>
</div>

<div class="content-box">
	<div class="content-box-header">
		<h3><i class="ic-hits"></i> {HITS_CHART_TITLE}</h3>
		<div class="card-header-right">
			<form id="hitsFilterForm" method="get" action="{HITS_FILTER_ACTION}" style="display: flex; gap: 10px; align-items: center; margin: 0;">
				<input type="hidden" name="m" value="hits" />

				<!-- Year Selectbox -->
				<select name="y" id="hitsYearSelect" class="card-header-select sed-select sed-select-sm">
					<option value="0">{PHP.L.adm_byyear}</option>
					<!-- BEGIN: HITS_YEAR_OPTION -->
					<option value="{YEAR_VAL}" {YEAR_SELECTED}>{YEAR_VAL}</option>
					<!-- END: HITS_YEAR_OPTION -->
				</select>

				<!-- Month Selectbox -->
				<select name="m_code" id="hitsMonthSelect" class="card-header-select sed-select sed-select-sm">
					<option value="0">{PHP.L.adm_bymonth}</option>
					<!-- BEGIN: HITS_MONTH_OPTION -->
					<option value="{MONTH_VAL}" {MONTH_SELECTED}>{MONTH_TITLE}</option>
					<!-- END: HITS_MONTH_OPTION -->
				</select>

				<!-- Week Selectbox -->
				<select name="w_code" id="hitsWeekSelect" class="card-header-select sed-select sed-select-sm">
					<option value="0">{PHP.L.adm_byweek}</option>
					<!-- BEGIN: HITS_WEEK_OPTION -->
					<option value="{WEEK_VAL}" {WEEK_SELECTED}>W{WEEK_VAL}</option>
					<!-- END: HITS_WEEK_OPTION -->
				</select>
			</form>
		</div>
	</div>
	<div class="content-box-content padding-16">
		<div class="chart-container" style="position: relative; height: 350px; width: 100%;">
			<div id="adminHitsChart" style="width: 100%; height: 100%;"></div>
		</div>
	</div>
</div>

<div class="content-box">

	<div class="content-box-content">

		<p style="margin-bottom: 20px; font-weight: 500; color: #4a5568;">{HITS_MAXHITS}</p>

		<div class="table cells striped resp-table">

			<div class="table-body resp-table-body">

				<!-- BEGIN: HITS_TABLE_ROW -->
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td aqv-day" style="width:160px;">
						<!-- IF {HITS_ROW_URL} -->
						<a href="{HITS_ROW_URL}" class="btn-link">{HITS_ROW_KEY}</a>
						<!-- ELSE -->
						{HITS_ROW_KEY}
						<!-- ENDIF -->
					</div>
					<div class="table-td resp-table-td text-right aqv-count" style="width:140px;">{HITS_ROW_HITS} {PHP.L.Hits}</div>
					<div class="table-td resp-table-td text-right aqv-hits" style="width:45px;">{HITS_ROW_PERCENTBAR}%</div>
					<div class="table-td resp-table-td aqv-graph">
						<div style="width:100%;">
							<div class="bar_back">
								<div class="bar_front" style="width:{HITS_ROW_PERCENTBAR}%;"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- END: HITS_TABLE_ROW -->

			</div>

		</div>

	</div>

</div>

<!-- END: ADMIN_HITS -->