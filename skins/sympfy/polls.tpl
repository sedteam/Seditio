<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		{POLLS_BREADCRUMBS}

		<div class="section-title">
			<h1>{POLLS_TITLE}</h1>
		</div>

		<div class="section-desc"></div>

		<div class="section-body">

			{POLL_VIEW}

			<!-- BEGIN: POLLS_VIEWALL -->

			<div class="table-cells polls-table">

				<!-- BEGIN: POLLS_LIST -->

				<div class="table-tr">

					<div class="table-td poll-date" style="width:130px;">
						{POLLS_LIST_DATE}
					</div>

					<div class="table-td poll-icon" style="width:30px;">
						<a href="{POLLS_LIST_URL}"><img src="system/img/admin/polls.png" alt="" /></a>
					</div>

					<div class="table-td poll-title">
						{POLLS_LIST_TEXT}
					</div>

				</div>

				<!-- END: POLLS_LIST -->

			</div>

			<!-- BEGIN: POLLS_NONE -->
			{PHP.L.none}
			<!-- END: POLLS_NONE -->

			<!-- END: POLLS_VIEWALL -->

		</div>

	</div>

</main>

<!-- END: MAIN -->