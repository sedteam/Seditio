<!-- BEGIN: POLL -->

<div id="pollajx">

	<div class="poll-box">

		<h4>{POLL_TITLE}</h4>

		<!-- BEGIN: POLL_ERROR -->

		<div class="error">
			{POLL_ERROR_BODY}
		</div>

		<!-- END: POLL_ERROR -->

		<!-- BEGIN: POLL_FORM -->

		<div class="forum-poll-form">

			<form name="pollvotes" id="pollvotes" action="{POLL_SEND_URL}" method="post">

				<!-- BEGIN: POLL_ROW_OPTIONS -->
				<div class="poll-item">
					{POLL_ROW_RADIO_ITEM}
				</div>
				<!-- END: POLL_ROW_OPTIONS -->

				<div class="poll-btn">
					<button type="submit" class="btn" onclick="{POLL_BUTTON_ONCLICK}">{PHP.L.Vote}</button>
				</div>

			</form>

		</div>

		<!-- END: POLL_FORM -->

		<!-- BEGIN: POLL_RESULT -->

		<!-- BEGIN: POLL_ROW_RESULT -->
		<div class="poll-item">
			<div class="poll-head-table">
				<div class="poll-title-td">
					{POLL_ROW_TEXT}
				</div>
				<div class="poll-count-td">
					({POLL_ROW_COUNT})
				</div>
			</div>
			<div class="poll-bar">
				<div class="bar_back">
					<div class="bar_front" style="width:{POLL_ROW_PERCENT}%;"></div>
				</div>
			</div>

		</div>
		<!-- END: POLL_ROW_RESULT -->

		<!-- END: POLL_RESULT -->

		<div class="poll-info">
			<p> {POLL_INFO}<br />
				{POLL_VOTERS} {PHP.skinlang.polls.voterssince} {POLL_SINCE}<br />
			</p>
		</div>

	</div>

</div>

<!-- BEGIN: POLL_COMMENTS -->
<div class="poll-comments spoiler-container {POLL_COMMENTS_ISSHOW}">

	<div class="comments-box-title">
		<h3><a href="{POLL_COMMENTS_URL}">{PHP.skinlang.polls.Comments} <i class="ic-socialbtn"></i> <span class="comments-amount">({POLL_COMMENTS_COUNT})</span>{POLL_COMMENTS_JUMP}</a></h3>
	</div>

	<div class="comments-box spoiler-body">
		{POLL_COMMENTS_DISPLAY}
	</div>

</div>
<!-- END: POLL_COMMENTS -->

<!-- END: POLL -->