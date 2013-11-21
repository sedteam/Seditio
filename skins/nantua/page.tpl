<!-- BEGIN: MAIN -->

<div id="title_back">

<div id="title">

	{PAGE_TITLE}

</div>

<div id="subtitle">

	{PAGE_DESC}<br />
	{PHP.skinlang.page.Submittedby} {PAGE_OWNER} &nbsp; {PHP.skinlang.page.Date} {PAGE_DATE}<br />

	<!-- BEGIN: PAGE_ADMIN -->

	{PAGE_ADMIN_UNVALIDATE} &nbsp; {PAGE_ADMIN_EDIT} &nbsp; ({PAGE_ADMIN_COUNT})<br />

	<!-- END: PAGE_ADMIN -->

</div>

</div>

<div id="main">

	{PAGE_TEXT}

	<!-- BEGIN: PAGE_MULTI -->

		<div class="paging">

			{PAGE_MULTI_TABNAV}<br />
			{PAGE_MULTI_PREV} {PAGE_MULTI_SELECT} {PAGE_MULTI_NEXT}

		</div>

		<div class="block">
			<h5>{PHP.skinlang.page.Summary}</h5>

			{PAGE_MULTI_TABTITLES}

		</div>

	<!-- END: PAGE_MULTI -->

	<!-- BEGIN: PAGE_FILE -->

		<div class="download">

			<a href="{PAGE_FILE_URL}">Download : {PAGE_SHORTTITLE} {PAGE_FILE_ICON}</a><br/>
			Size: {PAGE_FILE_SIZE}KB, downloaded {PAGE_FILE_COUNT} times

		</div>

	<!-- END: PAGE_FILE -->

	<h4 style="margin-top:48px;">{PHP.skinlang.page.Comments} {PAGE_COMMENTS}</h4>
	{PAGE_COMMENTS_DISPLAY}
  <h4 style="margin-top:48px;">{PHP.skinlang.page.Ratings} {PAGE_RATINGS}</h4>
  {PAGE_RATINGS_DISPLAY}

</div>

<!-- END: MAIN -->