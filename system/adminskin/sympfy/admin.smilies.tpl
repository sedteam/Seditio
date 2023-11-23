<!-- BEGIN: ADMIN_SMILIES -->

<div class="title">
	<span><i class="ic-menu"></i></span>
	<h2>{ADMIN_SMILIES_TITLE}</h2>
</div>

<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.Smilies}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.Smilies}">{PHP.L.Smilies}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.addnewentry}">{PHP.L.addnewentry}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<div class="tab-content default-tab" id="tab1">

			<!-- BEGIN: SMILIES_LIST -->
			<form id="savesmilies" action="{SMILIES_UPDATE_SEND}" method="post">

				<div class="table cells striped resp-table">

					<div class="table-head resp-table-head">
						<div class="table-row resp-table-row">
							<div class="table-th coltop text-left" style="width:48px;">{PHP.L.Preview}</div>
							<div class="table-th coltop text-left" style="width:64px;">{PHP.L.Size}</div>
							<div class="table-th coltop text-left">{PHP.L.Code}</div>
							<div class="table-th coltop text-left">{PHP.L.ImageURL}</div>
							<div class="table-th coltop text-left">{PHP.L.Text}</div>
							<div class="table-th coltop text-left">{PHP.L.Order}</div>
							<div class="table-th coltop text-left" style="width:40px;">{PHP.L.Delete}</div>
						</div>
					</div>

					<div class="table-body resp-table-body">

						<!-- BEGIN: SMILIES_LIST_ITEM -->
						<div class="table-row resp-table-row">

							<div class="table-td text-left resp-table-td" data-label="{PHP.L.Preview}">{SMILIE_LIST_PREVIEW}</div>
							<div class="table-td text-left resp-table-td" data-label="{PHP.L.Size}">{SMILIE_LIST_SIZE}</div>
							<div class="table-td text-left resp-table-td" data-label="{PHP.L.Code}">{SMILIE_LIST_CODE}</div>
							<div class="table-td text-left resp-table-td" data-label="{PHP.L.ImageURL}">{SMILIE_LIST_IMAGE}</div>
							<div class="table-td text-left resp-table-td" data-label="{PHP.L.Text}">{SMILIE_LIST_TEXT}</div>
							<div class="table-td text-left resp-table-td" data-label="{PHP.L.Order}">{SMILIE_LIST_ORDER}</div>
							<div class="table-td text-left resp-table-td smilies-action">
								<a href="{SMILIE_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="ic-trash"></i></a>
							</div>

						</div>
						<!-- END: SMILIES_LIST_ITEM -->

					</div>

				</div>

				<div class="table-btn text-center">
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

			<form id="addmenus" action="{SMILIE_ADD_SEND}" method="post">

				<div class="table cells striped resp-table">

					<div class="table-body resp-table-body">

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td" style="width:180px;">
								{PHP.L.Code} :
							</div>
							<div class="table-td text-left resp-table-td">
								{SMILIE_ADD_CODE}
								<div class="descr">{PHP.L.adm_required}</div>
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.ImageURL} :
							</div>
							<div class="table-td text-left resp-table-td">
								{SMILIE_ADD_IMAGEURL}
								<div class="descr">{PHP.L.adm_required}</div>
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.Text} :
							</div>
							<div class="table-td text-left resp-table-td">
								{SMILIE_ADD_TEXT}
								<div class="descr">{PHP.L.adm_required}</div>
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.Order} :
							</div>
							<div class="table-td text-left resp-table-td">
								{SMILIE_ADD_ORDER}
								<div class="descr">{PHP.L.adm_required}</div>
							</div>
						</div>

					</div>

				</div>

				<div class="table-btn text-center">
					<button type="submit" class="submit btn">{PHP.L.Add}</button>
				</div>

			</form>

			<!-- END: ADD_SMILIE -->

		</div>

	</div>

</div>

<!-- END: ADMIN_SMILIES -->