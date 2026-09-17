<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{USERS_REGISTER_TITLE}</h1>

			<div class="section-desc">
				{USERS_REGISTER_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<div class="auth-form">

				<!-- BEGIN: USERS_REGISTER_ERROR -->
				<div class="error">
					{USERS_REGISTER_ERROR_BODY}
				</div>
				<!-- END: USERS_REGISTER_ERROR -->

				<form name="login" action="{USERS_REGISTER_SEND}" method="post">

					<ul class="form responsive-form">
						<li class="form-row">
							<div class="form-label"><label>{PHP.L.usersregister_username} *</label></div>
							<div class="form-field">{USERS_REGISTER_USER}</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.L.usersregister_validemail} *</label></div>
							<div class="form-field">{USERS_REGISTER_EMAIL}<br />
								<div class="descr">{PHP.L.usersregister_validemailhint}</div>
							</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.L.usersregister_password} *</label></div>
							<div class="form-field">{USERS_REGISTER_PASSWORD}</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.L.usersregister_confirmpassword} *</label></div>
							<div class="form-field">{USERS_REGISTER_PASSWORDREPEAT}</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.L.usersregister_country}</label></div>
							<div class="form-field">{USERS_REGISTER_COUNTRY}</div>
						</li>


						<!-- BEGIN: USERS_REGISTER_VERIFY -->
						<li class="form-row">
							<div class="form-label"><label>{PHP.L.Captcha}: </label></div>
							<div class="form-field">
								<div>{USERS_REGISTER_VERIFYIMG}</div>
								<div>{USERS_REGISTER_VERIFYINPUT}</div>
							</div>
						</li>
						<!-- END: USERS_REGISTER_VERIFY -->
						
						<!-- IF {USERS_REGISTER_AGREEMENT} -->
						<li class="form-row">
							<div class="form-field-100">
								{USERS_REGISTER_AGREEMENT}
							</div>
						</li>
						<!-- ENDIF -->

						<li class="form-row">
							<div class="form-field-100 text-center">
								<div class="descr">{PHP.L.usersregister_formhint}</div>
							</div>
						</li>

						<li class="form-row">
							<div class="form-field-100 text-center">
								<input type="submit" class="submit btn btn-big" value="{PHP.L.usersregister_submit}">
							</div>
						</li>
					</ul>

					<!-- IF {USERS_REGISTER_OAUTH_BUTTONS} -->
					{USERS_REGISTER_OAUTH_BUTTONS}
					<!-- ENDIF -->

				</form>

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->