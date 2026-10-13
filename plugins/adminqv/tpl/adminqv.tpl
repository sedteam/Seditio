<!-- BEGIN: ADMIN_QV -->

<!-- KPI Metric Cards Grid -->
<div class="kpi-grid">

	<!-- BEGIN: ADMIN_QV_NEWUSERS -->
	<div class="kpi-card kpi-card-users">
		<div class="kpi-icon"><i class="ic-users"></i></div>
		<div class="kpi-info">
			<span class="kpi-value">{QV_NEWUSERS}</span>
			<span class="kpi-title">{PHP.L.plu_newusers}</span>
			<span class="kpi-subtitle">{PHP.L.plu_in_last_7_days}</span>
		</div>
		<div class="kpi-trend trend-{QV_NEWUSERS_TREND_DIR}">{QV_NEWUSERS_TREND_TEXT}</div>
		<a href="{QV_NEWUSERS_URL}" class="kpi-overlay-link"></a>
	</div>
	<!-- END: ADMIN_QV_NEWUSERS -->

	<!-- BEGIN: ADMIN_QV_NEWPAGES -->
	<div class="kpi-card kpi-card-pages">
		<div class="kpi-icon"><i class="ic-pages"></i></div>
		<div class="kpi-info">
			<span class="kpi-value">{QV_NEWPAGES}</span>
			<span class="kpi-title">{PHP.L.plu_newpages}</span>
			<span class="kpi-subtitle">{PHP.L.plu_in_last_7_days}</span>
		</div>
		<div class="kpi-trend trend-{QV_NEWPAGES_TREND_DIR}">{QV_NEWPAGES_TREND_TEXT}</div>
		<a href="{QV_NEWPAGES_URL}" class="kpi-overlay-link"></a>
	</div>
	<!-- END: ADMIN_QV_NEWPAGES -->

	<!-- BEGIN: ADMIN_QV_NEWONFORUMS -->
	<div class="kpi-card kpi-card-posts">
		<div class="kpi-icon"><i class="ic-forums"></i></div>
		<div class="kpi-info">
			<span class="kpi-value">{QV_NEWPOSTS}</span>
			<span class="kpi-title">{PHP.L.plu_newposts}</span>
			<span class="kpi-subtitle">{PHP.L.plu_in_last_7_days}</span>
		</div>
		<div class="kpi-trend trend-{QV_NEWPOSTS_TREND_DIR}">{QV_NEWPOSTS_TREND_TEXT}</div>
		<a href="{QV_NEWFORUMS_URL}" class="kpi-overlay-link"></a>
	</div>
	<!-- END: ADMIN_QV_NEWONFORUMS -->

	<!-- BEGIN: ADMIN_QV_NEWCOMMENTS -->
	<div class="kpi-card kpi-card-comments">
		<div class="kpi-icon"><i class="ic-comments"></i></div>
		<div class="kpi-info">
			<span class="kpi-value">{QV_NEWCOMMENTS}</span>
			<span class="kpi-title">{PHP.L.plu_newcomments}</span>
			<span class="kpi-subtitle">{PHP.L.plu_in_last_7_days}</span>
		</div>
		<div class="kpi-trend trend-{QV_NEWCOMMENTS_TREND_DIR}">{QV_NEWCOMMENTS_TREND_TEXT}</div>
		<a href="{QV_NEWCOMMENTS_URL}" class="kpi-overlay-link"></a>
	</div>
	<!-- END: ADMIN_QV_NEWCOMMENTS -->
</div>

<!-- Middle Section: Hits Analytics & Quick Actions -->
<div class="dashboard-middle-grid">

	<div class="dashboard-card analytics-box">
		<div class="card-header">
			<h3><i class="ic-hits"></i> <span id="hitsChartTitle">{PHP.L.plu_hits_days}</span></h3>
			<div class="card-header-right">
				<a href="{QV_HITS_URL}" class="card-header-btn" title="{PHP.L.Hits}"><i class="ic-external-link"></i></a>
				<select id="hitsPeriodSelect" class="card-header-select sed-select sed-select-sm">
					<option value="days" selected>{PHP.L.plu_period_days}</option>
					<option value="weeks">{PHP.L.plu_period_weeks}</option>
					<option value="months">{PHP.L.plu_period_months}</option>
					<option value="years">{PHP.L.plu_period_years}</option>
				</select>
			</div>
		</div>
		<div class="card-body">
			<div class="chart-container" style="position: relative; height: 375px; width: 100%;" id="seditioHitsChartContainer">
				<div id="seditioHitsChart" style="width: 100%; height: 100%;"></div>
			</div>
		</div>
	</div>

	<div class="dashboard-card quick-actions-box">
		<div class="card-header">
			<h3><i class="ic-wand"></i> {PHP.L.adm_quick_actions}</h3>
		</div>
		<div class="card-body padding-12-16">
			<ul class="quick-actions-list">
				<li><a href="{QV_QUICK_PAGEADD_URL}"><i class="ic-edit"></i> <span>{PHP.L.plu_quick_addpage}</span></a></li>
				<li><a href="{QV_QUICK_PAGEMGMT_URL}"><i class="ic-pages"></i> <span>{PHP.L.plu_quick_pagemgmt}</span></a></li>
				<li><a href="{QV_QUICK_MENU_URL}"><i class="ic-menu"></i> <span>{PHP.L.plu_quick_menumgmt}</span></a></li>
				<li><a href="{QV_QUICK_DIC_URL}"><i class="ic-dic"></i> <span>{PHP.L.plu_quick_dic}</span></a></li>
				<li><a href="{QV_QUICK_SKINEDEDIT_URL}"><i class="ic-brush"></i> <span>{PHP.L.plu_quick_skinedit}</span></a></li>
				<li><a href="{QV_QUICK_BANLIST_URL}"><i class="ic-banlist"></i> <span>{PHP.L.plu_quick_banlist}</span></a></li>
				<li><a href="{QV_QUICK_CACHE_URL}"><i class="ic-cache"></i> <span>{PHP.L.plu_quick_cache}</span></a></li>
			</ul>
		</div>
	</div>

</div>

<!-- BEGIN: ADMIN_QV_DB -->
<div class="dashboard-card db-stats-box">
	<div class="card-header">
		<h3><i class="ic-server"></i> {PHP.L.plu_db}</h3>
		<div class="card-header-right">
			<a href="{QV_OPTIMIZE_DB_URL}" class="btn-optimizedb">
				<i class="ic-refresh"></i> <span>{PHP.L.plu_db_optimize}</span>
			</a>
		</div>
	</div>
	<div class="card-body">
		<div class="table cells striped resp-table no-label">
			<div class="table-body resp-table-body">
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td">{PHP.L.plu_db_rows}:</div>
					<div class="table-td resp-table-td text-right"><strong>{QV_DB_ROWS}</strong></div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td">{PHP.L.plu_db_indexsize}:</div>
					<div class="table-td resp-table-td text-right"><strong>{QV_DB_INDEXSIZE}</strong></div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td">{PHP.L.plu_db_datassize}:</div>
					<div class="table-td resp-table-td text-right"><strong>{QV_DB_DATASSIZE}</strong></div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td">{PHP.L.plu_db_totalsize}:</div>
					<div class="table-td resp-table-td text-right"><strong>{QV_DB_TOTALSIZE}</strong></div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td">{PHP.L.plu_db_fragmented}:</div>
					<div class="table-td resp-table-td text-right"><strong>{QV_DB_TOTALFRAGMENTED}</strong></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END: ADMIN_QV_DB -->

<!-- END: ADMIN_QV -->