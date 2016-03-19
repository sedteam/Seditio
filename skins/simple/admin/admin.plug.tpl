<!-- BEGIN: ADMIN_PLUG -->

<!-- BEGIN: PLUG_DETAILS -->

<div class="content-box">
	
	<div class="content-box-header">					
    	<h3>{PLUG_DETAILS_NAME}</h3>			
    	<div class="clear"></div>					
    </div> 
    
    <div class="content-box-content">

		<table class="cells striped">
			<tr>
				<td style="width:33%;">{PHP.L.Code}:</td>
				<td>{PLUG_DETAILS_CODE}</td>
			</tr>
			<tr>
				<td>{PHP.L.Description}:</td>
				<td>{PLUG_DETAILS_DESC}</td>
			</tr>
			<tr>
				<td>{PHP.L.Version}:</td>
				<td>{PLUG_DETAILS_VERSION}</td>
			</tr>
			<tr>
				<td>{PHP.L.Date}:</td>
				<td>{PLUG_DETAILS_DATE}</td>
			</tr>
			<tr>
				<td>{PHP.L.Configuration}:</td>
				<td>{PLUG_DETAILS_CONFIG}</td>
			</tr>
			<tr>
				<td>{PHP.L.Rights}:</td>
				<td><a href="{PLUG_DETAILS_RIGHTS_URL}"><i class="fa fa-lg fa-cog"></i></a></td>
			</tr>
			<tr>
				<td>{PHP.L.adm_defauth_guests}:</td>
				<td>{PLUG_DETAILS_DEFAUTH_GUESTS}</td>
			</tr>
			<tr>
				<td>{PHP.L.adm_deflock_guests}:</td>
				<td>{PLUG_DETAILS_DEFLOCK_GUESTS}</td>
			</tr>
			<tr>
				<td>{PHP.L.adm_defauth_members}:</td>
				<td>{PLUG_DETAILS_DEFAUTH_MEMBERS}</td>
			</tr>
			<tr>
				<td>{PHP.L.adm_deflock_members}:</td>
				<td>{PLUG_DETAILS_DEFLOCK_MEMBERS}</td>
			</tr>
			<tr>
				<td>{PHP.L.Author}:</td>
				<td>{PLUG_DETAILS_AUTHOR}</td>
			</tr>
			<tr>
				<td>{PHP.L.Copyright}:</td>
				<td>{PLUG_DETAILS_COPYRIGHT}</td>
			</tr>
			<tr>
				<td>{PHP.L.Notes}:</td>
				<td>{PLUG_DETAILS_NOTES}</td>
			</tr>
		</table>
    
	</div>

</div>
    
<div class="content-box">
		
	<div class="content-box-header">					
		<h3>{PHP.L.Options}</h3>			
		<div class="clear"></div>					
	</div> 

    <div class="content-box-content">

		<table class="cells striped">
			<tr>
				<td style="width:33%;"><a href="{PLUG_DETAILS_INSTALL_URL}" title="{PHP.L.adm_opt_installall}"><img src="system/img/admin/play.png" alt="" /> {PHP.L.adm_opt_installall}</a></td>
				<td>{PHP.L.adm_opt_installall_explain}</td>
			</tr>
			<tr>
				<td><a href="{PLUG_DETAILS_UNINSTALL_URL}" title="{PHP.L.adm_opt_uninstallall}"><img src="system/img/admin/stop.png" alt="" /> {PHP.L.adm_opt_uninstallall}</a></td>
				<td>{PHP.L.adm_opt_uninstallall_explain}</td>
			</tr>
			<tr>
				<td><a href="{PLUG_DETAILS_PAUSE_URL}" title="{PHP.L.adm_opt_pauseall}"><img src="system/img/admin/pause.png" alt="" /> {PHP.L.adm_opt_pauseall}</a></td>
				<td>{PHP.L.adm_opt_pauseall_explain}</td>
			</tr>
			<tr>
				<td><a href="{PLUG_DETAILS_UNPAUSE_URL}" title="{PHP.L.adm_opt_unpauseall}"><img src="system/img/admin/forward.png" alt="" /> {PHP.L.adm_opt_unpauseall}</a></td>
				<td>{PHP.L.adm_opt_unpauseall_explain}</td>
			</tr>
		</table>
    
	</div>

