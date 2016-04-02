<!-- BEGIN: MAIN -->

<div id="title">

	<h2>{PAGE_TITLE}</h2>

</div>

<div id="subtitle">
	{PAGE_DESC}
	<div class="clear">
	{PHP.skinlang.page.Submittedby} {PAGE_OWNER} &nbsp; {PHP.skinlang.page.Date} {PAGE_DATE}<br />

	<!-- BEGIN: PAGE_ADMIN -->

	{PAGE_ADMIN_UNVALIDATE} &nbsp; {PAGE_ADMIN_EDIT} &nbsp; {PAGE_ADMIN_CLONE} &nbsp; ({PAGE_ADMIN_COUNT})<br />

	<!-- END: PAGE_ADMIN -->
	</div>
</div>

<div id="page">
               
	{PAGE_TEXT}

	<!-- BEGIN: PAGE_MULTI -->

		<div class="paging">
		   <ul class="pagination">
			<li class="prev">{PAGE_MULTI_PREV}</li>
			{PAGE_MULTI_TABNAV}
			<li class="next">{PAGE_MULTI_NEXT}</li>
		  </ul>
		  {PAGE_MULTI_SELECT} 
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

	<div class="headline no-margin"><h4>{PHP.skinlang.page.Comments} <span class="comments-amount">{PAGE_COMMENTS}</span></h4></div>
	{PAGE_COMMENTS_DISPLAY}
  
  
	<div class="headline no-margin"><h4>{PHP.skinlang.page.Ratings} {PAGE_RATINGS}</h4></div>
	{PAGE_RATINGS_DISPLAY}

</div>

<!-- END: MAIN -->
