<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{USERS_EDIT_SHORTTITLE}</h1>

			<div class="section-desc">
				{USERS_EDIT_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: USERS_EDIT_ERROR -->

			<div class="error">
				{USERS_EDIT_ERROR_BODY}
			</div>

			<!-- END: USERS_EDIT_ERROR -->

			<form action="{USERS_EDIT_SEND}" method="post" enctype="multipart/form-data" name="useredit">

				<input type="hidden" name="id" value="{USERS_EDIT_ID}">

				<ul class="form responsive-form">
					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_userid}</label></div>
						<div class="form-field">#{USERS_EDIT_ID}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_username}</label></div>
						<div class="form-field">{USERS_EDIT_NAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_userfirstname}</label></div>
						<div class="form-field">{USERS_EDIT_FIRSTNAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_userlastname}</label></div>
						<div class="form-field">{USERS_EDIT_LASTNAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_groupsmembership}</label></div>
						<div class="form-field user-groups">{PHP.L.usersedit_maingroup}<br />&nbsp;{PHP.out.img_down}<br />{USERS_EDIT_GROUPS}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_country}</label></div>
						<div class="form-field">{USERS_EDIT_COUNTRY}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_location}</label></div>
						<div class="form-field">{USERS_EDIT_LOCATION}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_timezone}</label></div>
						<div class="form-field">{USERS_EDIT_TIMEZONE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_skin}</label></div>
						<div class="form-field">{USERS_EDIT_SKIN}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_language}</label></div>
						<div class="form-field">{USERS_EDIT_LANG}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_avatar}</label></div>
						<div class="form-field">{USERS_EDIT_AVATAR}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_photo}</label></div>
						<div class="form-field">{USERS_EDIT_PHOTO}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_signature}</label></div>
						<div class="form-field">{USERS_EDIT_SIGNATURE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_newpassword}</label></div>
						<div class="form-field">
							{USERS_EDIT_NEWPASS}
							<div class="descr">{PHP.L.usersedit_newpasswordhint}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_email}</label></div>
						<div class="form-field">{USERS_EDIT_EMAIL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_hidetheemail}</label></div>
						<div class="form-field">{USERS_EDIT_HIDEEMAIL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_pmnotify}</label></div>
						<div class="form-field">{USERS_EDIT_PMNOTIFY} {PHP.L.usersedit_pmnotifyhint}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_website}</label></div>
						<div class="form-field">{USERS_EDIT_WEBSITE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_birthdate}</label></div>
						<div class="form-field">{USERS_EDIT_BIRTHDATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_occupation}</label></div>
						<div class="form-field">{USERS_EDIT_OCCUPATION}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_gender}</label></div>
						<div class="form-field">{USERS_EDIT_GENDER}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{USERS_EDIT_INSTAGRAM_TITLE}</label></div>
						<div class="form-field">{USERS_EDIT_INSTAGRAM} {USERS_EDIT_INSTAGRAM_MERA}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_signature}</label></div>
						<div class="form-field">{USERS_EDIT_TEXT}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_registeredsince}</label></div>
						<div class="form-field">{USERS_EDIT_REGDATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_lastlogged}</label></div>
						<div class="form-field">{USERS_EDIT_LASTLOG}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_lastip}</label></div>
						<div class="form-field">{USERS_EDIT_LASTIP}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_logcounter}</label></div>
						<div class="form-field">{USERS_EDIT_LOGCOUNT}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.usersedit_deletethisuser}</label></div>
						<div class="form-field">{USERS_EDIT_DELETE}</div>
					</li>

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.L.usersedit_update}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->