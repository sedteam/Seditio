<!-- BEGIN: ADMIN_PM -->

<div class="title">
	<span><i class="ic-pm"></i></span><h2>{ADMIN_PM_TITLE}</h2>
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

	<table class="cells striped">
		<tr>
			<td>{PHP.L.adm_pm_totaldb}</td>
			<td>{PM_TOTALMP_DB}</td>
		</tr>
		<tr>
			<td>{PHP.L.adm_pm_totalsent}</td>
			<td>{PM_TOTALMP_SEND}</td>
		</tr>
	</table>

</div>

</div>

<!-- END: ADMIN_PM -->
