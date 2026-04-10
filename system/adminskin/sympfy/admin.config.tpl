<!-- BEGIN: ADMIN_CONFIG -->

<div class="title">
	<span><i class="ic-settings"></i></span>
	<h2>{ADMIN_CONFIG_TITLE}</h2>
</div>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Configuration} {ADMIN_CONFIG_TITLE}</h3>
	</div>

	<div class="content-box-content content-table">

		<form id="saveconfig" action="{ADMIN_CONFIG_FORM_SEND}" method="post">

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left" style="width:400px;">{PHP.L.Configname}</div>
						<div class="table-th coltop text-left">{PHP.L.Configuration}</div>
						<div class="table-th coltop" style="width:85px;">{PHP.L.Reset}</div>
					</div>
				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: CONFIG_LIST -->

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td config-title">
							{CONFIG_LIST_TITLE}
						</div>
						<div class="table-td text-left resp-table-td config-field">
							<div class="field">{CONFIG_LIST_FIELD}</div>
							<div class="descr">{CONFIG_LIST_DESC}</div>
						</div>
						<div class="table-td text-center resp-table-td config-reset">
							<a href="{CONFIG_LIST_RESET_URL}" title="{PHP.L.Reset}" class="btn btn-small"><i class="ic-refresh"></i></a>
						</div>
					</div>

					<!-- END: CONFIG_LIST -->

				</div>

			</div>

			<div class="table-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>

		</form>

	</div>

</div>

<!-- BEGIN: CONFIG_MISSING_SECTION -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{CONFIG_MISSING_SECTION_TITLE}</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="descr" style="padding:12px;">{PHP.L.adm_config_missing_descr}</div>

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left" style="width:400px;">{PHP.L.Configname}</div>
					<div class="table-th coltop text-left">{PHP.L.adm_config_missing_action}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: CONFIG_MISSING_LIST -->

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td config-title">
						{CONFIG_MISSING_TITLE}<br /><span class="descr">{CONFIG_MISSING_NAME}</span>
					</div>
					<div class="table-td text-left resp-table-td">
						<a href="{CONFIG_MISSING_ADD_URL}" class="btn">{CONFIG_MISSING_ADD_LABEL}</a>
					</div>
				</div>

				<!-- END: CONFIG_MISSING_LIST -->

			</div>

		</div>

	</div>

</div>

<!-- END: CONFIG_MISSING_SECTION -->

<!-- BEGIN: HELP -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Help}</h3>
	</div>

	<div class="content-box-content">
		<p>{HELP_CONFIG}</p>
	</div>

</div>

<!-- END: HELP -->

<!-- END: ADMIN_CONFIG -->