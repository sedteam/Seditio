<!-- BEGIN: MAIN -->

<div id="title">

	<h2>{USERS_AUTH_TITLE}</h2>

</div>

<div class="col-row width-50 margin-auto">
    
    <div class="colleft">
      <form name="login" action="{USERS_AUTH_SEND}" method="post">
        <table class="cells striped">
          <tr><td>{PHP.skinlang.usersauth.Username}</td><td>{USERS_AUTH_USER}</td></tr>
      		<tr><td>{PHP.skinlang.usersauth.Password}</td><td>{USERS_AUTH_PASSWORD}</td></tr>
      		<tr><td>{PHP.skinlang.usersauth.Rememberme}</td><td>{PHP.out.guest_cookiettl}</td></tr>
          <tr><td colspan="2" style="text-align:center;"><input type="submit" class="submit btn btn-big" value="{PHP.skinlang.usersauth.Login}"></td></tr>
        </table>   
      </form>
    </div>
    
    <div class="colright">
      <ul class="arrow_list">
        <li><a href="{USERS_AUTH_REGISTER}">{PHP.skinlang.usersauth.Register}</a></li>
    		<li><a href="{USERS_AUTH_LOSTPASSWORD}">{PHP.skinlang.usersauth.Lostpassword}</a></li>
      </ul>
    </div>
      
</div>

<!-- END: MAIN -->