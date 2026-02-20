<!-- BEGIN: MAIN -->

<main id="forums">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{FORUMS_NEWTOPIC_SHORTTITLE}</h1>

			<div class="section-desc">
				{FORUMS_NEWTOPIC_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: FORUMS_NEWTOPIC_ERROR -->

			{FORUMS_NEWTOPIC_ERROR_BODY}

			<!-- END: FORUMS_NEWTOPIC_ERROR -->

			<form action="{FORUMS_NEWTOPIC_SEND}" method="post" name="newtopic">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.Title}</label></div>
						<div class="form-field">{FORUMS_NEWTOPIC_TITLE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.Desc}</label></div>
						<div class="form-field">{FORUMS_NEWTOPIC_DESC}</div>
					</li>

					<!-- BEGIN: PRIVATE -->

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.privatetopic}</label></div>
						<div class="form-field">
							{FORUMS_NEWTOPIC_ISPRIVATE}
							<div class="descr">{PHP.skinlang.forumsnewtopic.privatetopic2}</div>
						</div>
					</li>

					<!-- END: PRIVATE -->

					<li class="form-row">
						<div class="form-field-100">
							{FORUMS_NEWTOPIC_TEXT}
						</div>
					</li>

					<!-- BEGIN: FORUMS_NEW_POLL -->
					<li class="form-row">
						<div class="form-label"><label>{PHP.L.polls_add}</label></div>
						<div class="form-field">
							<div class="add-poll-label">{PHP.L.polls_title}:</div>
							<div class="add-poll-title">
								{NEW_POLL_TEXT}
							</div>
							<div class="add-poll-label">{PHP.L.polls_options}:</div>
							<div class="add-poll-options">
								<!-- BEGIN: NEW_POLL_OPTIONS -->
								<div class="add-poll-option">
									{PHP.L.polls_option} #<span class="num">{NEW_POLL_NUM}</span>: {NEW_POLL_OPTION}
									<!-- BEGIN: NEW_POLL_OPTIONS_DELETE -->
									<button type="button" class="poll-option-delete" onclick="sedjs.remove_poll_option(this);">x</button>
									<!-- END: NEW_POLL_OPTIONS_DELETE -->
								</div>
								<!-- END: NEW_POLL_OPTIONS -->
							</div>
							<button type="button" onclick="sedjs.add_poll_option('.add-poll-option');" class="poll-addoption">{PHP.L.polls_addoption}</button>
						</div>
					</li>
					<!-- END: FORUMS_NEW_POLL -->

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.skinlang.forumsnewtopic.Submit}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->