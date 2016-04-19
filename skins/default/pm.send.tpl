<!-- BEGIN: MAIN -->

<div id="title">

  <h2>{PMSEND_TITLE}</h2>

</div>

<div id="subtitle">

	{PMSEND_SUBTITLE}

</div>

<div id="page">

<!-- BEGIN: PMSEND_ERROR -->

<div class="error">

		{PMSEND_ERROR_BODY}

</div>

<!-- END: PMSEND_ERROR -->

<form action="{PMSEND_FORM_SEND}" method="post" name="newlink">

<table class="cells striped">

	<tr>
		<td style="width:176px;">{PHP.skinlang.pmsend.Sendmessageto}<br />
		{PHP.skinlang.pmsend.Sendmessagetohint}
		</td>
		<td>{PMSEND_FORM_TOUSER}</td>
	</tr>

	<tr>
		<td>{PHP.skinlang.pmsend.Subject}</td>
		<td>{PMSEND_FORM_TITLE}</td>
	</tr>

	<tr>
		<td colspan="2">
    {PHP.skinlang.pmsend.Message}<br />
    {PMSEND_FORM_TEXT}</td>
	</tr>

	<tr>
		<td colspan="2" class="valid">
		<input type="submit" class="submit btn" value="{PHP.skinlang.pmsend.Sendmessage}">
		</td>
	</tr>

</table>

</form>

</div>

<!-- END: MAIN -->