<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{USERS_PROFILE_SHORTTITLE}</h1>

			<div class="section-desc">
				{USERS_PROFILE_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: USERS_PROFILE_ERROR -->

			<div class="error">
				{USERS_PROFILE_ERROR_BODY}
			</div>

			<!-- END: USERS_PROFILE_ERROR -->

			<form action="{USERS_PROFILE_FORM_SEND}" method="post" enctype="multipart/form-data" name="profile" id="profile">

				<input type="hidden" name="userid" value="{USERS_PROFILE_ID}">

				<ul class="form responsive-form">
					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_username}</label></div>
						<div class="form-field">{USERS_PROFILE_NAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_userfirstname}</label></div>
						<div class="form-field">{USERS_PROFILE_FIRSTNAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_userlastname}</label></div>
						<div class="form-field">{USERS_PROFILE_LASTNAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_groupsmembership}</label></div>
						<div class="form-field user-groups">{PHP.L.usersprofile_maingroup}<br />&nbsp;{PHP.out.img_down}<br />{USERS_PROFILE_GROUPS}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_registeredsince}</label></div>
						<div class="form-field">{USERS_PROFILE_REGDATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_email}</label></div>
						<div class="form-field">{USERS_PROFILE_EMAIL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_hidetheemail}</label></div>
						<div class="form-field">{USERS_PROFILE_HIDEEMAIL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_pmnotify}</label></div>
						<div class="form-field">{USERS_PROFILE_PMNOTIFY} <span class="descr">{PHP.L.usersprofile_pmnotifyhint}</span></div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_skin}</label></div>
						<div class="form-field">{USERS_PROFILE_SKIN}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_language}</label></div>
						<div class="form-field">{USERS_PROFILE_LANG}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_country}</label></div>
						<div class="form-field">{USERS_PROFILE_COUNTRY}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_location}</label></div>
						<div class="form-field">{USERS_PROFILE_LOCATION}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_timezone}</label></div>
						<div class="form-field">{USERS_PROFILE_TIMEZONE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_website}</label></div>
						<div class="form-field">{USERS_PROFILE_WEBSITE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_birthdate}</label></div>
						<div class="form-field">{USERS_PROFILE_BIRTHDATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_occupation}</label></div>
						<div class="form-field">{USERS_PROFILE_OCCUPATION}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_gender}</label></div>
						<div class="form-field">{USERS_PROFILE_GENDER}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_avatar}</label></div>
						<div class="form-field">{USERS_PROFILE_AVATAR}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_photo}</label></div>
						<div class="form-field">{USERS_PROFILE_PHOTO}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_signature}</label></div>
						<div class="form-field">{USERS_PROFILE_SIGNATURE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_signature}</label></div>
						<div class="form-field">{USERS_PROFILE_TEXT}</div>
					</li>

					<!-- IF {PROFILE_OAUTH_BLOCK} -->
					<li class="form-row">
						<div class="form-label"><label>{PHP.L.hybridauth_social_accounts}</label></div>
						<div class="form-field">{PROFILE_OAUTH_BLOCK}</div>
					</li>
					<!-- ENDIF -->

				<!-- BEGIN: USERS_PROFILE_OLDPASS -->
					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_oldpassword}</label></div>
						<div class="form-field">
							{USERS_PROFILE_OLDPASS}
							<div class="descr">{PHP.L.usersprofile_oldpasswordhint}</div>
						</div>
					</li>
					<!-- END: USERS_PROFILE_OLDPASS -->

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersprofile_newpassword}</label></div>
						<div class="form-field">
							<div class="form-field-2col">{USERS_PROFILE_NEWPASS1}</div>
							<div class="form-field-2col">{USERS_PROFILE_NEWPASS2}</div>
							<div class="descr">{PHP.L.usersprofile_newpasswordhint}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.L.usersprofile_update}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->