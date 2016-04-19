<!-- BEGIN: ADMIN_TOOLS -->

<div class="content-box  column-left">
	
	<div class="content-box-header">					
		<h3>{PHP.L.Modules}</h3>			
		<div class="clear"></div>					
	</div> 
	   
    <div class="content-box-content">  
  
	<table class="cells striped">
	<thead>
		<tr>
			<th class="coltop">{PHP.L.Modules}</th>
			<th class="coltop" style="width:80px;">{PHP.L.Rights}</th>
			<th class="coltop" style="width:128px;">{PHP.L.Configuration}</th>
		</tr>
	</thead>

	<!-- BEGIN: MODULES_LIST -->
	<tr>
		<td>		
			<a href="{MODULES_LIST_URL}"><span class="icon"><i class="fa fa-lg fa-{MODULES_LIST_CODE}"></i></span> {MODULES_LIST_TITLE}</a>		
		</td>

		<td style="text-align:center;">
		
			<!-- BEGIN: MODULES_LIST_RIGHTS -->
			<a href="{MODULES_LIST_RIGHTS_URL}"><i class="fa fa-lg fa-lock"></i></a>
			<!-- END: MODULES_LIST_RIGHTS -->
		
		</td>

		<td style="text-align:center;">	
		
			<!-- BEGIN: MODULES_LIST_CONFIG -->
			<a href="{MODULES_LIST_CONFIG_URL}"><i class="fa fa-lg fa-cog"></i></a>
			<!-- END: MODULES_LIST_CONFIG -->
		
		</td>		
	</tr>
	<!-- END: MODULES_LIST -->
	
	<!-- BEGIN: MODULES_LIST_BANLIST -->
	<tr> 
		<td colspan="3"><a href="{MODULES_LIST_BANLIST_URL}"><span class="icon"><i class="fa fa-lg fa-ban"></i></span> {PHP.L.Banlist}</a></td> 
	</tr> 
	<!-- END: MODULES_LIST_BANLIST -->
	
	<!-- BEGIN: MODULES_LIST_CACHE -->
	<tr>
		<td colspan="3"><a href="{MODULES_LIST_CACHE_URL}"><span class="icon"><i class="fa fa-lg fa-cache"></i></span> {PHP.L.adm_internalcache}</a></td>
	</tr>
	<!-- END: MODULES_LIST_CACHE -->

	<!-- BEGIN: MODULES_LIST_SMILIES -->
	<tr>
		<td colspan="3"><a href="{MODULES_LIST_SMILIES_URL}"><span class="icon"><i class="fa fa-lg fa-smilies"></i></span> {PHP.L.Smilies}</a></td>
	</tr>
	<!-- END: MODULES_LIST_SMILIES -->

	<!-- BEGIN: MODULES_LIST_HITS -->
	<tr>
		<td colspan="3"><a href="{MODULES_LIST_HITS_URL}"><span class="icon"><i class="fa fa-lg fa-pie-chart"></i></span> {PHP.L.Hits}</a></td>
	</tr>
	<!-- END: MODULES_LIST_HITS -->

	<!-- BEGIN: MODULES_LIST_REFERERS -->
	<tr>
		<td colspan="3"><a href="{MODULES_LIST_REFERERS_URL}"><span class="icon"><i class="fa fa-lg fa-ship"></i></span> {PHP.L.Referers}</a></td>
	</tr>
	<!-- END: MODULES_LIST_REFERERS -->

	</table>

	</div>
	
</div>

<div class="content-box column-right">

	<div class="content-box-header">					
		<h3>{PHP.L.Tools}</h3>			
		<div class="clear"></div>					
	</div>    
    
	<div class="content-box-content">  

		<table class="cells striped">
		<thead><tr><th style="text-align:center;" class="coltop">{PHP.L.Tools} ({PHP.L.Plugins})</th>
		<th style="text-align:center;" class="coltop">{PHP.L.Configuration}</th></tr></thead>
		
			<!-- BEGIN: TOOLS_LIST -->
			<tr>
				<td><a href="{TOOLS_LIST_URL}"><span class="icon"><i class="fa fa-lg fa-plug"></i></span> {TOOLS_LIST_TITLE}</a></td>
				<td style="width:96px; text-align:center;">
				
				<!-- BEGIN: TOOLS_LIST_CONFIG -->
				<a href="{TOOLS_LIST_CONFIG_URL}"><i class="fa fa-lg fa-cog"></i></a>
				<!-- END: TOOLS_LIST_CONFIG -->
								
				</td>
			</tr>
			<!-- END: TOOLS_LIST -->

		</table>	
		
	</div>
	
</div>  

<!-- END: ADMIN_TOOLS -->

<!-- BEGIN: ADMIN_TOOL -->

<div class="content-box">
	
	<div class="content-box-header">					
		<h3><i class="fa fa-plug"></i> {TOOL_TITLE}</h3>			
		<div class="clear"></div>					
	</div> 
	   
    <div class="content-box-content">
		<!-- BEGIN: TOOL_BODY_LIST -->
		{TOOL_BODY}
		<!-- END: TOOL_BODY_LIST -->		
	</div>
	
</div>  

<!-- END: ADMIN_TOOL -->
