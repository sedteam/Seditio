<!-- BEGIN: ADMIN_PM -->

<div class="title">
	<span><i class="ic-pm"></i></span>
	<h2>{ADMIN_PM_TITLE}</h2>
</div>

<!-- BEGIN: PM_BUTTONS -->

<ul class="shortcut-buttons-set">

	<!-- BEGIN: PM_BUTTONS_CONFIG -->
	<li><a class="shortcut-button" href="{BUTTON_PM_CONFIG_URL}"><span>
				<i class="ic-settings ic-3x"></i><br />
				{PHP.L.Configuration}
			</span></a></li>
	<!-- END: PM_BUTTONS_CONFIG -->

</ul>

<div class="clear"></div>

<!-- END: PM_BUTTONS -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Statistics}</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped">
			<div class="table-body">
				<div class="table-row">
					<div class="table-td">{PHP.L.adm_pm_totaldb}</div>
					<div class="table-td">{PM_TOTALMP_DB}</div>
				</div>
				<div class="table-row">
					<div class="table-td">{PHP.L.adm_pm_totalsent}</div>
					<div class="table-td">{PM_TOTALMP_SEND}</div>
				</div>
			</div>
		</div>

	</div>

</div>

<!-- END: ADMIN_PM -->