<!-- BEGIN: ADMIN_RIGHTS -->

<div class="title">
	<span><i class="ic-cache"></i></span>
	<h2>{ADMIN_RIGHTS_TITLE}</h2>
</div>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Rights}</h3>
	</div>

	<div class="content-box-content content-table">

		<form id="saverights" action="{RIGHTS_UPDATE_SEND}" method="post">

			<!-- BEGIN: RIGHTSBYITEM -->

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left">{PHP.L.Groups}</div>
						<div class="table-th coltop text-center" style="width:128px;">{PHP.L.adm_rightspergroup}</div>
						<div class="table-th coltop text-left" style="width:24px;"><img src="system/img/admin/auth_r.gif" alt="" /></div>
						<div class="table-th coltop text-left" style="width:24px;"><img src="system/img/admin/auth_w.gif" alt="" /></div>

						<!-- BEGIN: ADVANCED_RIGHTS -->
						<div class="table-th coltop text-left" style="width:24px;"><img src="system/img/admin/auth_1.gif" alt="" /></div>
						<div class="table-th coltop text-left" style="width:24px;"><img src="system/img/admin/auth_2.gif" alt="" /></div>
						<div class="table-th coltop text-left" style="width:24px;"><img src="system/img/admin/auth_3.gif" alt="" /></div>
						<div class="table-th coltop text-left" style="width:24px;"><img src="system/img/admin/auth_4.gif" alt="" /></div>
						<div class="table-th coltop text-left" style="width:24px;"><img src="system/img/admin/auth_5.gif" alt="" /></div>
						<!-- END: ADVANCED_RIGHTS -->

						<div class="table-th coltop text-left" style="width:24px;"><img src="system/img/admin/auth_a.gif" alt="" /></div>
						<div class="table-th coltop text-left" style="width:80px;">{PHP.L.adm_setby}</div>
						<div class="table-th coltop text-center" style="width:64px;">{PHP.L.Open}</div>
					</div>
				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: RIGHTS_LIST -->
					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td rights-grouptitle">
							<a href="{RIGHTS_LIST_URL}"><span class="icon"><i class="ic-user"></i></span> {RIGHTS_LIST_TITLE}</a>
						</div>
						<div class="table-td text-center resp-table-td rights-per-group" data-label="{PHP.L.adm_rightspergroup}">
							<a href="{RIGHTS_LIST_RIGHTBYITEM_URL}"><i class="ic-lock"></i></a>
						</div>

						<!-- BEGIN: RIGHTS_LIST_OPTIONS -->
						<div class="table-td text-left resp-table-td rights-options" data-afterlabel="{RIGHTS_OPTIONS_CODE}">
							{RIGHTS_OPTIONS}
						</div>
						<!-- END: RIGHTS_LIST_OPTIONS -->

						<div class="table-td text-left resp-table-td rights-user" data-label="{PHP.L.adm_setby}">
							{RIGHTS_LIST_SETBYUSER}
						</div>
						<div class="table-td text-center resp-table-td rights-url">
							<a href="{RIGHTS_LIST_OPEN_URL}" class="btn btn-small circle-btn"><i class="ic-chevron-right"></i></a>
						</div>
					</div>
					<!-- END: RIGHTS_LIST -->

				</div>

			</div>

			<div class="table-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>

			<!-- END: RIGHTSBYITEM -->

		</form>

	</div>

</div>

<!-- END: ADMIN_RIGHTS -->