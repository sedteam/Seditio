<!-- BEGIN: MAIN -->

<main id="forums">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{FORUMS_EDITPOST_SHORTTITLE}</h1>
			<div class="section-desc">
				{FORUMS_EDITPOST_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<form action="{FORUMS_EDITPOST_SEND}" method="post" name="editpost">

				<!-- BEGIN: FORUMS_EDITPOST_ERROR -->

				{FORUMS_EDITPOST_ERROR_BODY}

				<!-- END: FORUMS_EDITPOST_ERROR -->

				<ul class="form responsive-form">

					<!-- BEGIN: FORUMS_EDITPOST_FIRST -->

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.Title}</label></div>
						<div class="form-field">{FORUMS_EDITPOST_TITLE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.Desc}</label></div>
						<div class="form-field">{FORUMS_EDITPOST_DESC}</div>
					</li>

					<!-- END: FORUMS_EDITPOST_FIRST -->

					<li class="form-row">
						<div class="form-field-100">
							{FORUMS_EDITPOST_TEXT}
						</div>
					</li>

					<!-- BEGIN: FORUMS_EDIT_POLL -->
					<li class="form-row">
						<div class="form-label"><label>{PHP.L.polls_add}</label></div>
						<div class="form-field">
							<div class="add-poll-label">{PHP.L.polls_title}:</div>
							<div class="add-poll-title">
								{EDIT_POLL_TEXT}
							</div>
							<div class="add-poll-label">{PHP.L.polls_options}:</div>
							<div class="add-poll-options">
								<!-- BEGIN: EDIT_POLL_OPTIONS -->
								<div class="add-poll-option">
									{PHP.L.polls_option} #<span class="num">{EDIT_POLL_NUM}</span>: {EDIT_POLL_OPTION}
									<!-- BEGIN: EDIT_POLL_OPTIONS_DELETE -->
									<button type="button" class="poll-option-delete" onclick="sedjs.remove_poll_option(this);">x</button>
									<!-- END: EDIT_POLL_OPTIONS_DELETE -->
								</div>
								<!-- END: EDIT_POLL_OPTIONS -->
							</div>
							<button type="button" onclick="sedjs.add_poll_option('.add-poll-option');" class="poll-addoption">{PHP.L.polls_addoption}</button>
						</div>
					</li>
					<!-- END: FORUMS_EDIT_POLL -->

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.skinlang.forumseditpost.Update}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->