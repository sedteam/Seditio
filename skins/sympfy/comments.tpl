<!-- BEGIN: COMMENTS -->

<!-- BEGIN: COMMENTS_EMPTY -->

<div class="block">
	{COMMENTS_EMPTYTEXT}
</div>

<!-- END: COMMENTS_EMPTY -->

<div class="commentlist">

	<!-- BEGIN: COMMENTS_ROW -->

	<div class="comment {COMMENTS_ROW_ODDEVEN}">

		<div id="comment-{COMMENTS_ROW_ID}" class="comment-container">

			<div class="comments-header">

				<div class="comments-avatar">
					{COMMENTS_ROW_AVATAR}
				</div>

				<div class="comment-head">
					<a href="{COMMENTS_ROW_URL}" id="c{COMMENTS_ROW_ID}"></a>
					<span class="name">{COMMENTS_ROW_AUTHOR}</span>
					<span class="date">{COMMENTS_ROW_DATE}</span>
					<span class="edit">{COMMENTS_ROW_ADMIN}</span>
				</div>

			</div>

			<div class="comment-entry" id="comment-{COMMENTS_ROW_ID}">
				{COMMENTS_ROW_TEXT}
			</div>

		</div>

	</div>

	<!-- END: COMMENTS_ROW -->

</div>

<div class="paging">
	<ul class="pagination">
		<li class="prev">{COMMENTS_PAGEPREV}</li>
		{COMMENTS_PAGINATION}
		<li class="next">{COMMENTS_PAGENEXT}</li>
	</ul>
</div>

<!-- BEGIN: COMMENTS_ERROR -->

{COMMENTS_ERROR_BODY}

<!-- END: COMMENTS_ERROR -->

<!-- BEGIN: COMMENTS_NEWCOMMENT -->

<form action="{COMMENTS_FORM_SEND}" method="post" name="newcomment">
	<div class="boxed">
		<h4>{PHP.skinlang.comments.Newcomment}</h4>
		<a name="nc"></a>
		{COMMENTS_FORM_TEXT}
		<p><button type="submit" class="submit btn btn-big">{PHP.skinlang.comments.Send}</button></p>
	</div>
</form>

<!-- END: COMMENTS_NEWCOMMENT -->

<!-- BEGIN: COMMENTS_EDITCOMMENT -->

<form action="{COMMENTS_EDIT_FORM_SEND}" method="post" name="editcomment">
	<div class="boxed">
		<h4>{PHP.skinlang.comments.Commentedit}:</h4>
		<a name="c{COMMENTS_EDIT_FORM_ID}"></a>
		{COMMENTS_EDIT_FORM_TEXT}
		<p><button type="submit" class="submit btn btn-big">{PHP.skinlang.comments.Update}</button></p>
	</div>
</form>

<!-- END: COMMENTS_EDITCOMMENT -->

<!-- BEGIN: COMMENTS_DISABLE -->

<div class="block">
	{COMMENTS_DISABLETEXT}
</div>

<!-- END: COMMENTS_DISABLE -->

<!-- END: COMMENTS -->