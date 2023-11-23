<!-- BEGIN: ADMIN_USERS -->

<div class="title">
	<span><i class="ic-users"></i></span>
	<h2>{ADMIN_USERS_TITLE}</h2>
</div>

<!-- BEGIN: USERS_BUTTONS -->

<ul class="shortcut-buttons-set">

	<!-- BEGIN: USERS_BUTTONS_CONFIG -->
	<li><a class="shortcut-button" href="{BUTTON_USERS_CONFIG_URL}"><span>
				<i class="ic-settings ic-3x"></i><br />
				{PHP.L.Configuration}
			</span></a></li>
	<!-- END: USERS_BUTTONS_CONFIG -->

	<!-- BEGIN: USERS_BUTTONS_BANLIST -->
	<li><a class="shortcut-button" href="{BUTTON_USERS_BANLIST_URL}"><span>
				<i class="ic-banlist ic-3x"></i><br />
				{PHP.L.Banlist}
			</span></a></li>
	<!-- END: USERS_BUTTONS_BANLIST -->

</ul>

<div class="clear"></div>

<!-- END: USERS_BUTTONS -->

<!-- BEGIN: USERS_EDIT -->
<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.editdeleteentries}</h3>
	</div>

	<div class="content-box-content content-table">

		<form id="editlevel" action="{USERS_EDIT_SEND}" method="post">

			<ul class="form responsive-form">

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Group} :</label></div>
					<div class="form-field">{USERS_EDIT_TITLE} <div class="descr">{PHP.L.adm_required}</div>
					</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Description} :</label></div>
					<div class="form-field">{USERS_EDIT_DESC}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Icon} :</label></div>
					<div class="form-field">{USERS_EDIT_ICON}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Alias} :</label></div>
					<div class="form-field">{USERS_EDIT_ALIAS}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_color} :</label></div>
					<div class="form-field">{USERS_EDIT_COLOR}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_maxsizesingle} :</label></div>
					<div class="form-field">{USERS_EDIT_MAXFILESIZE}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_maxsizeallpfs} :</label></div>
					<div class="form-field">{USERS_EDIT_MAXTOTALSIZE}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Enabled} :</label></div>
					<div class="form-field">{USERS_EDIT_GRPDISABLE}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Hidden} :</label></div>
					<div class="form-field">{USERS_EDIT_GRPHIDDEN}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Level} :</label></div>
					<div class="form-field">{USERS_EDIT_GRPLEVEL}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Rights} :</label></div>
					<div class="form-field"><a href="{USERS_EDIT_RIGHT_URL}"><i class="ic-lock"></i></a></div>
				</li>

				<!-- BEGIN: USERS_EDIT_ADMIN -->
				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Delete} :</label></div>
					<div class="form-field"><a href="{USERS_EDIT_DELETE_URL}">{PHP.out.img_delete}</a></div>
				</li>
				<!-- END: USERS_EDIT_ADMIN -->

				<li class="form-row">
					<div class="form-field-100 text-center">
						<button type="submit" class="submit btn">{PHP.L.Update}</button>
					</div>
				</li>

			</ul>

		</form>

	</div>

</div>
<!-- END: USERS_EDIT -->

<!-- BEGIN: USERS_GROUPS -->

<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.Users}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.Users}">{PHP.L.Users}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.addnewentry}">{PHP.L.addnewentry}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<div class="tab-content default-tab" id="tab1">

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">

					<div class="table-row resp-table-row">
						<div class="table-th coltop text-center">#ID</div>
						<div class="table-th coltop text-left">{PHP.L.Groups}</div>
						<div class="table-th coltop text-center" style="width:15%;">{PHP.L.Members}</div>
						<div class="table-th coltop text-center" style="width:15%;">{PHP.L.Main}</div>
						<div class="table-th coltop text-center" style="width:12%;">{PHP.L.Enabled}</div>
						<div class="table-th coltop text-center" style="width:12%;">{PHP.L.Hidden}</div>
						<div class="table-th coltop text-center" style="width:12%;">{PHP.L.Rights}</div>
					</div>

				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: GROUP_LIST -->
					<div class="table-row resp-table-row">
						<div class="table-td text-center resp-table-td users-id" data-label="#ID">
							{GROUP_LIST_ID}
						</div>
						<div class="table-td text-left resp-table-td users-groups" data-label="{PHP.L.Groups}">
							<a href="{GROUP_LIST_URL}"><span class="icon"><i class="ic-user"></i></span> {GROUP_LIST_TITLE}</a>
						</div>
						<div class="table-td text-center resp-table-td users-members" data-label="{PHP.L.Members}">
							{GROUP_LIST_GRP_COUNT}
						</div>
						<div class="table-td text-center resp-table-td users-main" data-label="{PHP.L.Main}">
							{GROUP_LIST_MAINGRP_COUNT}
						</div>
						<div class="table-td text-center resp-table-td users-enable" data-label="{PHP.L.Enabled}">
							{GROUP_LIST_DISABLE}
						</div>
						<div class="table-td text-center resp-table-td users-hidden" data-label="{PHP.L.Hidden}">
							{GROUP_LIST_COUNT}
						</div>
						<div class="table-td text-center resp-table-td users-rights" data-label="{PHP.L.Rights}">
							<a href="{GROUP_LIST_RIGHT_URL}"><i class="ic-lock"></i></a>
						</div>
					</div>
					<!-- END: GROUP_LIST -->

				</div>

			</div>

		</div>

		<div class="tab-content" id="tab2">

			<form id="addlevel" action="{GROUP_ADD_SEND}" method="post">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Group} :</label></div>
						<div class="form-field">{GROUP_ADD_TITLE} <div class="descr">{PHP.L.adm_required}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Description} :</label></div>
						<div class="form-field">{GROUP_ADD_DESC}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Icon} :</label></div>
						<div class="form-field">{GROUP_ADD_ICON}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Alias} :</label></div>
						<div class="form-field">{GROUP_ADD_ALIAS}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.adm_color} :</label></div>
						<div class="form-field">{GROUP_ADD_COLOR}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.adm_maxsizesingle} :</label></div>
						<div class="form-field">{GROUP_ADD_MAXFILESIZE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.adm_maxsizeallpfs} :</label></div>
						<div class="form-field">{GROUP_ADD_MAXTOTALSIZE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.adm_copyrightsfrom} :</label></div>
						<div class="form-field">{GROUP_ADD_COPYRIGHTSFROM} <div class="descr">{PHP.L.adm_required}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Level} :</label></div>
						<div class="form-field">{GROUP_ADD_GRPLEVEL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Enabled} :</label></div>
						<div class="form-field">{GROUP_ADD_GRPDISABLE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Hidden} :</label></div>
						<div class="form-field">{GROUP_ADD_GRPHIDDEN}</div>
					</li>

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.L.Add}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</div>

<!-- END: USERS_GROUPS -->

<!-- END: ADMIN_USERS -->