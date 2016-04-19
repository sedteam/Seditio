<!-- BEGIN: MAIN -->

<div id="title">
  <h2>{FORUMS_EDITPOST_PAGETITLE}</h2>
</div>

<div id="subtitle">
	{FORUMS_EDITPOST_SUBTITLE}
</div>

<div id="page">

<form action="{FORUMS_EDITPOST_SEND}" method="post" name="editpost">

<!-- BEGIN: FORUMS_EDITPOST_ERROR -->

<div class="error">

	{FORUMS_POSTS_EDITPOST_ERROR_BODY}

</div>

<!-- END: FORUMS_EDITPOST_ERROR -->

<table class="cells striped">

  <!-- BEGIN: FORUMS_EDITPOST_FIRST -->

	<tr>
		<td>{PHP.skinlang.forumsnewtopic.Title} {FORUMS_EDITPOST_TITLE}</td>
	</tr>

	<tr>
		<td>{PHP.skinlang.forumsnewtopic.Desc} {FORUMS_EDITPOST_DESC}</td>
	</tr>

  <!-- END: FORUMS_EDITPOST_FIRST -->

	<tr>
		<td>
		{FORUMS_EDITPOST_TEXT}
		</td>
	</tr>

	<tr>
		<td class="valid">
		<input type="submit" class="submit btn btn-big" value="{PHP.skinlang.forumseditpost.Update}">
		</td>
	</tr>

</table>

</form>

</div>

<!-- END: MAIN -->