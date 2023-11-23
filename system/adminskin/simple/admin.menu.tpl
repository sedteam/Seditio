<!-- BEGIN: ADMIN_MENU -->

<!-- BEGIN: MENU_DEFAULT -->

<div class="content-box sedtabs">
	<div class="content-box-header">
		<h3{PHP.L.core_menu}< /h3>
			<ul class="content-box-tabs">
				<li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.adm_menuitems}</a></li>
				<li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.adm_additem}</a></li>
			</ul>
			<div class="clear"></div>
	</div>

	<div class="content-box-content">
		<div class="tab-content default-tab" id="tab1">

			<!-- BEGIN: MENU_LIST -->
			<form id="updatepos" action="{MENU_UPDATE_POSITION_SEND}" method="post">
				<table class="cells striped">
					<thead>
						<tr>
							<th class="coltop" style="width:20px;">{PHP.L.Id}</th>
							<th class="coltop">{PHP.L.Title}</th>
							<th class="coltop">{PHP.L.adm_url}</th>
							<th class="coltop" style="width:30px;">{PHP.L.adm_position}</th>
							<th class="coltop" style="width:100px;">{PHP.L.Options}</th>
						</tr>
					</thead>

					<!-- BEGIN: MENU_LIST_ITEM -->
					<tr>
						<td style="text-align:center;">{MENU_ID}</td>
						<td>{MENU_TITLE}</td>
						<td><a href="{MENU_URL}">{MENU_URL}</a></td>
						<td>{MENU_POSITION}</td>
						<td style="text-align:center;">
							<a href="{MENU_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="ic-trash"></i></a>
							<a href="{MENU_EDIT_URL}" title="{PHP.L.Edit}" class="btn btn-small"><i class="ic-edit"></i></a>
						</td>
					</tr>
					<!-- END: MENU_LIST_ITEM -->
				</table>
				<div class="form-field" style="padding:10px 0;">
					<button type="submit" class="submit btn">{PHP.L.Update}</button>
				</div>
			</form>
			<!-- END: MENU_LIST -->

			<script>
				function confirmDelete() {
					if (confirm("{PHP.L.adm_confirm_delete}")) {
					return true;
				} else {
					return false;
				}
				}
			</script>

		</div>

		<div class="tab-content" id="tab2">

			<h4>{PHP.L.adm_addmenuitem}</h4>

			<form id="addmenus" action="{MENU_ADD_SEND}" method="post">

				<table class="cells striped">

					<tr>
						<td style="width:180px;">{PHP.L.Title} :</td>
						<td>{MENU_ADD_TITLE} {PHP.L.adm_required}</td>
					</tr>

					<tr>
						<td>{PHP.L.adm_parentitem} :</td>
						<td>{MENU_ADD_PARENT}</td>
					</tr>

					<tr>
						<td>{PHP.L.adm_url} :</td>
						<td>{MENU_ADD_URL}</td>
					</tr>

					<tr>
						<td>{PHP.L.adm_position} :</td>
						<td>{MENU_ADD_POSITION}</td>
					</tr>

					<tr>
						<td>{PHP.L.adm_activity} :</td>
						<td>{MENU_ADD_VISIBLE}</td>
					</tr>

					<tr>
						<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Add}" /></td>
					</tr>

				</table>

			</form>

		</div>
	</div>
</div>

<!-- END: MENU_DEFAULT -->

<!-- BEGIN: MENU_EDIT -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.adm_editmenuitem}</h3>
		<div class="content-box-header-right">
			<div class="clear"></div>
		</div>
	</div>

	<div class="content-box-content">

		<form id="addmenus" action="{MENU_UPDATE_SEND}" method="post">

			<table class="cells striped">

				<tr>
					<td style="width:180px;">{PHP.L.Title} :</td>
					<td>{MENU_UPDATE_TITLE} {PHP.L.adm_required}</td>
				</tr>

				<tr>
					<td>{PHP.L.adm_parentitem}:</td>
					<td>{MENU_UPDATE_PARENT}</td>
				</tr>

				<tr>
					<td>{PHP.L.adm_url} :</td>
					<td>{MENU_UPDATE_URL}</td>
				</tr>

				<tr>
					<td>{PHP.L.adm_position} :</td>
					<td>{MENU_UPDATE_POSITION}</td>
				</tr>

				<tr>
					<td>{PHP.L.adm_activity} :</td>
					<td>{MENU_UPDATE_VISIBLE}</td>
				</tr>

				<tr>
					<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
				</tr>

			</table>

		</form>

	</div>

</div>

<!-- END: MENU_EDIT -->

<!-- END: ADMIN_MENU -->