<!-- BEGIN: MAIN -->

{POLLS_HEADER1}

<link href="skins/{PHP.skin}/{PHP.skin}.css" type="text/css" rel="stylesheet">

{POLLS_HEADER2}

<div class="block">

<!-- BEGIN: POLLS_VIEW -->

<div id="title">

  <h2>{POLLS_TITLE}</h2>

</div>

<div id="page">

	{POLLS_RESULTS}

	<p>
		{POLLS_VOTERS} {PHP.skinlang.polls.voterssince} {POLLS_SINCE}<br />
		{PHP.skinlang.polls.Comments} {POLLS_COMMENTS}{POLLS_COMMENTS_DISPLAY}
	</p>

</div>

<!-- END: POLLS_VIEW -->

<!-- BEGIN: POLLS_VIEWALL -->

<div id="title">
	<h2>{PHP.skinlang.polls.Allpolls}</h2>
</div>

<div id="page">

	{POLLS_LIST}

</div>

<!-- END: POLLS_VIEWALL -->


<!-- BEGIN: POLLS_EXTRA -->

<div class="block">

	{POLLS_EXTRATEXT}<br />{POLLS_VIEWALL}

</div>

<!-- END: POLLS_EXTRA -->

</div>

{POLLS_FOOTER}

<!-- END: MAIN -->