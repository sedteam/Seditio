<!-- BEGIN: ADMIN_CONFIG -->

<div class="content-box">

	<div class="content-box-header">
		<h3><i class="ic-settings"></i> {ADMIN_CONFIG_ADMINLEGEND}</h3>
	</div>

	<div class="content-box-content">

		<form id="saveconfig" action="{ADMIN_CONFIG_FORM_SEND}" method="post">

			<div class="table cells striped">

				<div class="table-head">
					<div class="table-row">
						<div class="table-th coltop text-left" style="width:400px;">{PHP.L.Configname}</div>
						<div class="table-th coltop text-left">{PHP.L.Configuration}</div>
						<div class="table-th coltop" style="width:85px;">{PHP.L.Reset}</div>
					</div>
				</div>

				<div class="table-body">

					<!-- BEGIN: CONFIG_LIST -->

					<div class="table-row">
						<div class="table-td text-left">
							{CONFIG_LIST_TITLE}
						</div>
						<div class="table-td text-left config-field">
							<div class="field">{CONFIG_LIST_FIELD}</div>
							<div class="descr">{CONFIG_LIST_DESC}</div>
						</div>
						<div class="table-td text-center">
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