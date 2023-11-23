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

				<input type="hidden" name="userid" value="{USERS_PROFILE_ID}"><input type="hidden" name="curpassword" value="{USERS_PROFILE_PASSWORD}">

				<ul class="form responsive-form">
					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Username}</label></div>
						<div class="form-field">{USERS_PROFILE_NAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Userfirstname}</label></div>
						<div class="form-field">{USERS_PROFILE_FIRSTNAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Userlastname}</label></div>
						<div class="form-field">{USERS_PROFILE_LASTNAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Groupsmembership}</label></div>
						<div class="form-field user-groups">{PHP.skinlang.usersprofile.Maingroup}<br />&nbsp;{PHP.out.img_down}<br />{USERS_PROFILE_GROUPS}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Registeredsince}</label></div>
						<div class="form-field">{USERS_PROFILE_REGDATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Email}</label></div>
						<div class="form-field">{USERS_PROFILE_EMAIL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Hidetheemail}</label></div>
						<div class="form-field">{USERS_PROFILE_HIDEEMAIL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.PMnotify}</label></div>
						<div class="form-field">{USERS_PROFILE_PMNOTIFY} <span class="descr">{PHP.skinlang.usersprofile.PMnotifyhint}</span></div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Skin}</label></div>
						<div class="form-field">{USERS_PROFILE_SKIN}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Language}</label></div>
						<div class="form-field">{USERS_PROFILE_LANG}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Country}</label></div>
						<div class="form-field">{USERS_PROFILE_COUNTRY}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Location}</label></div>
						<div class="form-field">{USERS_PROFILE_LOCATION}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Timezone}</label></div>
						<div class="form-field">{USERS_PROFILE_TIMEZONE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Website}</label></div>
						<div class="form-field">{USERS_PROFILE_WEBSITE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Birthdate}</label></div>
						<div class="form-field">{USERS_PROFILE_BIRTHDATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Occupation}</label></div>
						<div class="form-field">{USERS_PROFILE_OCCUPATION}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Gender}</label></div>
						<div class="form-field">{USERS_PROFILE_GENDER}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Avatar}</label></div>
						<div class="form-field">{USERS_PROFILE_AVATAR}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Signature}</label></div>
						<div class="form-field">{USERS_PROFILE_TEXT}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersprofile.Newpassword}</label></div>
						<div class="form-field">
							<div class="form-field-2col">{USERS_PROFILE_NEWPASS1}</div>
							<div class="form-field-2col">{USERS_PROFILE_NEWPASS2}</div>
							<div class="descr">{PHP.skinlang.usersprofile.Newpasswordhint}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.skinlang.usersprofile.Update}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->