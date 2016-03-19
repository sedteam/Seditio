<!-- BEGIN: ADMIN_CACHE -->

<div class="content-box">
	<div class="content-box-header">					
		<h3>{PHP.L.adm_internalcache}</h3>									
		<div class="clear"></div>					
	</div>

	<div class="content-box-content"> 

		<p>
			<a href="{CACHE_REFRESH_URL}">{PHP.L.Refresh}</a> | 
			<a href="{CACHE_PURGE_URL}">{PHP.L.adm_purgeall}</a> | 
			<a href="{CACHE_SHOWALL_URL}">{PHP.L.adm_showall}</a>
		</p>

		<table class="cells striped">

			<thead>
				<th class="coltop">{PHP.L.Delete}</th>
				<th class="coltop">{PHP.L.Item}</th>
				<th class="coltop">{PHP.L.Expire}</th>
				<th class="coltop">{PHP.L.Size}</th>
				<th class="coltop">{PHP.L.Value}</th>
			</thead>

			<!-- BEGIN: CACHE_LIST -->
			<tr>
				<td style="text-align:center;"><a href="{CACHE_LIST_DELETE_URL}" class="btn btn-small"><i class="fa fa-trash"></i></a></td>
				<td>{CACHE_LIST_NAME}</td>
				<td style="text-align:right;">{CACHE_LIST_EXPIRE}</td>
				<td style="text-align:right;">{CACHE_LIST_SIZE}</td>
				<td>{CACHE_LIST_VALUE}</td>
			</tr>
			<!-- END: CACHE_LIST -->
			 
			<tr>
				<td colspan="3">&nbsp;</td>
				<td style="text-align:right;">{CACHE_SIZE}</td>
				<td>&nbsp;</td>
			</tr>

		</table>

	</div>
	
</div>	

<!-- END: ADMIN_CACHE -->
