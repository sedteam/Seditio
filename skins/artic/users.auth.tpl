<!-- BEGIN: MAIN -->

<div id="title">

	{USERS_AUTH_TITLE}

</div>

<div id="main">

<div style="text-align:center;">

<div style="text-align:left; width:640px; margin:0 auto;">

<form name="login" action="{USERS_AUTH_SEND}" method="post">

  <table class="cells">
    <tr><td>{PHP.skinlang.usersauth.Username}</td><td>{USERS_AUTH_USER}</td>
      <td rowspan="4">
        <a href="{USERS_AUTH_REGISTER}">{PHP.skinlang.usersauth.Register}</a><br />
    		<a href="plug.php?e=passrecover">{PHP.skinlang.usersauth.Lostpassword}</a>
      </td>
    </tr>
		<tr><td>{PHP.skinlang.usersauth.Password}</td><td>{USERS_AUTH_PASSWORD}</td></tr>
		<tr><td>{PHP.skinlang.usersauth.Rememberme}</td><td>{PHP.out.guest_cookiettl}</td></tr>
    <tr><td colspan="2" style="text-align:center;"><input type="submit" value="{PHP.skinlang.usersauth.Login}"></td></tr>
  </table>
     
</form>

</div>

</div>

<!-- END: MAIN -->