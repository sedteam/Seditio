<!-- BEGIN: MAIN -->

<div id="title_back">

<div id="title">

	{GALLERY_HOME_TITLE}

</div>

<div id="subtitle">

	{GALLERY_HOME_SUBTITLE}

</div>

</div>

<div id="main">
 
	<!-- BEGIN: USERS -->

	<a href="{GALLERY_HOME_USERS_ROW_URL}">{GALLERY_HOME_USERS_ROW_AUTHOR} ({GALLERY_HOME_USERS_ROW_COUNT})</a>

	<!-- END: USERS -->

	<!-- BEGIN: GALLERIES -->

	<table class="flat">

		<!-- BEGIN: ROW -->

		{GALLERY_HOME_GALLERIES_ROW_COND1}

		<td style="text-align:center; vertical-align:top; padding:8px;">
			<a href="{GALLERY_HOME_GALLERIES_ROW_URL}"><img src="{GALLERY_HOME_GALLERIES_ROW_SAMPLE}" alt="" /><br />
	<strong>{GALLERY_HOME_GALLERIES_ROW_SHORTTITLE}</a></strong><br />
	
	  By {GALLERY_HOME_GALLERIES_ROW_USER}, 
	  {GALLERY_HOME_GALLERIES_ROW_COUNT} photos<br />
    Updated {GALLERY_HOME_GALLERIES_ROW_UPDATED}
	
		</td>

		{GALLERY_HOME_GALLERIES_ROW_COND2}

	<!-- END: ROW -->

	</table>

	<!-- END: GALLERIES -->

</div>

<!-- END: MAIN -->
