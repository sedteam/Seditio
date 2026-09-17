<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{USERS_DETAILS_SHORTTITLE}</h1>

			<div class="section-desc">
				{USERS_DETAILS_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: USERS_DETAILS_ADMIN -->
			<div id="admin">
				{USERS_DETAILS_ADMIN_EDIT}
			</div>
			<!-- END: USERS_DETAILS_ADMIN -->

			<div class="table-cells striped">

				<div class="table-tr">
					<div class="table-td" style="width:200px;">{PHP.L.usersdetails_sendprivatemessage}</div>
					<div class="table-td">{USERS_DETAILS_PM}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_userfirstname}</div>
					<div class="table-td">{USERS_DETAILS_FIRSTNAME}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_userlastname}</div>
					<div class="table-td">{USERS_DETAILS_LASTNAME}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_maingroup}</div>
					<div class="table-td">{USERS_DETAILS_MAINGRP}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_memberof}</div>
					<div class="table-td">{PHP.L.usersedit_maingroup}<br />&nbsp;{PHP.out.img_down}<br />{USERS_DETAILS_GROUPS}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_country}</div>
					<div class="table-td">{USERS_DETAILS_COUNTRYFLAG} {USERS_DETAILS_COUNTRY}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_location}</div>
					<div class="table-td">{USERS_DETAILS_LOCATION}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_timezone}</div>
					<div class="table-td">{USERS_DETAILS_TIMEZONE}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_weblog}</div>
					<div class="table-td">{USERS_DETAILS_JOURNAL}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_website}</div>
					<div class="table-td">{USERS_DETAILS_WEBSITE}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_birthdate}</div>
					<div class="table-td">{USERS_DETAILS_BIRTHDATE}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_age}</div>
					<div class="table-td">{USERS_DETAILS_AGE}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_occupation}</div>
					<div class="table-td">{USERS_DETAILS_OCCUPATION}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_gender}</div>
					<div class="table-td">{USERS_DETAILS_GENDER}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{USERS_DETAILS_INSTAGRAM_TITLE}</div>
					<div class="table-td">{USERS_DETAILS_INSTAGRAM} {USERS_DETAILS_INSTAGRAM_MERA}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_signature}</div>
					<div class="table-td">{USERS_DETAILS_TEXT}</div>
				</div>

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_registrationdate}</div>
					<div class="table-td">{USERS_DETAILS_REGDATE}</div>
				</div>

				<!-- BEGIN: USERS_DETAILS_THANKS -->
				<div class="table-tr">
					<div class="table-td">{PHP.L.thanks_title_short}</div>
					<div class="table-td"><a href="{USERS_DETAILS_THANKS_URL}">{USERS_DETAILS_THANKS_COUNT}</a></div>
				</div>
				<!-- END: USERS_DETAILS_THANKS -->

				<div class="table-tr">
					<div class="table-td">{PHP.L.usersdetails_avatar}</div>
					<div class="table-td">{USERS_DETAILS_AVATAR}</div>
				</div>

			</div>

			<!-- BEGIN: USERS_DETAILS_COMMENTS -->
			<div class="page-comments spoiler-container {USERS_DETAILS_COMMENTS_ISSHOW}">
				<div class="comments-box-title">
					<h3><a href="{USERS_DETAILS_COMMENTS_URL}">{PHP.L.page_comments} <i class="ic-socialbtn"></i> <span class="comments-amount">({USERS_DETAILS_COMMENTS_COUNT})</span>{USERS_DETAILS_COMMENTS_JUMP}</a></h3>
				</div>
				<div class="comments-box spoiler-body">
					{USERS_DETAILS_COMMENTS_DISPLAY}
				</div>
			</div>
			<!-- END: USERS_DETAILS_COMMENTS -->

		</div>

	</div>

</main>

<!-- END: MAIN -->