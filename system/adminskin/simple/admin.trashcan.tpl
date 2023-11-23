<!-- BEGIN: ADMIN_TRASHCAN -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.Trashcan}</h3>
		<div class="content-box-header-right">
			<div class="clear"></div>
		</div>
	</div>
	<div class="content-box-content">

		<p>
			<a href="{TRASHCAN_CONFIGURATION_URL}">{PHP.L.Configuration}</a> |
			<a href="{TRASHCAN_WIPEALL_URL}">{PHP.L.Wipeall}</a>
		</p>

		<table class="cells striped">
			<thead>
				<tr>
					<th class="coltop" style="width:144px;">{PHP.L.Date}</th>
					<th class="coltop" style="width:144px;">{PHP.L.Type}</th>
					<th class="coltop">{PHP.L.Title}</th>
					<th class="coltop" style="width:96px;">{PHP.L.adm_setby}</th>
					<th class="coltop" style="width:30px;">{PHP.L.Wipe}</th>
					<th class="coltop" style="width:30px;">{PHP.L.Restore}</th>
				</tr>
			</thead>

			<!-- BEGIN: TRASHCAN_LIST -->

			<tr>
				<td>{TRASHCAN_LIST_DATE}</td>
				<td>{TRASHCAN_LIST_TYPE}</td>
				<td>{TRASHCAN_LIST_TITLE}</td>
				<td>{TRASHCAN_LIST_TRASHEDBY}</td>
				<td>[<a href="{TRASHCAN_LIST_WIPE_URL}">-</a>]</td>
				<td>[<a href="{TRASHCAN_LIST_RESTORE_URL}">+</a>]</td>
			</tr>

			<!-- END: TRASHCAN_LIST -->

		</table>

	</div>
</div>

<!-- END: ADMIN_TRASHCAN -->