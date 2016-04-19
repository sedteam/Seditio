<!-- BEGIN: MAIN -->

<div id="title">
  <h2>{FORUMS_NEWTOPIC_PAGETITLE}</h2>
</div>

<div id="subtitle">
	{FORUMS_NEWTOPIC_SUBTITLE}
</div>

<div id="page">

<!-- BEGIN: FORUMS_NEWTOPIC_ERROR -->

<div class="error">

		{FORUMS_NEWTOPIC_ERROR_BODY}

</div>

<!-- END: FORUMS_NEWTOPIC_ERROR -->

<form action="{FORUMS_NEWTOPIC_SEND}" method="post" name="newtopic">

<table class="cells striped">

	<tr>
		<td>{PHP.skinlang.forumsnewtopic.Title} {FORUMS_NEWTOPIC_TITLE}</td>
	</tr>

	<tr>
		<td>{PHP.skinlang.forumsnewtopic.Desc} {FORUMS_NEWTOPIC_DESC}</td>
	</tr>

	<!-- BEGIN: PRIVATE -->

	<tr>
		<td>
		{PHP.skinlang.forumsnewtopic.privatetopic} {FORUMS_NEWTOPIC_ISPRIVATE}<br />
		{PHP.skinlang.forumsnewtopic.privatetopic2}
		</td>
	</tr>

	<!-- END: PRIVATE -->

	<tr>
		<td>
		{FORUMS_NEWTOPIC_TEXT}
		</td>
	</tr>

	<tr>
		<td style="text-align:center;">
		<input type="submit" class="submit btn btn-big" value="{PHP.skinlang.forumsnewtopic.Submit}">
		</td>
	</tr>

</table>

</form>

</div>

<!-- END: MAIN -->