</div>
    
<div class="content-box">
	
	<div class="content-box-header">					
		<h3>{PHP.L.Parts}</h3>			
		<div class="clear"></div>					
	</div> 
    
    <div class="content-box-content">    

		<table class="cells striped">

			<thead>
			<tr>
				<th class="coltop" colspan="2">{PHP.L.adm_part}</th>
				<th class="coltop">{PHP.L.File}</th>
				<th class="coltop">{PHP.L.Hooks}</th>
				<th class="coltop">{PHP.L.Order}</th>
				<th class="coltop">{PHP.L.Status}</th>
				<th class="coltop">{PHP.L.Action}</th>
			</tr>
			</thead>

			<!-- BEGIN: PLUG_PARTS_LIST -->
	
			<!-- BEGIN: PLUG_PARTS_ERROR -->
		
			<tr>
				<td style="width:32px;">#{PARTS_LIST_NUMBER}</td>
				<td>-</td>
				<td>{PARTS_LIST_FILE}</td>				
				<td colspan="4">{PARTS_LIST_ERROR}</td>
			</tr>
		
			<!-- END: PLUG_PARTS_ERROR -->

			<!-- BEGIN: PLUG_PARTS -->
		
			<tr>
				<td style="width:32px;">#{PARTS_LIST_NUMBER}</td>
				<td>{PARTS_LIST_PART}</td>
				<td>{PARTS_LIST_FILE}.php</td>
				<td>{PARTS_LIST_HOOKS}</td>				
				<td style="text-align:center;">{PARTS_LIST_ORDER}</td>				
				<td style="text-align:center;">{PARTS_LIST_STATUS}</td>
				<td style="text-align:center;">{PARTS_LIST_ACTION}</td>
			</tr>
				
			<!-- END: PLUG_PARTS -->
				
			<!-- END: PLUG_PARTS_LIST -->

		</table>
    
	</div>

</div>
    
<div class="content-box">
	
	<div class="content-box-header">					
    	<h3>{PHP.L.Tags}</h3>			
    	<div class="clear"></div>					
    </div>
    
    <div class="content-box-content">     

		<table class="cells striped">
			
			<thead>
			<tr>
				<th class="coltop" colspan="2">{PHP.L.Part}</th>
				<th class="coltop">{PHP.L.Files} / {PHP.L.Tags}</th>
			</tr>
			</thead>
			
			<!-- BEGIN: TAGS_LIST -->
			
			<tr>
				<td style="width:32px;">#{TAGS_LIST_NUMBER}</td>
				<td>{TAGS_LIST_PART}</td>
				<td>{TAGS_LIST_BODY}</td>
			</tr>	
			
			<!-- END: TAGS_LIST -->
			
		</table>
    
    </div>

</div>	

<!-- END: PLUG_DETAILS -->

