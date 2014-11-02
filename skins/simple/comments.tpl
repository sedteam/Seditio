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

<div class="commentlist">

<!-- BEGIN: COMMENTS_ROW -->

				<div class="comments {COMMENTS_ROW_ODDEVEN}">
            <div class="col-row">
    					<div class="comment-by colleft width-40">              
                  <a href="{COMMENTS_ROW_URL}" id="c{COMMENTS_ROW_ID}"><img src="skins/{PHP.skin}/img/system/icon-comment.gif" alt="">{COMMENTS_ROW_ORDER}.</a>
    		          {PHP.skinlang.comments.Postedby} <strong>{COMMENTS_ROW_AUTHOR}            
               </strong>
               </div>
              <div class="comment-date colright width-60">{COMMENTS_ROW_ADMIN} {COMMENTS_ROW_DATE} </div>
            </div>
            <div class="comment-text">
						  	{COMMENTS_ROW_TEXT}
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

<!-- BEGIN: COMMENTS_NEWCOMMENT -->

	<form action="{COMMENTS_FORM_SEND}" method="post" name="newcomment">
	<div class="boxed">	
    <h4>{PHP.skinlang.comments.Comment}</h4>
		{COMMENTS_FORM_TEXT}
		<p><input type="submit" class="submit btn btn-big" value="{PHP.skinlang.comments.Send}"></p>
	</div>
  </form>

<!-- END: COMMENTS_NEWCOMMENT -->

<!-- BEGIN: COMMENTS_EDITCOMMENT -->

	<form action="{COMMENTS_EDIT_FORM_SEND}" method="post" name="editcomment">
	<div class="boxed">
		<h4>{PHP.skinlang.comments.Commentedit}:</h4>
		<a name="c{COMMENTS_EDIT_FORM_ID}"></a>
		{COMMENTS_EDIT_FORM_TEXT}
		<p><input type="submit" class="submit btn btn-big" value="{PHP.skinlang.comments.Update}"></p>
	</div>
	</form>

<!-- END: COMMENTS_EDITCOMMENT -->

<!-- BEGIN: COMMENTS_DISABLE -->

	<div class="block">
		{COMMENTS_DISABLETEXT}
	</div>

<!-- END: COMMENTS_DISABLE -->

<!-- END: COMMENTS -->