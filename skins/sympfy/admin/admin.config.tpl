<!-- BEGIN: ADMIN_CONFIG -->

<div class="content-box">
	<div class="content-box-header">
		<h3><i class="fa fa-cog"></i> {ADMIN_CONFIG_ADMINLEGEND}</h3>
	</div>
	<div class="content-box-content">
	<form id="saveconfig" action="{ADMIN_CONFIG_FORM_SEND}" method="post">
		<table class="cells striped">
			<thead>
				<tr>
					<th  class="coltop" colspan="2">{PHP.L.Configuration}</th>
					<th class="coltop">{PHP.L.Reset}</th>
				</tr>
			</thead>
			
			<!-- BEGIN: CONFIG_LIST -->
			
			<tr>
				<td style="width:40%;">{CONFIG_LIST_TITLE} </td>
				<td style="width:60%;">{CONFIG_LIST_FIELD}<br /><div class="descr">{CONFIG_LIST_DESC}</div></td>
				<td style="text-align:center; width:7%;"><a href="{CONFIG_LIST_RESET_URL}" title="{PHP.L.Reset}" class="btn btn-small"><i class="fa fa-undo"></i></a></td>
			</tr>
			
			<!-- END: CONFIG_LIST -->
			
			<tr>
				<td colspan="3"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
			</tr>
		
		</table>
	</form>
	</div>
</div>

<!-- BEGIN: HELP -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.Help}</h3>
	</div>
	<div class="content-box-content">
		<p>{HELP_CONFIG}</p>
	</div>
</div>

<!-- END: HELP -->

<!-- END: ADMIN_CONFIG -->
