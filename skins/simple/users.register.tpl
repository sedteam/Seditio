<!-- BEGIN: MAIN -->

<div id="title">

	<h2>{USERS_REGISTER_TITLE}</h2>

</div>

<div id="subtitle">

	{USERS_REGISTER_SUBTITLE}

</div>

<!-- BEGIN: USERS_REGISTER_ERROR -->

<div class="error">

	{USERS_REGISTER_ERROR_BODY}

</div>

<!-- END: USERS_REGISTER_ERROR -->

<div id="page">

<form name="login" action="{USERS_REGISTER_SEND}" method="post">

<table class="cells striped">

		<tr>
			<td style="width:176px;">{PHP.skinlang.usersregister.Username}</td>
			<td>{USERS_REGISTER_USER} *</td>
		</tr>
		<tr>
			<td>{PHP.skinlang.usersregister.Validemail}</td>
			<td>{USERS_REGISTER_EMAIL} *<br />
			<div class="descr">{PHP.skinlang.usersregister.Validemailhint}</div></td>
		</tr>
		<tr>
			<td>{PHP.skinlang.usersregister.Password}</td>
			<td>{USERS_REGISTER_PASSWORD} *</td>
		</tr>
		<tr>
			<td>{PHP.skinlang.usersregister.Confirmpassword}</td>
			<td>{USERS_REGISTER_PASSWORDREPEAT} *</td>
		</tr>
		<tr>
			<td>{PHP.skinlang.usersregister.Country}</td>
			<td>{USERS_REGISTER_COUNTRY}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="descr">{PHP.skinlang.usersregister.Formhint}</div></td>
		</tr>
		<tr>
			<td colspan="2" class="valid">
			<input type="submit" class="submit btn btn-big" value="{PHP.skinlang.usersregister.Submit}"></td>
		</tr>

</table>

</form>

</div>

<!-- END: MAIN -->