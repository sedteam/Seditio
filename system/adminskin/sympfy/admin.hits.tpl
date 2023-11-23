<!-- BEGIN: ADMIN_HITS -->

<div class="title">
	<span><i class="ic-referers"></i></span>
	<h2>{ADMIN_HITS_TITLE}</h2>
</div>

<div class="content-box">

	<div class="content-box-content">

		<!-- BEGIN: YEAR_OR_MONTH -->

		<h4>{PHP.v} :</h4>

		<div class="table cells striped resp-table">

			<div class="table-body resp-table-body">

				<!-- BEGIN: HITS_ROW -->
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td aqv-day" style="width:140px;">{HITS_ROW_DAY}</div>
					<div class="table-td resp-table-td text-right aqv-count" style="width:138px;">{HITS_ROW_HITS} {PHP.L.Hits}</div>
					<div class="table-td resp-table-td text-right aqv-hits" style="width:40px;">{HITS_ROW_PERCENTBAR}%</div>
					<div class="table-td resp-table-td aqv-graph">
						<div style="width:100%;">
							<div class="bar_back">
								<div class="bar_front" style="width:{HITS_ROW_PERCENTBAR}%;"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- END: HITS_ROW -->

			</div>

		</div>

		<!-- END: YEAR_OR_MONTH -->

		<!-- BEGIN: DEFAULT -->

		<p>{HITS_MAXHITS}</p>

		<h4>{PHP.L.adm_byyear} :</h4>

		<div class="table cells striped resp-table">

			<div class="table-body resp-table-body">

				<!-- BEGIN: HITS_YEAR_ROW -->
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td aqv-day" style="width:140px;"><a href="{HITS_YEAR_ROW_URL}">{HITS_YEAR_ROW_YEAR}</a></div>
					<div class="table-td resp-table-td text-right aqv-count" style="width:138px;">{HITS_YEAR_ROW_HITS} {PHP.L.Hits}</div>
					<div class="table-td resp-table-td text-right aqv-hits" style="width:40px;">{HITS_YEAR_ROW_PERCENTBAR}%</div>
					<div class="table-td resp-table-td aqv-graph">
						<div style="width:100%;">
							<div class="bar_back">
								<div class="bar_front" style="width:{HITS_YEAR_ROW_PERCENTBAR}%;"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- END: HITS_YEAR_ROW -->

			</div>

		</div>

		<h4>{PHP.L.adm_bymonth} :</h4>

		<div class="table cells striped resp-table">

			<div class="table-body resp-table-body">

				<!-- BEGIN: HITS_MONTH_ROW -->
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td aqv-day" style="width:140px;"><a href="{HITS_MONTH_ROW_URL}">{HITS_MONTH_ROW_MONTH}</a></div>
					<div class="table-td resp-table-td text-right aqv-count" style="width:138px;">{HITS_MONTH_ROW_HITS} {PHP.L.Hits}</div>
					<div class="table-td resp-table-td text-right aqv-hits" style="width:40px;">{HITS_MONTH_ROW_PERCENTBAR}%</div>
					<div class="table-td resp-table-td aqv-graph">
						<div style="width:100%;">
							<div class="bar_back">
								<div class="bar_front" style="width:{HITS_MONTH_ROW_PERCENTBAR}%;"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- END: HITS_MONTH_ROW -->

			</div>

		</div>

		<h4>{PHP.L.adm_byweek} :</h4>

		<div class="table cells striped resp-table">

			<div class="table-body resp-table-body">

				<!-- BEGIN: HITS_WEEK_ROW -->
				<div class="table-row resp-table-row">
					<div class="table-td resp-table-td aqv-day" style="width:140px;">{HITS_WEEK_ROW_WEEK}</div>
					<div class="table-td resp-table-td text-right aqv-count" style="width:138px;">{HITS_WEEK_ROW_HITS} {PHP.L.Hits}</div>
					<div class="table-td resp-table-td text-right aqv-hits" style="width:40px;">{HITS_WEEK_ROW_PERCENTBAR}%</div>
					<div class="table-td resp-table-td aqv-graph">
						<div style="width:100%;">
							<div class="bar_back">
								<div class="bar_front" style="width:{HITS_WEEK_ROW_PERCENTBAR}%;"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- END: HITS_WEEK_ROW -->

			</div>

		</div>

		<!-- END: DEFAULT -->

	</div>

</div>

<!-- END: ADMIN_HITS -->