<!-- BEGIN: ADMIN_TRASHCAN -->

<div class="title">
	<span><i class="ic-trash"></i></span>
	<h2>{ADMIN_TRASHCAN_TITLE}</h2>
</div>

<ul class="arrow_list">
	<li><a href="{TRASHCAN_CONFIGURATION_URL}">{PHP.L.Configuration}</a></li>
	<li><a href="{TRASHCAN_WIPEALL_URL}">{PHP.L.Wipeall}</a></li>
</ul>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Trashcan}</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left" style="width:150px;">{PHP.L.Date}</div>
					<div class="table-th coltop text-left" style="width:120px;">{PHP.L.Type}</div>
					<div class="table-th coltop text-left">{PHP.L.Title}</div>
					<div class="table-th coltop text-left" style="width:120px;">{PHP.L.adm_setby}</div>
					<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Wipe}</div>
					<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Restore}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: TRASHCAN_LIST -->

				<div class="table-row resp-table-row">

					<div class="table-td text-left resp-table-td trashcan-date" data-label="{PHP.L.Date}">{TRASHCAN_LIST_DATE}</div>
					<div class="table-td text-left resp-table-td trashcan-type" data-label="{PHP.L.Type}">{TRASHCAN_LIST_TYPE}</div>
					<div class="table-td text-left resp-table-td trashcan-title" data-label="{PHP.L.Title}">{TRASHCAN_LIST_TITLE}</div>
					<div class="table-td text-left resp-table-td trashcan-trashby" data-label="{PHP.L.adm_setby}">{TRASHCAN_LIST_TRASHEDBY}</div>
					<div class="table-td text-center resp-table-td trashcan-wipe" data-label="{PHP.L.Wipe}"><a href="{TRASHCAN_LIST_WIPE_URL}"><i class="ic-trash"></i></a></div>
					<div class="table-td text-center resp-table-td trashcan-restore" data-label="{PHP.L.Restore}"><a href="{TRASHCAN_LIST_RESTORE_URL}"><i class="ic-plus"></i></a></div>

				</div>

				<!-- END: TRASHCAN_LIST -->

			</div>

		</div>

	</div>

</div>

<!-- END: ADMIN_TRASHCAN -->