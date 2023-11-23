<!-- BEGIN: MAIN -->

<main id="plugins">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PLUGIN_PASSRECOVER_TITLE}</h1>

		</div>

		<div class="section-body">

			<!-- BEGIN: PLUGIN_PASSRECOVER_AUTH -->

			<p>{PHP.L.plu_mailsent}</p>

			<!-- END: PLUGIN_PASSRECOVER_AUTH -->


			<!-- BEGIN: PLUGIN_PASSRECOVER_NEWPASSWORD -->

			<p>{PHP.L.plu_mailsent2}</p>

			<!-- END: PLUGIN_PASSRECOVER_NEWPASSWORD -->


			<!-- BEGIN: PLUGIN_PASSRECOVER_GENERATEPASS -->

			<p>{PHP.L.plu_newpass}</p>

			<!-- END: PLUGIN_PASSRECOVER_GENERATEPASS -->

			<!-- BEGIN: PLUGIN_PASSRECOVER_LOGGED -->

			<p>{PHP.L.plu_loggedin1} {PLUGIN_PASSRECOVER_LOGGED_USERNAME} {PHP.L.plu_loggedin2} <br />
				{PHP.L.plu_loggedin3}</p>

			<!-- END: PLUGIN_PASSRECOVER_LOGGED -->

			<!-- BEGIN: PLUGIN_PASSRECOVER_RECOVER -->

			<p>{PHP.L.plu_explain1}<br />
				{PHP.L.plu_explain2}<br />
				{PHP.L.plu_explain3}<br /></p>

			<form name="reqauth" action="{PLUGIN_PASSRECOVER_SEND}" method="post">

				<ul class="form responsive-form">
					<li class="form-row">
						<div class="form-label" style="width:120px;">{PHP.L.plu_youremail}</div>
						<div class="form-field">{PLUGIN_PASSRECOVER_EMAIL} <button type="submit" class="submit btn">{PHP.L.plu_request}</button></div>
					</li>
				</ul>

			</form>

			<p>{PHP.L.plu_explain4}</p>

			<!-- END: PLUGIN_PASSRECOVER_RECOVER -->

		</div>

	</div>

</main>

<!-- END: MAIN -->