<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PM_SHORTTITLE}</h1>

			<div class="section-desc">
				{PM_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<div class="centered">
				{PM_INBOX} &nbsp; &nbsp; {PM_ARCHIVES} &nbsp; &nbsp; {PM_SENTBOX} &nbsp; &nbsp; {PM_SENDNEWPM}
			</div>

			<table class="cells striped">

				<!-- BEGIN: PM_TITLE -->

				<tr>
					<td class="coltop" style="width:16px;">{PHP.skinlang.pm.State}</td>
					<td class="coltop">{PHP.skinlang.pm.Sender}</td>
					<td class="coltop" style="width:40%;">{PHP.skinlang.pm.SubjectClick}</td>
					<td class="coltop" style="width:176px;">{PHP.skinlang.pm.Date}</td>
					<td class="coltop" style="width:72px;">{PHP.skinlang.pm.Action}</td>
				</tr>


				<!-- END: PM_TITLE -->

				<!-- BEGIN: PM_TITLE_SENTBOX -->

				<tr>
					<td class="coltop" style="width:16px;">{PHP.skinlang.pm.State}</td>
					<td class="coltop">{PHP.skinlang.pm.Recipient}</td>
					<td class="coltop" style="width:40%;">{PHP.skinlang.pm.SubjectClick}</td>
					<td class="coltop" style="width:176px; text-align:center;">{PHP.skinlang.pm.Date}</td>
					<td class="coltop" style="width:72px; text-align:center;">{PHP.skinlang.pm.Action}</td>
				</tr>

				<!-- END: PM_TITLE_SENTBOX -->

				<!-- BEGIN: PM_ROW -->

				<tr>
					<td style="width:16px;" class="centerall {PM_ROW_ODDEVEN}">{PM_ROW_ICON_STATUS}</td>
					<td class="{PM_ROW_ODDEVEN}">{PM_ROW_FROMORTOUSER}</td>
					<td class="{PM_ROW_ODDEVEN}"><strong>{PM_ROW_TITLE}</strong></td>
					<td style="width:112px;" class="centerall {PM_ROW_ODDEVEN}">{PM_ROW_DATE}</td>
					<td style="width:112px;" class="centerall {PM_ROW_ODDEVEN}">{PM_ROW_ICON_ACTION}</td>
				</tr>

				<!-- END: PM_ROW -->

				<!-- BEGIN: PM_ROW_EMPTY -->

				<tr>
					<td colspan="5" style="padding:16px;">
						{PHP.skinlang.pm.Nomessages}
					</td>
				</tr>

				<!-- END: PM_ROW_EMPTY -->

			</table>

			<div class="pagination-box">

				<ul class="pagination">
					<li class="page-item">{PM_TOP_PAGEPREV}</li>
					{PM_TOP_PAGINATION}
					<li class="page-item">{PM_TOP_PAGENEXT}</li>
				</ul>

			</div>

			<!-- BEGIN: PM_FOOTER -->

			<!-- END: PM_FOOTER -->

			<!-- BEGIN: PM_DETAILS -->

			{PHP.skinlang.pm.Subject} : <strong>{PM_ROW_TITLE}</strong><br />
			{PHP.skinlang.pm.Sender} : {PM_ROW_FROMUSER} / {PHP.skinlang.pm.Recipient} : {PM_ROW_TOUSER} / {PHP.skinlang.pm.Date} : {PM_ROW_DATE}
			&nbsp; &nbsp; {PHP.skinlang.pm.Action} : {PM_ROW_ICON_ACTION}

			<div class="block">
				{PM_ROW_TEXT}
			</div>

			<!-- END: PM_DETAILS -->

			<div class="centered">

				<img src="skins/{PHP.skin}/img/system/icon-pm-new.gif" alt="" />: {PHP.skinlang.pm.Newmessage} &nbsp; &nbsp;
				<img src="skins/{PHP.skin}/img/system/icon-pm.gif" alt="" />: {PHP.skinlang.pm.Message} &nbsp; &nbsp;
				<img src="skins/{PHP.skin}/img/system/icon-pm-reply.gif" alt="" />: {PHP.skinlang.pm.Reply} &nbsp; &nbsp;
				<img src="skins/{PHP.skin}/img/system/icon-pm-archive.gif" alt="" />: {PHP.skinlang.pm.Sendtoarchives} &nbsp; &nbsp;
				<img src="skins/{PHP.skin}/img/system/icon-pm-trashcan.gif" alt="" />: {PHP.skinlang.pm.Delete}

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->