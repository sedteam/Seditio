<!-- BEGIN: MAIN -->

<!-- BEGIN: POLLS_STANDALONE_HEADER -->

{POLLS_STANDALONE_HEADER1}
<link href="skins/{PHP.skin}/css/framework.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/fonts.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/plugins.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/cms.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/sympfy.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/responsive.css" type="text/css" rel="stylesheet" />
{POLLS_STANDALONE_HEADER2}

<!-- END: POLLS_STANDALONE_HEADER -->

<main id="standalone">

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

</main>

<!-- BEGIN: POLLS_STANDALONE_FOOTER -->

{POLLS_STANDALONE_FOOTER1}
<script src="skins/{PHP.skin}/js/jquery.min.js"></script>
<script src="skins/{PHP.skin}/js/jquery.plugins.min.js"></script>
<script src="skins/{PHP.skin}/js/app.js"></script>
{POLLS_STANDALONE_FOOTER2}

<!-- END: POLLS_STANDALONE_FOOTER -->

<!-- END: MAIN -->