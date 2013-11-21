<!-- BEGIN: COMMENTS -->


<!-- BEGIN: COMMENTS_EMPTY -->

	<div class="block">

		{COMMENTS_EMPTYTEXT}

	</div>

<!-- END: COMMENTS_EMPTY -->

<!-- BEGIN: COMMENTS_ERROR -->

	<div class="error">

		{COMMENTS_ERROR_BODY}

	</div>

<!-- END: COMMENTS_ERROR -->

<!-- BEGIN: COMMENTS_ROW -->

	<div class="block">

		<span class="title">
		<a href="{COMMENTS_ROW_URL}" id="c{COMMENTS_ROW_ID}"><img src="skins/{PHP.skin}/img/system/icon-comment.gif" alt=""> {COMMENTS_ROW_ORDER}.</a>
		   {PHP.skinlang.comments.Postedby} {COMMENTS_ROW_AUTHOR}</span>   {COMMENTS_ROW_DATE}   {COMMENTS_ROW_ADMIN}    {COMMENTS_ROW_RATE}

		<div style="padding:4px;">
			{COMMENTS_ROW_TEXT}
		</div>

	</div>

<!-- END: COMMENTS_ROW -->

	<table class="paging">
		<tr>
			<td class="paging_left">{COMMENTS_PAGEPREV}</td>
			<td class="paging_center">{COMMENTS_PAGINATION}</td>
			<td class="paging_right">{COMMENTS_PAGENEXT}</td>
		</tr>
	</table>

<!-- BEGIN: COMMENTS_NEWCOMMENT -->

	<form action="{COMMENTS_FORM_SEND}" method="post" name="newcomment">

	<div class="block">

		<h4>{PHP.skinlang.comments.Comment}</h4>

		{COMMENTS_FORM_TEXT}

		<p><input type="submit" value="{PHP.skinlang.comments.Send}"></p>

	</div>

	</form>

<!-- END: COMMENTS_NEWCOMMENT -->

<!-- BEGIN: COMMENTS_EDITCOMMENT -->

	<form action="{COMMENTS_EDIT_FORM_SEND}" method="post" name="editcomment">

	<div class="block">

		<h4>{PHP.skinlang.comments.Commentedit}</h4>
		<a name="c{COMMENTS_EDIT_FORM_ID}"></a>
		{COMMENTS_EDIT_FORM_TEXT}

		<p><input type="submit" value="{PHP.skinlang.comments.Update}"></p>

	</div>

	</form>

<!-- END: COMMENTS_EDITCOMMENT -->

<!-- BEGIN: COMMENTS_DISABLE -->

	<div class="block">

		{COMMENTS_DISABLETEXT}

	</div>

<!-- END: COMMENTS_DISABLE -->

<!-- END: COMMENTS -->