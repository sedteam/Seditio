<!-- BEGIN: MAIN -->

{POLLS_STANDALONE_HEADER1}
<link href="skins/{PHP.skin}/css/framework.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/fonts.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/plugins.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/cms.css" type="text/css" rel="stylesheet" />		
<link href="skins/{PHP.skin}/css/sympfy.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/responsive.css" type="text/css" rel="stylesheet" />
{POLLS_STANDALONE_HEADER2}

<main id="standalone">
	
	<!-- BEGIN: POLLS_VIEW -->
	
	<div class="section-title">
		<h1>{POLLS_TITLE}</h1>
		<div class="section-desc"></div>		
	</div>

	<div class="section-body">

		{POLLS_RESULTS}
		<p>
			{POLLS_VOTERS} {PHP.skinlang.polls.voterssince} {POLLS_SINCE}<br />
			{PHP.skinlang.polls.Comments} {POLLS_COMMENTS}{POLLS_COMMENTS_DISPLAY}
		</p>		
	</div>
	
	<!-- END: POLLS_VIEW -->
	
	
	<!-- BEGIN: POLLS_VIEWALL -->
	
	<div class="section-title">
		<h1>{PHP.skinlang.polls.Allpolls}</h1>
		<div class="section-desc"></div>		
	</div>

	<div class="section-body">	

		{POLLS_LIST}

	</div>

	<!-- END: POLLS_VIEWALL -->


	<!-- BEGIN: POLLS_EXTRA -->

	<div class="section-block">

		{POLLS_EXTRATEXT}<br />{POLLS_VIEWALL}

	</div>

	<!-- END: POLLS_EXTRA -->	

</main>

{POLLS_STANDALONE_FOOTER}

<!-- END: MAIN -->