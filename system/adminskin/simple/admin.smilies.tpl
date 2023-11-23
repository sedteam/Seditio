<!-- BEGIN: ADMIN_SMILIES -->

<div class="content-box sedtabs">
	<div class="content-box-header">
		<h3>{PHP.L.Smilies}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Smilies}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.addnewentry}</a></li>
		</ul>
		<div class="clear"></div>
	</div>

	<div class="content-box-content">
		<div class="tab-content default-tab" id="tab1">

			<!-- BEGIN: SMILIES_LIST -->
			<form id="savesmilies" action="{SMILIES_UPDATE_SEND}" method="post">

				<table class="cells striped">
					<thead>
						<tr>
							<th class="coltop" style="width:40px;">{PHP.L.Delete}</th>
							<th class="coltop" style="width:48px;">{PHP.L.Preview}</th>
							<th class="coltop" style="width:64px;">{PHP.L.Size}</th>
							<th class="coltop">{PHP.L.Code}</th>
							<th class="coltop">{PHP.L.ImageURL}</th>
							<th class="coltop">{PHP.L.Text}</th>
							<th class="coltop">{PHP.L.Order}</th>
						</tr>
					</thead>
					<tbody>
						<!-- BEGIN: SMILIES_LIST_ITEM -->
						<tr>
							<td><a href="{SMILIE_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="ic-trash"></i></a></td>
							<td>{SMILIE_LIST_PREVIEW}</td>
							<td>{SMILIE_LIST_SIZE}</td>
							<td>{SMILIE_LIST_CODE}</td>
							<td>{SMILIE_LIST_IMAGE}</td>
							<td>{SMILIE_LIST_TEXT}</td>
							<td>{SMILIE_LIST_ORDER}</td>
						</tr>
						<!-- END: SMILIES_LIST_ITEM -->
					</tbody>
				</table>

				<div class="form-field" style="padding:10px 0;">
					<button type="submit" class="submit btn">{PHP.L.Update}</button>
				</div>
			</form>
			<!-- END: SMILIES_LIST -->

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

			<!-- BEGIN: ADD_SMILIE -->

			<h4>{PHP.L.addnewentry}</h4>

			<form id="addmenus" action="{SMILIE_ADD_SEND}" method="post">

				<table class="cells striped">

					<tr>
						<td style="width:180px;">{PHP.L.Code} :</td>
						<td>{SMILIE_ADD_CODE} {PHP.L.adm_required}</td>
					</tr>

					<tr>
						<td>{PHP.L.ImageURL} :</td>
						<td>{SMILIE_ADD_IMAGEURL} {PHP.L.adm_required}</td>
					</tr>

					<tr>
						<td>{PHP.L.Text} :</td>
						<td>{SMILIE_ADD_TEXT} {PHP.L.adm_required}</td>
					</tr>

					<tr>
						<td>{PHP.L.Order} :</td>
						<td>{SMILIE_ADD_ORDER} {PHP.L.adm_required}</td>
					</tr>

					<tr>
						<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Add}" /></td>
					</tr>

				</table>

			</form>

			<!-- END: ADD_SMILIE -->

		</div>
	</div>
</div>

<!-- END: ADMIN_SMILIES -->