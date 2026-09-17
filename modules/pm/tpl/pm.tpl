<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PM_SHORTTITLE}</h1>

			<div class="section-desc">
				{PM_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<div class="centered">
				{PM_INBOX} &nbsp; &nbsp; {PM_ARCHIVES} &nbsp; &nbsp; {PM_SENTBOX} &nbsp; &nbsp; {PM_SENDNEWPM}
			</div>


			<div class="table cells striped resp-table">

				<!-- BEGIN: PM_TITLE -->

				<div class="table-head resp-table-head">

					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left" style="width:16px;">{PHP.L.pm_state}</div>
						<div class="table-th coltop text-center" style="width:10%;">{PHP.L.pm_sender}</div>
						<div class="table-th coltop text-left">{PHP.L.pm_subjectclick}</div>
						<div class="table-th coltop text-center" style="width:176px;">{PHP.L.pm_date}</div>
						<div class="table-th coltop text-center" style="width:80px;"></div>
					</div>

				</div>

				<!-- END: PM_TITLE -->

				<!-- BEGIN: PM_TITLE_SENTBOX -->

				<div class="table-head resp-table-head">

					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left" style="width:16px;">{PHP.L.pm_state}</div>
						<div class="table-th coltop text-center" style="width:10%;">{PHP.L.pm_recipient}</div>
						<div class="table-th coltop text-left">{PHP.L.pm_subjectclick}</div>
						<div class="table-th coltop text-center" style="width:176px;">{PHP.L.pm_date}</div>
						<div class="table-th coltop text-center" style="width:80px;"></div>
					</div>

				</div>

				<!-- END: PM_TITLE_SENTBOX -->

				<div class="table-body resp-table-body">

					<!-- BEGIN: PM_ROW -->

					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.pm_state}">
							{PM_ROW_ICON_STATUS}
						</div>
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.pm_recipient}">
							{PM_ROW_FROMORTOUSER}
						</div>
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.pm_subjectclick}">
							<strong>{PM_ROW_TITLE}</strong>
						</div>
						<div class="table-td text-center resp-table-td" data-label="{PHP.L.pm_date}">
							{PM_ROW_DATE}
						</div>
						<div class="table-td text-center resp-table-td" data-label="{PHP.L.pm_date}">
							{PM_ROW_ICON_ACTION}
						</div>

					</div>

					<!-- END: PM_ROW -->

				</div>

			</div>

			<!-- BEGIN: PM_ROW_EMPTY -->

			<div class="pm-empty">
				{PHP.L.pm_nomessages}
			</div>

			<!-- END: PM_ROW_EMPTY -->

			<div class="pagination-box">

				<ul class="pagination">
					<li class="page-item">{PM_TOP_PAGEPREV}</li>
					{PM_TOP_PAGINATION}
					<li class="page-item">{PM_TOP_PAGENEXT}</li>
				</ul>

			</div>

			<!-- BEGIN: PM_FOOTER -->

			<!-- END: PM_FOOTER -->

			<!-- BEGIN: PM_DETAILS -->

			<div class="table-cells table-with-border">

				<div class="table-tr">
					<div class="table-td" style="width:200px;">{PHP.L.pm_subject} </div>
					<div class="table-td"><strong>{PM_ROW_TITLE}</strong></div>
				</div>
				<div class="table-tr">
					<div class="table-td">{PHP.L.pm_sender}</div>
					<div class="table-td">{PM_ROW_FROMUSER}</div>
				</div>
				<div class="table-tr">
					<div class="table-td">{PHP.L.pm_recipient}</div>
					<div class="table-td">{PM_ROW_TOUSER}</div>
				</div>
				<div class="table-tr">
					<div class="table-td">{PHP.L.pm_date}</div>
					<div class="table-td">{PM_ROW_DATE}</div>
				</div>
				<div class="table-tr">
					<div class="table-td">{PHP.L.pm_message}</div>
					<div class="table-td">
						<div class="pm-message">
							{PM_ROW_TEXT}
						</div>
					</div>
				</div>
				<div class="table-tr">
					<div class="table-td">{PHP.L.pm_action}</div>
					<div class="table-td">{PM_ROW_ICON_ACTION}</div>
				</div>

			</div>



			<!-- END: PM_DETAILS -->

			<div class="centered">

				<img src="skins/{PHP.skin}/img/system/icon-pm-new.gif" alt="" />: {PHP.L.pm_newmessage} &nbsp; &nbsp;
				<img src="skins/{PHP.skin}/img/system/icon-pm.gif" alt="" />: {PHP.L.pm_message} &nbsp; &nbsp;
				<img src="skins/{PHP.skin}/img/system/icon-pm-reply.gif" alt="" />: {PHP.L.pm_reply} &nbsp; &nbsp;
				<img src="skins/{PHP.skin}/img/system/icon-pm-archive.gif" alt="" />: {PHP.L.pm_sendtoarchives} &nbsp; &nbsp;
				<img src="skins/{PHP.skin}/img/system/icon-pm-trashcan.gif" alt="" />: {PHP.L.pm_delete}

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->