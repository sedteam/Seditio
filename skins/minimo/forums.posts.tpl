<!-- BEGIN: MAIN -->

<div id="title">
  <h2>{FORUMS_POSTS_PAGETITLE}</h2>
	<span class="desc">{FORUMS_POSTS_TOPICDESC}</span>
</div>

<div id="bolded-line"></div>

<div id="subtitle">
	{FORUMS_POSTS_SUBTITLE}
</div>

<div id="page">

<!-- BEGIN: FORUMS_POSTS_TOPICPRIVATE -->

<div class="error">

	{PHP.skinlang.forumspost.privatetopic}

</div>

<!-- END: FORUMS_POSTS_TOPICPRIVATE -->

<!-- BEGIN: FORUMS_POSTS_PAGINATION_TP -->
<div class="paging">
<ul class="pagination">
  <li class="prev">{FORUMS_POSTS_PAGEPREV}</li>
  {FORUMS_POSTS_PAGES}
  <li class="next">{FORUMS_POSTS_PAGENEXT}</li>
</ul>
</div>
<!-- END: FORUMS_POSTS_PAGINATION_TP -->

<table class="cells">

	<tr>
		<td class="coltop" style="width:160px;">{PHP.skinlang.forumspost.Author}</td>
		<td class="coltop">{PHP.skinlang.forumspost.Message}</td>
	</tr>

	<!-- BEGIN: FORUMS_POSTS_ROW -->
  
	<tr>
		<td class="{FORUMS_POSTS_ROW_ODDEVEN}">
        <span style="font-size:120%;"><strong>{FORUMS_POSTS_ROW_POSTERNAME}</strong></span>
    </td>
  	<td style="height:16px; max-height:30px; text-align:right;" class="{FORUMS_POSTS_ROW_ODDEVEN}">
      	#{FORUMS_POSTS_ROW_IDURL} &nbsp;
      	{FORUMS_POSTS_ROW_CREATION} &nbsp; 
        {FORUMS_POSTS_ROW_POSTERIP} &nbsp; 
        {FORUMS_POSTS_ROW_ADMIN} &nbsp; 
        {FORUMS_POSTS_ROW_RATE}
  	</td>
	</tr>  
  
	<tr>
		<td class="{FORUMS_POSTS_ROW_ODDEVEN}" style="vertical-align:top;">

			{FORUMS_POSTS_ROW_AVATAR}

			<p>
				{FORUMS_POSTS_ROW_MAINGRP}<br />
				{FORUMS_POSTS_ROW_COUNTRYFLAG} {FORUMS_POSTS_ROW_MAINGRPSTARS}<br />
				<img src="skins/{PHP.skin}/img/online{FORUMS_POSTS_ROW_USERONLINE}.gif" alt="{PHP.skinlang.forumspost.Onlinestatus}">
			</p>

			<p>
				{FORUMS_POSTS_ROW_POSTCOUNT} {PHP.skinlang.forumspost.posts}<br />
				{FORUMS_POSTS_ROW_WEBSITE}
			</p>

		</td>

		<td style="padding:8px; height:100%;" class="{FORUMS_POSTS_ROW_ODDEVEN}">
  		<div style="overflow-x:auto; overflow-y:visible; margin-bottom:8px;">
  			{FORUMS_POSTS_ROW_TEXT}
  		  <div class="signature">{FORUMS_POSTS_ROW_USERTEXT} </div>
  		</div>  
  		{FORUMS_POSTS_ROW_UPDATEDBY}
		</td>
	</tr>

	<!-- END: FORUMS_POSTS_ROW -->

</table>

<!-- BEGIN: FORUMS_POSTS_PAGINATION_BM -->
<div class="paging">
<ul class="pagination">
  <li class="prev">{FORUMS_POSTS_PAGEPREV}</li>
  {FORUMS_POSTS_PAGES}
  <li class="next">{FORUMS_POSTS_PAGENEXT}</li>
</ul>
</div>
<!-- END: FORUMS_POSTS_PAGINATION_BM -->

<!-- BEGIN: FORUMS_POSTS_TOPICLOCKED -->

<div class="error">

	{FORUMS_POSTS_TOPICLOCKED_BODY}

</div>

<!-- END: FORUMS_POSTS_TOPICLOCKED -->

<!-- BEGIN: FORUMS_POSTS_ANTIBUMP -->

<div>

	{FORUMS_POSTS_ANTIBUMP_BODY}

</div>

<!-- END: FORUMS_POSTS_ANTIBUMP -->

<!-- BEGIN: FORUMS_POSTS_NEWPOST -->

<form action="{FORUMS_POSTS_NEWPOST_SEND}" method="post" name="newpost">

	{FORUMS_POSTS_NEWPOST_TEXT}

	<div class="valid">
	<input type="submit" class="submit btn btn-big" value="{PHP.skinlang.forumspost.Reply}">
	</div>

</form>

<!-- END: FORUMS_POSTS_NEWPOST -->

<div class="paging">

{FORUMS_POSTS_JUMPBOX}

</div>

</div>

<!-- END: MAIN -->