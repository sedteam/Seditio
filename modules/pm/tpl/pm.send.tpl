<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PMSEND_SHORTTITLE}</h1>

			<div class="section-desc">
				{PMSEND_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: PMSEND_ERROR -->

			{PMSEND_ERROR_BODY}

			<!-- END: PMSEND_ERROR -->

			<form action="{PMSEND_FORM_SEND}" method="post" name="newlink">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.pmsend_sendmessageto}</label></div>
						<div class="form-field">
							{PMSEND_FORM_TOUSER}
							<div class="descr">{PHP.L.pmsend_sendmessagetohint}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.pmsend_subject}</label></div>
						<div class="form-field">{PMSEND_FORM_TITLE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.pmsend_message}</label></div>
						<div class="form-field">{PMSEND_FORM_TEXT}</div>
					</li>

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.L.pmsend_sendmessage}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->