<!-- BEGIN: COMMENTS -->

<!-- BEGIN: COMMENTS_EMPTY -->

<div class="block">
	{COMMENTS_EMPTYTEXT}
</div>

<!-- END: COMMENTS_EMPTY -->

<div class="commentlist">
	{COMMENTS_TREE}
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
		
		<!-- BEGIN: COMMENTS_REPLY_NOTICE -->
		<div class="reply-notice">
			{COMMENTS_REPLY_TO_LABEL}: <a href="{COMMENTS_REPLY_TO_URL}">#{COMMENTS_REPLY_TO_ID}</a> {COMMENTS_REPLY_TO_BY} {COMMENTS_REPLY_TO_AUTHOR}
			<a href="{COMMENTS_REPLY_CANCEL_URL}" class="cancel-reply btn btn-adm">{COMMENTS_REPLY_CANCEL_LABEL}</a>
		</div>
		<!-- END: COMMENTS_REPLY_NOTICE -->
		
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
