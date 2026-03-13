<!-- BEGIN: COMMENTS_ITEM -->
<div class="comment">

	<div id="comment-{COMMENTS_ROW_ID}" class="comment-container">

		<div class="comments-header">

			<div class="comments-avatar">
				{COMMENTS_ROW_AVATAR}
			</div>

			<div class="comment-head">
				<a href="{COMMENTS_ROW_URL}" id="c{COMMENTS_ROW_ID}"></a>
				<span class="name">{COMMENTS_ROW_AUTHOR}</span>
				<span class="date">{COMMENTS_ROW_DATE}</span>
				<a href="{COMMENTS_ROW_URL}" class="comment-anchor" title="{COMMENTS_ROW_ANCHOR_TITLE}">#</a>
				<span class="reply">{COMMENTS_ROW_REPLY}</span>
				<span class="edit">{COMMENTS_ROW_ADMIN}</span>
			</div>

		</div>

		<div class="comment-entry" id="comment-{COMMENTS_ROW_ID}">
			{COMMENTS_ROW_TEXT}
		</div>
		
		{COMMENTS_ROW_THANKS_DISPLAY}
		
		<div class="comment-replies">
			{COMMENTS_CHILDREN}
		</div>

	</div>

</div>
<!-- END: COMMENTS_ITEM -->
