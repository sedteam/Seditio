<!-- BEGIN: ADMIN_PAGE_MANAGER -->

<div class="title">
	<span><i class="ic-forums"></i></span>
	<h2>{ADMIN_PAGE_MANAGER_TITLE}</h2>
</div>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Pages} ({PAGE_MANAGER_COUNT})</h3>
		<div class="content-box-header-right">
			{PHP.L.Category} {PAGE_MANAGER_CATEGORY}
		</div>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">

				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left" style="width:40px;">#ID</div>
					<div class="table-th coltop text-left">{PHP.L.Title}</div>
					<div class="table-th coltop text-left" style="width:100px;">{PHP.L.Date}</div>
					<div class="table-th coltop text-left" style="width:180px;">{PHP.L.Owner}</div>
					<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Status}</div>
					<div class="table-th coltop text-center" style="width:135px;">{PHP.L.Action}</div>
				</div>

			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: PAGE_LIST -->

				<div class="table-row resp-table-row">

					<div class="table-td text-left resp-table-td pagemanager-id">
						#{PAGE_ID}
					</div>
					<div class="table-td text-left resp-table-td pagemanager-title" data-label="{PHP.L.Title}">
						<a href="{PAGE_URL}">{PAGE_TITLE}</a>
					</div>
					<div class="table-td text-left resp-table-td pagemanager-date" data-label="{PHP.L.Date}">
						{PAGE_DATE}
					</div>
					<div class="table-td text-left resp-table-td pagemanager-owner" data-label="{PHP.L.Owner}">
						<i class="ic-user"></i> {PAGE_OWNER}
					</div>
					<div class="table-td text-center resp-table-td pagemanager-status" data-label="{PHP.L.Status}">

						<!-- BEGIN: PAGE_VALIDATE -->
						<i class="ic-eye-off" title="{PHP.L.Validate}"></i>
						<!-- END: PAGE_VALIDATE -->

						<!-- BEGIN: PAGE_UNVALIDATE -->
						<i class="ic-eye" title="{PHP.L.Putinvalidationqueue}"></i>
						<!-- END: PAGE_UNVALIDATE -->

					</div>
					<div class="table-td text-center resp-table-td pagemanager-actions" data-label="{PHP.L.Action}">

						<!-- BEGIN: ADMIN_ACTIONS -->

						<!-- BEGIN: PAGE_VALIDATE -->
						<a href="{PAGE_VALIDATE_URL}" title="{PHP.L.Validate}" class="btn btn-small"><i class="ic-eye-off"></i></a>
						<!-- END: PAGE_VALIDATE -->

						<!-- BEGIN: PAGE_UNVALIDATE -->
						<a href="{PAGE_UNVALIDATE_URL}" title="{PHP.L.Putinvalidationqueue}" class="btn btn-small"><i class="ic-eye"></i></a>
						<!-- END: PAGE_UNVALIDATE -->

						<a href="{PAGE_EDIT_URL}" title="{PHP.L.Edit}" class="btn btn-small"><i class="ic-edit"></i></a>
						<a href="{PAGE_CLONE_URL}" title="{PHP.L.Clone}" class="btn btn-small"><i class="ic-copy"></i></a>
						<a href="{PAGE_DELETE_URL}" title="{PHP.L.Delete}" onclick="return sedjs.confirmact('Вы подтверждаете удаление?');" class="btn btn-small"><i class="ic-trash"></i></a>

						<!-- END: ADMIN_ACTIONS -->

					</div>

				</div>

				<!-- END: PAGE_LIST -->

			</div>

		</div>

		<!-- BEGIN: PAGE_PAGINATION_BM -->

		<div class="paging">
			<ul class="pagination">
				<li class="prev">{PAGE_PAGEPREV}</li>
				{PAGE_PAGINATION}
				<li class="next">{PAGE_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: PAGE_PAGINATION_BM -->

		<script>
			function confirmDelete() {
				if (confirm("Вы подтверждаете удаление?")) {
					return true;
				} else {
					return false;
				}
			}
		</script>

	</div>

</div>

<!-- END: ADMIN_PAGE_MANAGER -->