<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{USERS_AUTH_TITLE}</h1>

			<div class="section-desc">
				{USERS_AUTH_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<div class="auth-form">

				<!-- BEGIN: USERS_AUTH_ERROR -->
				<div class="error">
					{USERS_AUTH_ERROR_BODY}
				</div>
				<!-- END: USERS_AUTH_ERROR -->

				<form name="login" action="{USERS_AUTH_SEND}" method="post">

					<ul class="form responsive-form">

						<li class="form-row">
							<div class="form-label">{PHP.skinlang.usersauth.Username}</div>
							<div class="form-field">{USERS_AUTH_USER}<span class="require"></span></div>
						</li>

						<li class="form-row">
							<div class="form-label">{PHP.skinlang.usersauth.Password}</div>
							<div class="form-field">{USERS_AUTH_PASSWORD}<span class="require"></span></div>
						</li>

						<li class="form-row">
							<div class="form-label">{PHP.skinlang.usersauth.Rememberme}</div>
							<div class="form-field">{PHP.out.guest_cookiettl}<span class="require"></span></div>
						</li>

						<!-- BEGIN: USERS_AUTH_VERIFY -->
						<li class="form-row">
							<div class="form-label"><label>{PHP.L.Captcha}: </label></div>
							<div class="form-field">
								<div>{USERS_AUTH_VERIFYIMG}</div>
								<div>{USERS_AUTH_VERIFYINPUT}</div>
							</div>
						</li>
						<!-- END: USERS_AUTH_VERIFY -->

						<li class="form-row">
							<div class="form-field-100 text-center">
								<input type="submit" class="submit btn btn-big" value="{PHP.skinlang.usersauth.Login}">
							</div>
						</li>

					</ul>

				</form>

				<div class="auth-links">
					<ul class="systemlist">
						<li><a href="{USERS_AUTH_REGISTER}">{PHP.skinlang.usersauth.Register}</a></li>
						<li><a href="{USERS_AUTH_LOSTPASSWORD}">{PHP.skinlang.usersauth.Lostpassword}</a></li>
					</ul>
				</div>

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->