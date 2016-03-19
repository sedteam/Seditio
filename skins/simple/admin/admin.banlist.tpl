<!-- BEGIN: ADMIN_BANLIST -->

<div class="content-box">
	<div class="content-box-header">					
		<h3>{PHP.L.Banlist}</h3>									
		<div class="clear"></div>					
	</div>

	<div class="content-box-content">  

		<table class="cells striped">
			<thead>
				<th class="coltop">{PHP.L.Delete}</th>
				<th class="coltop">{PHP.L.Until}</th>
				<th class="coltop">{PHP.L.adm_ipmask}</th>
				<th class="coltop">{PHP.L.adm_emailmask}</th>
				<th class="coltop">{PHP.L.Reason}</th>
				<th class="coltop">{PHP.L.Update}</th>
			</thead>

			<!-- BEGIN: BANLIST_EDIT_LIST -->
			
			<form id="savebanlist_{BANLIST_EDIT_ID}" action="{BANLIST_EDIT_SEND_URL}" method="post">
			<tr>
				<td style="text-align:center;"><a href="{BANLIST_EDIT_DELETE_URL}" class="btn btn-small"><i class="fa fa-trash"></i></a></td>
				<td style="text-align:center;">{BANLIST_EDIT_EXPIRE}</td>
				<td>{BANLIST_EDIT_IP}</td>
				<td>{BANLIST_EDIT_EMAIL_MASK}</td>
				<td>{BANLIST_EDIT_REASON}</td>
				<td style="text-align:center;"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
			</tr>
			</form>
			
			<!-- END: BANLIST_EDIT_LIST -->
			
		</table>
		
	</div>
	
</div>	

<div class="content-box">
	<div class="content-box-header">					
		<h3>{PHP.L.addnewentry}</h3>									
		<div class="clear"></div>					
	</div>

	<div class="content-box-content">  

		<form id="addbanlist" action="{BANLIST_ADD_SEND_URL}" method="post">
			
		<table class="cells striped">
		<tr>
			<td>{PHP.L.Duration} :</td><td>{BANLIST_ADD_NEXPIRE}</td>
		</tr>

		<tr>
			<td>{PHP.L.Ipmask} :</td><td>{BANLIST_ADD_IP}</td>
		</tr>
		<tr>
			<td>{PHP.L.Emailmask} :</td><td>{BANLIST_ADD_EMAIL_MASK}</td>
		</tr>
		<tr>
			<td>{PHP.L.Reason} :</td><td>{BANLIST_ADD_REASON}</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Add}" /></td>
		</tr>
		</table>

		</form>

	</div>
	
</div>

<!-- END: ADMIN_BANLIST -->
