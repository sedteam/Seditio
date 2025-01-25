<!-- BEGIN: ADMIN_MENU -->

<div class="title">
	<span><i class="ic-menu"></i></span>
	<h2>{ADMIN_MENU_TITLE}</h2>
</div>

<!-- BEGIN: MENU_DEFAULT -->

<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.adm_menuitems}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.adm_menuitems}">{PHP.L.adm_menuitems}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.adm_additem}">{PHP.L.adm_additem}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<div class="tab-content default-tab" id="tab1">

			<!-- BEGIN: MENU_LIST -->
			<form id="updatepos" action="{MENU_UPDATE_POSITION_SEND}" method="post">

				<div class="table cells striped resp-table">

					<div class="table-head resp-table-head">
						<div class="table-row resp-table-row">
							<div class="table-th coltop text-left" style="width:20px;">{PHP.L.Id}</div>
							<div class="table-th coltop text-left">{PHP.L.Title}</div>
							<div class="table-th coltop text-left">{PHP.L.adm_url}</div>
							<div class="table-th coltop text-left" style="width:30px;">{PHP.L.adm_position}</div>
							<div class="table-th coltop text-center" style="width:100px;">{PHP.L.Options}</div>
						</div>
					</div>

					<div class="table-body resp-table-body">

						<!-- BEGIN: MENU_LIST_ITEM -->
						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td" data-label="{PHP.L.Id}">{MENU_ID}</div>
							<div class="table-td text-left resp-table-td" data-label="{PHP.L.Title}">{MENU_TITLE}</div>
							<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_url}"><a href="{MENU_URL}">{MENU_URL}</a></div>
							<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_position}">{MENU_POSITION}</div>
							<div class="table-td text-center resp-table-td menu-action">
								<a href="{MENU_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="ic-trash"></i></a>
								<a href="{MENU_EDIT_URL}" title="{PHP.L.Edit}" class="btn btn-small"><i class="ic-edit"></i></a>
							</div>
						</div>
						<!-- END: MENU_LIST_ITEM -->

					</div>

				</div>

				<div class="table-btn text-center">
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

			<form id="addmenus" action="{MENU_ADD_SEND}" method="post">

				<div class="table cells striped resp-table">

					<div class="table-body resp-table-body">

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td" style="width:180px;">{PHP.L.Title} :</div>
							<div class="table-td text-left resp-table-td">
								{MENU_ADD_TITLE}
								<div class="descr">{PHP.L.adm_required}</div>
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">{PHP.L.adm_parentitem} :</div>
							<div class="table-td text-left resp-table-td">{MENU_ADD_PARENT}</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">{PHP.L.adm_url} :</div>
							<div class="table-td text-left resp-table-td">{MENU_ADD_URL}</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">{PHP.L.adm_position} :</div>
							<div class="table-td text-left resp-table-td">{MENU_ADD_POSITION}</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">{PHP.L.adm_activity} :</div>
							<div class="table-td text-left resp-table-td">{MENU_ADD_VISIBLE}</div>
						</div>

					</div>

				</div>

				<div class="table-btn text-center">
					<button type="submit" class="submit btn">{PHP.L.Add}</button>
				</div>

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

	<div class="content-box-content content-table">

		<form id="updatemenus" action="{MENU_UPDATE_SEND}" method="post">

			<div class="table cells striped resp-table">

				<div class="table-body resp-table-body">

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td" style="width:180px;">{PHP.L.Title} :</div>
						<div class="table-td text-left resp-table-td">
							{MENU_UPDATE_TITLE}
							<div class="descr">{PHP.L.adm_required}</div>
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">{PHP.L.adm_parentitem}:</div>
						<div class="table-td text-left resp-table-td">{MENU_UPDATE_PARENT}</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">{PHP.L.adm_url} :</div>
						<div class="table-td text-left resp-table-td">{MENU_UPDATE_URL}</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">{PHP.L.adm_position} :</div>
						<div class="table-td text-left resp-table-td">{MENU_UPDATE_POSITION}</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">{PHP.L.adm_activity} :</div>
						<div class="table-td text-left resp-table-td">{MENU_UPDATE_VISIBLE}</div>
					</div>

				</div>

			</div>

			<div class="table-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>

		</form>

	</div>

</div>

<!-- END: MENU_EDIT -->

<!-- END: ADMIN_MENU -->