<!-- BEGIN: PLUG_LISTING-->

  <div class="content-box"><div class="content-box-header">					
  	<h3>{PHP.L.Plugins} ({PLUG_LISTING_COUNT})</h3>			
  	<div class="clear"></div>					
  </div>    
    
  <div class="content-box-content">  

	<table class="cells striped">
	
		<thead>
		<tr>
			<th class="coltop">{PHP.L.Plugins} {PHP.L.adm_clicktoedit}</th>
			<th class="coltop">{PHP.L.Code}</th>
			<th class="coltop">{PHP.L.Version}</th>
			<th class="coltop">{PHP.L.Status} ({PHP.L.Parts})</th>
			<th class="coltop">{PHP.L.Configuration}</th>
			<th class="coltop" style="width:50px;">{PHP.L.Rights}</th>
			<th class="coltop" style="width:50px;">{PHP.L.Open}</th>
		</tr>
		</thead>
	
	<!-- BEGIN: PLUG_LIST-->
	
	<!-- BEGIN: PLUG_LIST_ERROR-->

		<tr>
			<td>{PLUG_LIST_CODE}</td>
			<td colspan="7">{PLUG_LIST_ERROR}</td>
		</tr>

	<!-- END: PLUG_LIST_ERROR-->
	
		<tr>
			<td><a href="{PLUG_LIST_DETAILS_URL}"><span class="icon"><i class="fa fa-lg fa-{PLUG_LIST_CODE}"></i></span> {PLUG_LIST_NAME}</a></td>
			<td>{PLUG_LIST_CODE}</td>
			<td style="text-align:center;">{PLUG_LIST_VERSION}</td>
			<td style="text-align:center;">{PLUG_LIST_STATUS} {PLUG_LIST_PARTS_COUNT}</td>
			<td style="text-align:center;">
				
				<!-- BEGIN: PLUG_LIST_CONFIG-->
			
				<a href="{PLUG_LIST_CONFIG_URL}"><i class="fa fa-lg fa-cog"></i></a>
			
				<!-- END: PLUG_LIST_CONFIG-->
			
			</td>
			<td style="text-align:center;"><a href="{PLUG_LIST_RIGHTS_URL}"><i class="fa fa-lg fa-lock"></i></a></td>
			<td style="text-align:center;">
				
				<!-- BEGIN: PLUG_LIST_OPEN-->			
				
				<a href="{PLUG_LIST_OPEN_URL}"><i class="fa fa-arrow-right"></i></a>
			
				<!-- END: PLUG_LIST_OPEN-->
			
			</td>
		</tr>	
	
	<!-- END: PLUG_LIST-->

	</table>
  
  </div>
  
</div>
  
  
<div class="content-box">
	
	<div class="content-box-header">					
		<h3>{PHP.L.Hooks} ({HOOKS_COUNT})</h3>			
		<div class="clear"></div>					
	</div>    
    
	<div class="content-box-content">  

	<table class="cells striped">
	
	<thead>
		<tr>
			<th class="coltop">{PHP.L.Hooks}</th>
			<th class="coltop">{PHP.L.Plugin}</th>
			<th class="coltop" style="text-align:center;">{PHP.L.File}</th>
			<th class="coltop" style="text-align:center;">{PHP.L.Order}</th>
			<th class="coltop" style="text-align:center;">{PHP.L.Active}</th>
		</tr>
	</thead>

	<!-- BEGIN: HOOK_LIST -->
		
		<tr>
			<td>{HOOK_LIST_HOOK}</td>
			<td>{HOOK_LIST_PLUG_TITLE} ({HOOK_LIST_PLUG_CODE})</td>
			<td>{HOOK_LIST_PLUG_FILE}</td>
			<td style="text-align:center;">{HOOK_LIST_ORDER}</td>
			<td style="text-align:center;">{HOOK_LIST_STATUS}</td>
		</tr>
		
	<!-- END: HOOK_LIST -->

	</table>
  
	</div>
	
</div>

<!-- END: PLUG_LISTING-->

<!-- BEGIN: PLUG_UN_INSTALL-->

<div class="content-box">
	
	<div class="content-box-header">					
		<h3>{PHP.L.Install} / {PHP.L.Uninstall}</h3>			
		<div class="clear"></div>					
	</div>    
    
	<div class="content-box-content">  

		<p>{PLUG_UN_INSTALL_INFO}</p>
		<a href="{PLUG_UN_INSTALL_URL}" class="btn">Continue...</a>
		
	</div>
	
</div>

<!-- END: PLUG_UN_INSTALL-->


<!-- END: ADMIN_PLUG -->
