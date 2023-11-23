<!-- BEGIN: ADMIN_FORUMS -->

<!-- BEGIN: FORUMS_STRUCTURE_UPDATE -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.editdeleteentries} : {FN_UPDATE_FORM_TITLE}</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content">

		<form id="savestructure" action="{FN_UPDATE_SEND}" method="post">

			<table class="cells striped">

				<tr>
					<td>{PHP.L.Code} :</td>
					<td>{FN_UPDATE_CODE}</td>
				</tr>

				<tr>
					<td>{PHP.L.Path} :</td>
					<td>{FN_UPDATE_PATH}</td>
				</tr>

				<tr>
					<td>{PHP.L.Title} :</td>
					<td>{FN_UPDATE_TITLE}</td>
				</tr>

				<tr>
					<td>{PHP.L.Description} :</td>
					<td>{FN_UPDATE_DESC}</td>
				</tr>

				<tr>
					<td>{PHP.L.Icon} :</td>
					<td>{FN_UPDATE_ICON}</td>
				</tr>

				<tr>
					<td>{PHP.L.adm_defstate} :</td>
					<td>{FN_UPDATE_DEFSTATE}</td>
				</tr>

				<tr>
					<td>{PHP.L.adm_tpl_mode} :</td>
					<td>{FN_UPDATE_TPLMODE}</td>
				</tr>

				<tr>
					<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
				</tr>

			</table>
		</form>

	</div>

</div>

<!-- END: FORUMS_STRUCTURE_UPDATE -->


<!-- BEGIN: FORUMS_STRUCTURE -->

<div class="content-box sedtabs">
	<div class="content-box-header">
		<h3>{PHP.L.adm_forum_structure}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.editdeleteentries}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.addnewentry}</a></li>
		</ul>
		<div class="clear"></div>
	</div>

	<div class="content-box-content">
		<div class="tab-content default-tab" id="tab1">

			<form id="savestructure" action="{FORUMS_STRUCTURE_UPDATE_SEND}" method="post">

				<table class="cells striped">
					<thead>
						<tr>
							<th class="coltop">{PHP.L.Delete}</th>
							<th class="coltop">{PHP.L.Code}</th>
							<th class="coltop">{PHP.L.Path}</th>
							<th class="coltop">{PHP.L.adm_defstate}</th>
							<th class="coltop">{PHP.L.TPL}</th>
							<th class="coltop">{PHP.L.Title}</th>
							<th class="coltop">{PHP.L.Sections}</th>
							<th class="coltop">{PHP.L.Options}</th>
						</tr>
					</thead>

					<!-- BEGIN: STRUCTURE_LIST -->

					<tr>
						<td style="text-align:center;">
							<!-- BEGIN: STRUCTURE_LIST_DELETE -->
							<a href="{STRUCTURE_LIST_DELETE_URL}"><i class="ic-trash"></i></a>
							<!-- END: STRUCTURE_LIST_DELETE -->
						</td>
						<td>{STRUCTURE_LIST_CODE}</td>
						<td>{STRUCTURE_LIST_PATH}</td>
						<td style="text-align:center;">{STRUCTURE_LIST_DEFSTATE}</td>
						<td style="text-align:center;">{STRUCTURE_LIST_TPL}</td>
						<td>{STRUCTURE_LIST_TITLE}</td>
						<td style="text-align:right;">{STRUCTURE_LIST_SECTIONCOUNT} <a href="{STRUCTURE_LIST_OPEN_URL}"><i class="ic-arrow-right"></i></a></td>
						<td style="text-align:center;"><a href="{STRUCTURE_LIST_OPTIONS_URL}"><i class="ic-settings"></i></a></a></td>
					</tr>

					<!-- END: STRUCTURE_LIST -->

					<tr>
						<td colspan="9"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
					</tr>
				</table>
			</form>

		</div>

		<div class="tab-content" id="tab2">

			<h4>{PHP.L.addnewentry}</h4>

			<form id="addstructure" action="{FN_ADD_SEND}" method="post">

				<table class="cells striped">

					<tr>
						<td style="width:180px;">{PHP.L.Code} :</td>
						<td>{FN_ADD_CODE} {PHP.L.adm_required}</td>
					</tr>

					<tr>
						<td>{PHP.L.Path} :</td>
						<td>{FN_ADD_PATH} {PHP.L.adm_required}</td>
					</tr>

					<tr>
						<td>{PHP.L.adm_defstate} :</td>
						<td>{FN_ADD_DEFSTATE}</td>
					</tr>

					<tr>
						<td>{PHP.L.Title} :</td>
						<td>{FN_ADD_TITLE} {PHP.L.adm_required}</td>
					</tr>

					<tr>
						<td>{PHP.L.Description} :</td>
						<td>{FN_ADD_DESC}</td>
					</tr>

					<tr>
						<td>{PHP.L.Icon} :</td>
						<td>{FN_ADD_ICON}</td>
					</tr>

					<tr>
						<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Add}" /></td>
					</tr>

				</table>

			</form>

		</div>
	</div>
</div>

<!-- END: FORUMS_STRUCTURE -->

<!-- END: ADMIN_FORUMS -->