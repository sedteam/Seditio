<!-- BEGIN: ADMIN_PFS -->

<!-- BEGIN: PFS_BUTTONS -->

	<ul class="shortcut-buttons-set">
		
		<li><a class="shortcut-button" href="{BUTTON_PFS_CONFIG_URL}"><span>
			<i class="fa fa-3x fa-cog"></i><br />
			{PHP.L.Configuration} <br />"{PHP.L.core_pfs}"
		</span></a></li>
		
	</ul>

	<div class="clear"></div>
	
<!-- END: PFS_BUTTONS -->

<div class="content-box">
	<div class="content-box-header">					
		<h3>{PHP.L.adm_allpfs}</h3>			
		<div class="clear"></div>					
	</div>    
	<div class="content-box-content">
		
		<table class="cells striped">
		
			<thead>
			<tr>
				<th class="coltop">{PHP.L.Edit}</th>
				<th class="coltop">{PHP.L.User}</th>
				<th class="coltop">{PHP.L.Files}</th>
			</tr>
			</thead>
			
			<!-- BEGIN: PFS_LIST -->
			<tr>
				<td style="text-align:center;">{PFS_LIST_EDIT}</td>
				<td>{PFS_LIST_USER}</td>
				<td style="text-align:center;">{PFS_LIST_COUNTFILES}</td>
			</tr>
			<!-- END: PFS_LIST -->
		
		</table>		 
	  
	</div>
</div>	   

<!-- END: ADMIN_PFS -->
