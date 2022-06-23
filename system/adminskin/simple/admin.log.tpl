<!-- BEGIN: ADMIN_LOG -->

<div class="content-box">
	<div class="content-box-header">					
		<h3>{PHP.L.adm_log}</h3>
		<div class="content-box-header-right">
			<form>{ADMIN_LOG_FILTER} {ADMIN_LOG_CLEAR}</form>
			<div class="clear"></div>					
		</div>
	</div>
	<div class="content-box-content">
  
		<!-- BEGIN: LOG_PAGINATION_TP -->
		
		<div class="paging">
			<ul class="pagination">
			  <li class="prev">{LOG_PAGEPREV}</li>
			  {LOG_PAGINATION}
			  <li class="next">{LOG_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: LOG_PAGINATION_TP -->

		<table class="cells striped">
			<thead>
				<tr>
					<th class="coltop">#</th>
					<th class="coltop" style="width:150px;">{PHP.L.Date} (GMT)</th>
					<th class="coltop">{PHP.L.Ip}</th>
					<th class="coltop">{PHP.L.User}</th>
					<th class="coltop">{PHP.L.Group}</th>
					<th class="coltop">{PHP.L.Log}</th>
				</tr>
			</thead>
			
			<!-- BEGIN: LOG_LIST -->
			
			<tr>
				<td>{LOG_LIST_ID}</td>
				<td>{LOG_LIST_DATE}</td>
				<td>{LOG_LIST_IP}</td>
				<td>{LOG_LIST_USER}</td>
				<td>{LOG_LIST_GROUP}</td>
				<td class="desc"><div style="word-break: break-all;">{LOG_LIST_DESC}</div></td>
			</tr>			
			
			<!-- END: LOG_LIST -->
			
		</table>
		
		<!-- BEGIN: LOG_PAGINATION_BM -->
		
		<div class="paging">
			<ul class="pagination">
			  <li class="prev">{LOG_PAGEPREV}</li>
			  {LOG_PAGINATION}
			  <li class="next">{LOG_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: LOG_PAGINATION_BM -->
					
	</div>
</div>	

<!-- END: ADMIN_LOG -->
