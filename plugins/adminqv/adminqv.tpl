<!-- BEGIN: ADMIN_QV -->
		
<div class="row row-flex">
	
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	
		<div class="content-box">
		
			<div class="content-box-header">
				<h3>{PHP.L.plu_pastdays}</h3>
			</div>
			
			<div class="content-box-content content-table">			
	
				<table class="cells striped">
					<tbody>
						<!-- BEGIN: ADMIN_QV_NEWUSERS -->
						<tr>
							<td><a href="{QV_NEWUSERS_URL}">{PHP.L.plu_newusers}</a></td>
							<td style="text-align:center; width:20%;">{QV_NEWUSERS}</td>
						</tr>
						<!-- END: ADMIN_QV_NEWUSERS -->
						<!-- BEGIN: ADMIN_QV_NEWPAGES -->
						<tr>
							<td><a href="{QV_NEWPAGES_URL}">{PHP.L.plu_newpages}</a></td>
							<td style="text-align:center;">{QV_NEWPAGES}</td>
						</tr>
						<!-- END: ADMIN_QV_NEWPAGES -->
						<!-- BEGIN: ADMIN_QV_NEWONFORUMS -->
						<tr>
							<td><a href="{QV_NEWFORUMS_URL}">{PHP.L.plu_newtopics}</a></td>
							<td style="text-align:center;">{QV_NEWTOPICS}</td>
						</tr>
						<tr>
							<td><a href="{QV_NEWFORUMS_URL}">{PHP.L.plu_newposts}</a></td>
							<td style="text-align:center;">{QV_NEWPOSTS}</td>
						</tr>
						<!-- END: ADMIN_QV_NEWONFORUMS -->
						<!-- BEGIN: ADMIN_QV_NEWCOMMENTS -->
						<tr>
							<td><a href="{QV_NEWCOMMENTS_URL}">{PHP.L.plu_newcomments}</a></td>
							<td style="text-align:center;">{QV_NEWCOMMENTS}</td>
						</tr>
						<!-- END: ADMIN_QV_NEWCOMMENTS -->
						<!-- BEGIN: ADMIN_QV_NEWPM -->
						<tr>
							<td>{PHP.L.plu_newpms}</td>
							<td style="text-align:center;">{QV_NEWPMS}</td>
						</tr>
						<!-- END: ADMIN_QV_NEWPM -->
					</tbody>
				</table>
				
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

				<table class="cells striped">
					<tbody>
						<tr>
							<td>{PHP.L.plu_db_rows}</td>
							<td style="text-align:center; width:20%;">{QV_DB_ROWS}</td>
						</tr>
						<tr>
							<td>{PHP.L.plu_db_indexsize}</td>
							<td style="text-align:center;">{QV_DB_INDEXSIZE}</td>
						</tr>
						<tr>
							<td>{PHP.L.plu_db_datassize}</td>
							<td style="text-align:center;">{QV_DB_DATASSIZE}</td>
						</tr>
						<tr>
							<td>{PHP.L.plu_db_totalsize}</td>
							<td style="text-align:center;">{QV_DB_TOTALSIZE}</td>
						</tr>
						<tr>
							<td>{PHP.L.plu_db_fragmented}</td>
							<td style="text-align:center;">{QV_DB_TOTALFRAGMENTED}</td>
						</tr>						
					</tbody>
				</table>
				
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

		<table class="cells striped">	
		<!-- BEGIN: ADMIN_QV_HITS_DAYLIST -->	
			<tr>
				<td style="width:90px;">{QV_HITS_DAY}</td>
				<td style="text-align:right; width:138px;">{QV_HITS_COUNT} {PHP.L.Hits}</td>
				<td style="text-align:right; width:40px;">{QV_HITS_PERCENTBAR}%</td>
				<td>
					<div style="width:100%;"><div class="bar_back"><div class="bar_front" style="width:{QV_HITS_PERCENTBAR}%;"></div></div></div>
				</td>
			</tr>
		<!-- END: ADMIN_QV_HITS_DAYLIST -->			
		<tr>
			<td colspan="4"><a href="{QV_HITS_URL}">{PHP.L.More}</a></td>
		</tr>
		</table>
		<!-- END: ADMIN_QV_HITS -->
		
	</div>

</div>
					
<!-- END: ADMIN_QV -->