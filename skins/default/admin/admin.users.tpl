<!-- BEGIN: ADMIN_USERS -->

<!-- BEGIN: USERS_BUTTONS -->

	<ul class="shortcut-buttons-set">
		
		<li><a class="shortcut-button" href="{BUTTON_USERS_CONFIG_URL}"><span>
			<i class="fa fa-3x fa-cog"></i><br />
			{PHP.L.Configuration} <br />"{PHP.L.Users}"
		</span></a></li>
		
		<li><a class="shortcut-button" href="{BUTTON_USERS_BANLIST_URL}"><span>
			<i class="fa fa-3x fa-ban"></i><br />
			{PHP.L.Banlist}
		</span></a></li>
		
	</ul>

	<div class="clear"></div>
	
<!-- END: USERS_BUTTONS -->

<!-- BEGIN: USERS_EDIT -->
  <div class="content-box"><div class="content-box-header">					
  	<h3>{PHP.L.editdeleteentries}</h3>			
  	<div class="clear"></div>					
  </div>    
    
  <div class="content-box-content">  

	<form id="editlevel" action="{USERS_EDIT_SEND}" method="post">
	<table class="cells striped">
		<tr>
			<td>{PHP.L.Group} :</td>
			<td>{USERS_EDIT_TITLE} {PHP.L.adm_required}</td>
		</tr>
		<tr>
			<td>{PHP.L.Description} :</td>
			<td>{USERS_EDIT_DESC}</td>
		</tr>
		<tr>
			<td>{PHP.L.Icon} :</td>
			<td>{USERS_EDIT_ICON}</td>
		</tr>
		<tr>
			<td>{PHP.L.Alias} :</td>
			<td>{USERS_EDIT_ALIAS}</td>
		</tr>
		<tr>
			<td>{PHP.L.adm_color} :</td>
			<td>{USERS_EDIT_COLOR}</td>
		</tr>
		<tr>
			<td>{PHP.L.adm_maxsizesingle} :</td>
			<td>{USERS_EDIT_MAXFILESIZE}</td>
		</tr>
		<tr>
			<td>{PHP.L.adm_maxsizeallpfs} :</td>
			<td>{USERS_EDIT_MAXTOTALSIZE}</td>
		</tr>
		<tr>
			<td>{PHP.L.Enabled} :</td>
			<td>{USERS_EDIT_GRPDISABLE}</td>
		</tr>
		<tr>
			<td>{PHP.L.Hidden} :</td>
			<td>{USERS_EDIT_GRPHIDDEN}</td>
		</tr>
		<tr>
			<td>{PHP.L.Level} :</td>
			<td>{USERS_EDIT_GRPLEVEL}</td>
		</tr>
		<tr>
			<td>{PHP.L.Rights} :</td>
			<td><a href="{USERS_EDIT_RIGHT_URL}"><img src="system/img/admin/rights1.png" alt="" /></a></td>
		</tr>
		
		<!-- BEGIN: USERS_EDIT_ADMIN -->
		<tr>
			<td>{PHP.L.Delete} :</td>
			<td><a href="{USERS_EDIT_DELETE_URL}">{PHP.out.img_delete}</a></td>
		</tr>
		<!-- END: USERS_EDIT_ADMIN -->
		
		<tr>
			<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
		</tr>
	</table>
	</form>

	</div>
</div>
<!-- END: USERS_EDIT -->

<!-- BEGIN: USERS_GROUPS -->

<div class="content-box sedtabs">
	<div class="content-box-header">					
		<h3>{PHP.L.Users}</h3>					
		<ul class="content-box-tabs">
		  <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Users}</a></li>
		  <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.addnewentry}</a></li>
		</ul>					
		<div class="clear"></div>					
	</div>    

	<div class="content-box-content">
	<div class="tab-content default-tab" id="tab1">    

	<table class="cells striped">
	<thead>
		<tr>
			<th class="coltop">#ID</th>
			<th class="coltop">{PHP.L.Groups}</th>
			<th class="coltop" style="width:15%;">{PHP.L.Members}</th>
			<th class="coltop" style="width:15%;">{PHP.L.Main}</th>  
			<th class="coltop" style="width:12%;">{PHP.L.Enabled}</th>
			<th class="coltop" style="width:12%;">{PHP.L.Hidden}</th>
			<th class="coltop" style="width:12%;">{PHP.L.Rights}</th>
		</tr>
	</thead>

	<!-- BEGIN: GROUP_LIST -->
			<tr>
				<td style="text-align:center;">{GROUP_LIST_ID}</td>
				<td><a href="{GROUP_LIST_URL}"><span class="icon"><i class="fa fa-lg fa-user"></i></span> {GROUP_LIST_TITLE}</a></td>
				<td style="text-align:center;">{GROUP_LIST_GRP_COUNT}</td>
				<td style="text-align:center;">{GROUP_LIST_MAINGRP_COUNT}</td>
				<td style="text-align:center;">{GROUP_LIST_DISABLE}</td>
				<td style="text-align:center;">{GROUP_LIST_COUNT}</td>
				<td style="text-align:center;"><a href="{GROUP_LIST_RIGHT_URL}"><i class="fa fa-lg fa-lock"></i></a></td>
			</tr>
	<!-- END: GROUP_LIST -->

	</table>

	</div>
	<div class="tab-content" id="tab2">  

	<h4>{PHP.L.addnewentry} :</h4>
	
	<form id="addlevel" action="{GROUP_ADD_SEND}" method="post">
	
	<table class="cells striped">
	<tr>
		<td>{PHP.L.Group} :</td>
		<td>{GROUP_ADD_TITLE} {PHP.L.adm_required}</td>
	</tr>
	<tr>
		<td>{PHP.L.Description} :</td>
		<td>{GROUP_ADD_DESC}</td>
	</tr>
	<tr>
		<td>{PHP.L.Icon} :</td>
		<td>{GROUP_ADD_ICON}</td>
	</tr>
	<tr>
		<td>{PHP.L.Alias} :</td>
		<td>{GROUP_ADD_ALIAS}</td>
	</tr>
	<tr>
		<td>{PHP.L.adm_color} :</td>
		<td>{GROUP_ADD_COLOR}</td>
	</tr>
	<tr>
		<td>{PHP.L.adm_maxsizesingle} :</td>
		<td>{GROUP_ADD_MAXFILESIZE}</td>
	</tr>
	<tr>
		<td>{PHP.L.adm_maxsizeallpfs} :</td>
		<td>{GROUP_ADD_MAXTOTALSIZE}</td>
	</tr>
	<tr>
		<td>{PHP.L.adm_copyrightsfrom} :</td>
		<td>{GROUP_ADD_COPYRIGHTSFROM} {PHP.L.adm_required}</td>
	</tr>
	<tr>
		<td>{PHP.L.Level} :</td>
		<td>{GROUP_ADD_GRPLEVEL}</td></tr>
	<tr>
		<td>{PHP.L.Enabled} :</td>
		<td>{GROUP_ADD_GRPDISABLE}</td>
	</tr>
	<tr>
		<td>{PHP.L.Hidden} :</td>
		<td>{GROUP_ADD_GRPHIDDEN}</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Add}" /></td>
	</tr>
	</table>
	
	</form>
	</div>
	</div>
</div>

<!-- END: USERS_GROUPS -->

<!-- END: ADMIN_USERS -->
