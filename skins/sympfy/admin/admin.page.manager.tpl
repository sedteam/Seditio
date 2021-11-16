<!-- BEGIN: ADMIN_PAGE_MANAGER -->

<div class="content-box">
	
	<div class="content-box-header">
		<h3>{PHP.L.adm_pagemanager} ({PAGE_MANAGER_COUNT})</h3>
		<div class="content-box-header-right">
			{PHP.L.Category} {PAGE_MANAGER_CATEGORY}
			<div class="clear"></div>					
		</div>
	</div>

	<div class="content-box-content">	
			
		<!-- BEGIN: PAGE_PAGINATION_TP -->
		
		<div class="paging">
			<ul class="pagination">
			  <li class="prev">{PAGE_PAGEPREV}</li>
			  {PAGE_PAGINATION}
			  <li class="next">{PAGE_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: LOG_PAGINATION_TP -->

		<table class="cells striped">

		<thead>
		<tr>
			<th class="coltop">#ID</th>
			<th class="coltop">{PHP.L.Title}</th>
			<th class="coltop">{PHP.L.Date}</th>
			<th class="coltop">{PHP.L.Owner}</th>
			<th class="coltop">{PHP.L.Status}</th>
			<th class="coltop">{PHP.L.Action}</th>
		</tr>
		</thead>
		
		<!-- BEGIN: PAGE_LIST -->
		
		<tr>
			<td style="width:32px;">#{PAGE_ID}</td>
			<td><a href="{PAGE_URL}">{PAGE_TITLE}</a></td>
			<td style="width:80px; text-align:center;">{PAGE_DATE}</td>
			<td style="width:180px; text-align:center;"><i class="fa fa-user"></i> {PAGE_OWNER}</td>						
			<td style="width:30px; text-align:center;">
 				<!-- BEGIN: PAGE_VALIDATE -->
					<i class="fa fa-eye-slash" title="{PHP.L.Validate}"></i>  
				<!-- END: PAGE_VALIDATE -->

				<!-- BEGIN: PAGE_UNVALIDATE -->
					<i class="fa fa-eye" title="{PHP.L.Putinvalidationqueue}"></i>        
				<!-- END: PAGE_UNVALIDATE -->     
			</td>
			<td style="text-align:center; width:110px;">
			
			<!-- BEGIN: ADMIN_ACTIONS -->			
			
				<!-- BEGIN: PAGE_VALIDATE -->
					<a href="{PAGE_VALIDATE_URL}" title="{PHP.L.Validate}" class="btn btn-small"><i class="fa fa-eye-slash"></i></a>  
				<!-- END: PAGE_VALIDATE -->

				<!-- BEGIN: PAGE_UNVALIDATE -->
					<a href="{PAGE_UNVALIDATE_URL}" title="{PHP.L.Putinvalidationqueue}" class="btn btn-small"><i class="fa fa-eye"></i></a>        
				<!-- END: PAGE_UNVALIDATE --> 
		
				<a href="{PAGE_EDIT_URL}" title="{PHP.L.Edit}" class="btn btn-small"><i class="fa fa-pencil"></i></a> 
				<a href="{PAGE_CLONE_URL}" title="{PHP.L.Clone}" class="btn btn-small"><i class="fa fa-files-o"></i></a>
				<a href="{PAGE_DELETE_URL}" title="{PHP.L.Delete}" onclick="return sedjs.confirmact('Вы подтверждаете удаление?');" class="btn btn-small"><i class="fa fa-trash"></i></a>
			
			<!-- END: ADMIN_ACTIONS -->  
			
			</td>
		</tr>	
		
		<!-- END: PAGE_LIST -->
		
		</table>
		
		<!-- BEGIN: PAGE_PAGINATION_BM -->
		
		<div class="paging">
			<ul class="pagination">
			  <li class="prev">{PAGE_PAGEPREV}</li>
			  {PAGE_PAGINATION}
			  <li class="next">{PAGE_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: LOG_PAGINATION_BM -->
						
		<script>
		  function confirmDelete() {
			if (confirm("Вы подтверждаете удаление?")) {
				return true;
			} else {
				return false;
			}
		}
		</script>

	</div>
	
</div>		

<!-- END: ADMIN_PAGE_MANAGER -->
