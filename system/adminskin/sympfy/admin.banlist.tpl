<!-- BEGIN: ADMIN_BANLIST -->

<div class="title">
	<span><i class="ic-banlist"></i></span>
	<h2>{ADMIN_BANLIST_TITLE}</h2>
</div>

<div class="content-box">

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-center" style="width:20px">{PHP.L.Delete}</div>
					<div class="table-th coltop text-left">{PHP.L.Until}</div>
					<div class="table-th coltop text-left">{PHP.L.adm_ipmask}</div>
					<div class="table-th coltop text-left">{PHP.L.adm_emailmask}</div>
					<div class="table-th coltop text-left">{PHP.L.Reason}</div>
					<div class="table-th coltop text-center">{PHP.L.Update}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: BANLIST_EDIT_LIST -->

				<form class="table-row resp-table-row" id="savebanlist_{BANLIST_EDIT_ID}" action="{BANLIST_EDIT_SEND_URL}" method="post">

					<div class="table-td text-center resp-table-td banlist-action">
						<a href="{BANLIST_EDIT_DELETE_URL}" class="btn btn-small"><i class="ic-trash"></i></a>
					</div>

					<div class="table-td text-left resp-table-td banlist-until" data-label="{PHP.L.Until}">
						{BANLIST_EDIT_EXPIRE}
					</div>

					<div class="table-td text-left resp-table-td banlist-ipmask" data-label="{PHP.L.adm_ipmask}">
						{BANLIST_EDIT_IP}
					</div>

					<div class="table-td text-left resp-table-td banlist-emailmask" data-label="{PHP.L.adm_emailmask}">
						{BANLIST_EDIT_EMAIL_MASK}
					</div>

					<div class="table-td text-left resp-table-td banlist-reason" data-label="{PHP.L.Reason}">
						{BANLIST_EDIT_REASON}
					</div>

					<div class="table-td text-center resp-table-td banlist-submit">
						<button type="submit" class="submit btn">{PHP.L.Update}</button>
					</div>

				</form>

				<!-- END: BANLIST_EDIT_LIST -->

			</div>

		</div>

	</div>

</div>

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.addnewentry}</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<form id="addbanlist" action="{BANLIST_ADD_SEND_URL}" method="post">

			<div class="table cells striped resp-table">

				<div class="table-body resp-table-body">

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td" style="width:220px;">
							{PHP.L.Duration}
						</div>
						<div class="table-td text-left resp-table-td">
							{BANLIST_ADD_NEXPIRE}
						</div>
					</div>
					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.Ipmask} :
						</div>
						<div class="table-td text-left resp-table-td">
							{BANLIST_ADD_IP}
						</div>
					</div>
					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.Emailmask} :
						</div>
						<div class="table-td text-left resp-table-td">
							{BANLIST_ADD_EMAIL_MASK}
						</div>
					</div>
					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.Reason} :
						</div>
						<div class="table-td text-left resp-table-td">
							{BANLIST_ADD_REASON}
						</div>
					</div>

				</div>

			</div>

			<div class="table-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Add}</button>
			</div>

		</form>

	</div>

</div>

<!-- END: ADMIN_BANLIST -->