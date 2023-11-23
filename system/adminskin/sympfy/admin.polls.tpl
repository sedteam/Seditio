<!-- BEGIN: ADMIN_POLLS -->

<div class="title">
	<span><i class="ic-polls"></i></span>
	<h2>{ADMIN_POLLS_TITLE}</h2>
</div>

<!-- BEGIN: POLLS -->

<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.Polls}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.Polls}">{PHP.L.Polls}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.addnewentry}">{PHP.L.addnewentry}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<div class="tab-content default-tab" id="tab1">

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left">{PHP.L.Poll} {PHP.L.adm_clicktoedit}</div>
						<div class="table-th coltop text-left" style="width:130px;">{PHP.L.Date}</div>
						<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Votes}</div>
						<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Delete}</div>
						<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Reset}</div>
						<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Bump}</div>
						<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Open}</div>
					</div>
				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: POLLS_LIST -->

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td poll-title" data-label="{PHP.L.Poll} {PHP.L.adm_clicktoedit}">
							<a href="{POLLS_LIST_OPTIONS_URL}">{POLLS_LIST_POLLTEXT}</a>
						</div>
						<div class="table-td text-left resp-table-td poll-date" data-label="{PHP.L.Date}">
							{POLLS_LIST_DATE}
						</div>
						<div class="table-td text-center resp-table-td poll-votes" data-label="{PHP.L.Votes}">
							{POLLS_LIST_TOTALVOTES}
						</div>
						<div class="table-td text-center resp-table-td poll-delete" data-label="{PHP.L.Delete}">
							<a href="{POLLS_LIST_DELETE_URL}" title="{PHP.L.Delete}"><i class="ic-trash"></i></a>
						</div>
						<div class="table-td text-center resp-table-td poll-reset" data-label="{PHP.L.Reset}">
							<a href="{POLLS_LIST_RESET_URL}" title="{PHP.L.Reset}"><i class="ic-refresh"></i></a>
						</div>
						<div class="table-td text-center resp-table-td poll-bump" data-label="{PHP.L.Bump}">
							<a href="{POLLS_LIST_BUMP_URL}" title="{PHP.L.Bump}"><i class="ic-thumb-up"></i></a>
						</div>
						<div class="table-td text-center resp-table-td poll-open" data-label="{PHP.L.Open}">
							<a href="{POLLS_LIST_OPEN_URL}"><i class="ic-arrow-right"></i></a>
						</div>
					</div>

					<!-- END: POLLS_LIST -->

				</div>

			</div>

			<div style="padding:10px;">
				{PHP.L.Total} : {POLLS_TOTAL}
			</div>

		</div>

		<div class="tab-content" id="tab2">

			<form id="addpoll" action="{POLL_ADD_SEND}" method="post">

				<div class="table cells striped resp-table">

					<div class="table-body resp-table-body">

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td poll-title" style="width:180px;">{PHP.L.adm_poll_title} :</div>
							<div class="table-td text-left resp-table-td poll-filed">
								{POLL_ADD_TEXT}
								<div class="descr">{PHP.L.adm_required}</div>
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

</div>

<!-- END: POLLS -->

<!-- BEGIN: POLL_EDIT -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.editdeleteentries}: {PHP.L.Poll} #{POLL_EDIT_ID}</h3>
	</div>

	<div class="content-box-content content-table">

		<form id="pollchgtitle" action="{POLL_EDIT_SEND}" method="post">

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left">{PHP.L.adm_poll_title}</div>
						<div class="table-th coltop text-left">{PHP.L.Open}</div>
						<div class="table-th coltop text-left">{PHP.L.Update}</div>
					</div>
				</div>

				<div class="table-body resp-table-body">

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td poll-field" data-label="{PHP.L.adm_poll_title}">
							{POLL_EDIT_TEXT}
						</div>
						<div class="table-td text-left resp-table-td poll-edit-open" data-label="{PHP.L.Open}">
							<a href="javascript:sedjs.polls('{POLL_EDIT_ID}')"><i class="ic-arrow-right"></i></a>
						</div>
						<div class="table-td text-left resp-table-td poll-update" style="width:120px;">
							<button type="submit" class="submit btn" title="{PHP.L.Update}">{PHP.L.Update}</button>
						</div>
					</div>

				</div>

			</div>

			<div style="padding:10px;">
				{PHP.L.Date} : {POLL_EDIT_CREATION_DATE} GMT
			</div>

		</form>

	</div>

</div>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Poll} #{POLL_EDIT_ID} : Options</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">{PHP.L.Delete}</div>
					<div class="table-th coltop text-left">#</div>
					<div class="table-th coltop text-left">{PHP.L.Option}</div>
					<div class="table-th coltop text-left">{PHP.L.Update}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: OPTIONS_LIST -->

				<form class="table-row resp-table-row" id="savepollopt" action="{POLL_EDIT_OPTIONS_SEND}" method="post">

					<div class="table-td text-left resp-table-td poll-actions" style="width:30px;">
						<a href="{POLL_EDIT_OPTIONS_DELETE_URL}" title="{PHP.L.Delete}"><i class="ic-trash"></i></a>
					</div>
					<div class="table-td text-left resp-table-td" style="width:30px;" data-label="#">
						{POLL_EDIT_OPTIONS_ID}
					</div>
					<div class="table-td text-left resp-table-td poll-field" data-label="{PHP.L.Option}">
						{POLL_EDIT_OPTIONS_TEXT}
					</div>
					<div class="table-td text-left resp-table-td poll-update" style="width:120px;">
						<button type="submit" class="submit btn">{PHP.L.Update}</button>
					</div>

				</form>

				<!-- END: OPTIONS_LIST -->

			</div>

		</div>

	</div>

</div>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.addnewentry} : Option</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">{PHP.L.Option}</div>
					<div class="table-th coltop text-left"></div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<form class="table-row resp-table-row" id="addpollopt" action="{POLL_OPTIONS_ADD_SEND}" method="post">

					<div class="table-td text-left resp-table-td poll-field" data-label="{PHP.L.Option}">
						{POLL_OPTIONS_ADD_TEXT}
					</div>
					<div class="table-td text-left resp-table-td poll-update" style="width:120px;">
						<button type="submit" class="submit btn">{PHP.L.Add}</button>
					</div>

				</form>

			</div>

		</div>

	</div>

</div>

<!-- END: POLL_EDIT -->

<!-- END: ADMIN_POLLS -->