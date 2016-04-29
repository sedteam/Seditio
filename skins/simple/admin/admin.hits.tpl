<!-- BEGIN: ADMIN_HITS -->

<div class="content-box">
	<div class="content-box-header">					
		<h3>{PHP.L.Hits}</h3>									
		<div class="clear"></div>					
	</div>

	<div class="content-box-content"> 

		<!-- BEGIN: YEAR_OR_MONTH -->

		<h4>{PHP.v} :</h4>

		<table class="cells striped end">
			
			<!-- BEGIN: HITS_ROW -->
			
			<tr>
				<td style="width:128px; text-align:center; padding:1px;">{HITS_ROW_DAY}</td>
				<td style="text-align:right; width:150px; padding:1px;">{HITS_ROW_HITS} {PHP.L.Hits}</td>
				<td style="text-align:right; width:40px; padding:1px;">{HITS_ROW_PERCENTBAR}%</td>
				<td>
					<div style="width:320px;">
						<div class="bar_back">
							<div class="bar_front" style="width:{HITS_ROW_PERCENTBAR}%;"></div>
						</div>
					</div>
				</td>
			</tr>
			
			<!-- END: HITS_ROW -->

		</table>

		<!-- END: YEAR_OR_MONTH -->


		<!-- BEGIN: DEFAULT -->

		<p>{HITS_MAXHITS}</p>

		<h4>{PHP.L.adm_byyear} :</h4>

		<table class="cells striped end">

			<!-- BEGIN: HITS_YEAR_ROW -->
			
			<tr>
				<td style="width:80px;text-align:center; padding:1px;"><a href="{HITS_YEAR_ROW_URL}">{HITS_YEAR_ROW_YEAR}</a></td>
				<td style="text-align:right; width:150px; padding:1px;">{HITS_YEAR_ROW_HITS} {PHP.L.Hits}</td>
				<td style="text-align:right; width:40px; padding:1px;">{HITS_YEAR_ROW_PERCENTBAR}%</td>
				<td>
					<div style="width:320px;">
						<div class="bar_back">
							<div class="bar_front" style="width:{HITS_YEAR_ROW_PERCENTBAR}%;"></div>
						</div>
					</div>
				</td>
			</tr>
			
			<!-- END: HITS_YEAR_ROW -->

		</table>

		<h4>{PHP.L.adm_bymonth} :</h4>

		<table class="cells striped end">

			<!-- BEGIN: HITS_MONTH_ROW -->
			
			<tr>
				<td style="width:80px;text-align:center; padding:1px;"><a href="{HITS_MONTH_ROW_URL}">{HITS_MONTH_ROW_MONTH}</a></td>
				<td style="text-align:right; width:150px; padding:1px;">{HITS_MONTH_ROW_HITS} {PHP.L.Hits}</td>
				<td style="text-align:right; width:40px; padding:1px;">{HITS_MONTH_ROW_PERCENTBAR}%</td>
				<td>
					<div style="width:320px;">
						<div class="bar_back">
							<div class="bar_front" style="width:{HITS_MONTH_ROW_PERCENTBAR}%;"></div>
						</div>
					</div>
				</td>
			</tr>
			
			<!-- END: HITS_MONTH_ROW -->
			
		</table>

		<h4>{PHP.L.adm_byweek} :</h4>

		<table class="cells striped end">

			<!-- BEGIN: HITS_WEEK_ROW -->
			
			<tr>
				<td style="width:80px;text-align:center; padding:1px;">{HITS_WEEK_ROW_WEEK}</td>
				<td style="text-align:right; width:150px; padding:1px;">{HITS_WEEK_ROW_HITS} {PHP.L.Hits}</td>
				<td style="text-align:right; width:40px; padding:1px;">{HITS_WEEK_ROW_PERCENTBAR}%</td>
				<td>
					<div style="width:320px;">
						<div class="bar_back">
							<div class="bar_front" style="width:{HITS_WEEK_ROW_PERCENTBAR}%;"></div>
						</div>
					</div>
				</td>
			</tr>
			
			<!-- END: HITS_WEEK_ROW -->
			
		</table>
				
		<!-- END: DEFAULT -->

	</div>
</div>	
	
<!-- END: ADMIN_HITS -->
