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

			<form action="{USERS_EDIT_SEND}" method="post" name="useredit">

				<input type="hidden" name="id" value="{USERS_EDIT_ID}">

				<ul class="form responsive-form">
					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.UserID}</label></div>
						<div class="form-field">#{USERS_EDIT_ID}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Username}</label></div>
						<div class="form-field">{USERS_EDIT_NAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Userfirstname}</label></div>
						<div class="form-field">{USERS_EDIT_FIRSTNAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Userlastname}</label></div>
						<div class="form-field">{USERS_EDIT_LASTNAME}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Groupsmembership}</label></div>
						<div class="form-field user-groups">{PHP.skinlang.usersedit.Maingroup}<br />&nbsp;{PHP.out.img_down}<br />{USERS_EDIT_GROUPS}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Country}</label></div>
						<div class="form-field">{USERS_EDIT_COUNTRY}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Location}</label></div>
						<div class="form-field">{USERS_EDIT_LOCATION}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Timezone}</label></div>
						<div class="form-field">{USERS_EDIT_TIMEZONE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Skin}</label></div>
						<div class="form-field">{USERS_EDIT_SKIN}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Language}</label></div>
						<div class="form-field">{USERS_EDIT_LANG}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Avatar}</label></div>
						<div class="form-field">{USERS_EDIT_AVATAR}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Signature}</label></div>
						<div class="form-field">{USERS_EDIT_SIGNATURE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Newpassword}</label></div>
						<div class="form-field">
							{USERS_EDIT_NEWPASS}
							<div class="descr">{PHP.skinlang.usersedit.Newpasswordhint}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Email}</label></div>
						<div class="form-field">{USERS_EDIT_EMAIL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Hidetheemail}</label></div>
						<div class="form-field">{USERS_EDIT_HIDEEMAIL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.PMnotify}</label></div>
						<div class="form-field">{USERS_EDIT_PMNOTIFY} {PHP.skinlang.usersedit.PMnotifyhint}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Website}</label></div>
						<div class="form-field">{USERS_EDIT_WEBSITE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Birthdate}</label></div>
						<div class="form-field">{USERS_EDIT_BIRTHDATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Occupation}</label></div>
						<div class="form-field">{USERS_EDIT_OCCUPATION}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Gender}</label></div>
						<div class="form-field">{USERS_EDIT_GENDER}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{USERS_EDIT_INSTAGRAM_TITLE}</label></div>
						<div class="form-field">{USERS_EDIT_INSTAGRAM} {USERS_EDIT_INSTAGRAM_MERA}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Signature}</label></div>
						<div class="form-field">{USERS_EDIT_TEXT}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Registeredsince}</label></div>
						<div class="form-field">{USERS_EDIT_REGDATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Lastlogged}</label></div>
						<div class="form-field">{USERS_EDIT_LASTLOG}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.LastIP}</label></div>
						<div class="form-field">{USERS_EDIT_LASTIP}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Logcounter}</label></div>
						<div class="form-field">{USERS_EDIT_LOGCOUNT}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.usersedit.Deletethisuser}</label></div>
						<div class="form-field">{USERS_EDIT_DELETE}</div>
					</li>

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.skinlang.usersedit.Update}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->