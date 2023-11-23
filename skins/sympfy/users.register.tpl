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
							<div class="form-label"><label>{PHP.skinlang.usersregister.Username} *</label></div>
							<div class="form-field">{USERS_REGISTER_USER}</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.skinlang.usersregister.Validemail} *</label></div>
							<div class="form-field">{USERS_REGISTER_EMAIL}<br />
								<div class="descr">{PHP.skinlang.usersregister.Validemailhint}</div>
							</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.skinlang.usersregister.Password} *</label></div>
							<div class="form-field">{USERS_REGISTER_PASSWORD}</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.skinlang.usersregister.Confirmpassword} *</label></div>
							<div class="form-field">{USERS_REGISTER_PASSWORDREPEAT}</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.skinlang.usersregister.Country}</label></div>
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

						<li class="form-row">
							<div class="form-field-100 text-center">
								<div class="descr">{PHP.skinlang.usersregister.Formhint}</div>
							</div>
						</li>

						<li class="form-row">
							<div class="form-field-100 text-center">
								<input type="submit" class="submit btn btn-big" value="{PHP.skinlang.usersregister.Submit}">
							</div>
						</li>
					</ul>

				</form>

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->