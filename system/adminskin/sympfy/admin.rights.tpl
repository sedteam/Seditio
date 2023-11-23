<!-- BEGIN: ADMIN_RIGHTS -->

<div class="title">
	<span><i class="ic-forums"></i></span>
	<h2>{ADMIN_RIGHTS_TITLE}</h2>
</div>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Rights}</h3>
		<!-- BEGIN: RIGHTS_COPY -->
		<div class="content-box-header-right">
			<form id="copyrights" action="{RIGHTS_UPDATE_SEND}" method="post">
				{RIGHTS_COPYRIGHTSCONF} {PHP.L.adm_copyrightsfrom} : {RIGHTS_COPYRIGHTSFROM}
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</form>
		</div>
		<!-- END: RIGHTS_COPY -->
	</div>

	<div class="content-box-content content-table">

		<form id="saverights" action="{RIGHTS_UPDATE_SEND}" method="post">

			<!-- BEGIN: RIGHTS_GROUP -->

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left">{RIGHTS_GROUP_TITLE}</div>
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
					</div>
				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: RIGHTS_LIST -->

					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td rights-grouptitle">
							<a href="{RIGHTS_LIST_URL}"><span class="icon"><i class="ic-{RIGHTS_LIST_CODE}"></i></span> {RIGHTS_LIST_TITLE}</a>
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

					</div>

					<!-- END: RIGHTS_LIST -->

				</div>

			</div>

			<!-- BEGIN: RIGHTS_UPDATE -->
			<div class="table-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>
			<!-- END: RIGHTS_UPDATE -->

			<!-- END: RIGHTS_GROUP -->

		</form>

	</div>

</div>

<!-- END: ADMIN_RIGHTS -->