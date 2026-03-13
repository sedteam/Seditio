<!-- BEGIN: MAIN -->

<main id="plugins">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PLUGIN_CONTACT_SHORTTITLE}</h1>

			<div class="section-desc">
				{PLUGIN_CONTACT_EXPLAIN}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: PLUGIN_CONTACT_ERROR -->

			{PLUGIN_CONTACT_ERROR_BODY}

			<!-- END: PLUGIN_CONTACT_ERROR -->

			<!-- BEGIN: PLUGIN_CONTACT_DONE -->

			{PLUGIN_CONTACT_DONE_BODY}

			<!-- END: PLUGIN_CONTACT_DONE -->

			<form action="{PLUGIN_CONTACT_FORM}" method="post" name="sendmail">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PLUGIN_CONTACT_RECIPIENTS_TITLE} :</label></div>
						<div class="form-field">{PLUGIN_CONTACT_RECIPIENTS}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PLUGIN_CONTACT_EMAIL_TITLE} :</label></div>
						<div class="form-field">{PLUGIN_CONTACT_EMAIL} *</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PLUGIN_CONTACT_NAME_TITLE} :</label></div>
						<div class="form-field">{PLUGIN_CONTACT_NAME} *</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PLUGIN_CONTACT_PHONE_TITLE} :</label></div>
						<div class="form-field">{PLUGIN_CONTACT_PHONE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PLUGIN_CONTACT_SUBJECT_TITLE} :</label></div>
						<div class="form-field">{PLUGIN_CONTACT_SUBJECT} *</div>
					</li>


					<li class="form-row">
						<div class="form-label"><label>{PLUGIN_CONTACT_BODY_TITLE} :</label></div>
						<div class="form-field">{PLUGIN_CONTACT_BODY} *</div>
					</li>

					<li class="form-row">
						<div class="form-field-100">{PLUGIN_CONTACT_REQUIRED}</div>
					</li>

					<li class="form-row">
						<div class="form-field-100 text-center">{PLUGIN_CONTACT_ANTISPAM}</div>
					</li>

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn btn-big">{PLUGIN_CONTACT_SEND}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->