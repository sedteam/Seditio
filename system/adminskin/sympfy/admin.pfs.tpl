<!-- BEGIN: ADMIN_PFS -->

<div class="title">
	<span><i class="ic-pfs"></i></span>
	<h2>{ADMIN_PFS_TITLE}</h2>
</div>

<!-- BEGIN: PFS_BUTTONS -->

<ul class="shortcut-buttons-set">

	<li><a class="shortcut-button" href="{BUTTON_PFS_CONFIG_URL}"><span>
				<i class="ic-settings ic-3x"></i><br />
				{PHP.L.Configuration} <br />"{PHP.L.core_pfs}"
			</span></a></li>

</ul>

<div class="clear"></div>

<!-- END: PFS_BUTTONS -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.adm_allpfs}</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped">

			<div class="table-head">
				<div class="table-row">
					<div class="table-th coltop text-center">{PHP.L.User}</div>
					<div class="table-th coltop text-center">{PHP.L.Files}</div>
					<div class="table-th coltop text-center">{PHP.L.Edit}</div>
				</div>
			</div>

			<div class="table-body">

				<!-- BEGIN: PFS_LIST -->
				<div class="table-row">
					<div class="table-td text-center">{PFS_LIST_USER}</div>
					<div class="table-td text-center">{PFS_LIST_COUNTFILES}</div>
					<div class="table-td text-center">{PFS_LIST_EDIT}</div>
				</div>
				<!-- END: PFS_LIST -->

			</div>

		</div>

	</div>
</div>

<!-- END: ADMIN_PFS -->