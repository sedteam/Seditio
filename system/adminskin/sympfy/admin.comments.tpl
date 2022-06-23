<!-- BEGIN: ADMIN_COMMENTS -->

<div class="title">
	<span><i class="ic-comments"></i></span><h2>{ADMIN_COMMENTS_TITLE}</h2>
</div>

<div class="content-box">

	<div class="content-box-header">					
		<h3>{PHP.L.viewdeleteentries}</h3>													
	</div>

	<div class="content-box-content content-table"> 
	
		<!-- BEGIN: COMMENTS_PAGINATION_TP -->
		
		<div class="paging">
			<ul class="pagination">
			  <li class="prev">{COMMENTS_PAGEPREV}</li>
			  {COMMENTS_PAGINATION}
			  <li class="next">{COMMENTS_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: COMMENTS_PAGINATION_TP -->

		<table class="cells striped">
			<tr>
				<th style="width:40px;" class="coltop">{PHP.L.Delete}</th>
				<th style="width:40px;" class="coltop">#</th>
				<th style="width:40px;" class="coltop">{PHP.L.Code}</th>
				<th class="coltop">{PHP.L.Author}</th>
				<th style="width:128px;" class="coltop">{PHP.L.Date}</th>
				<th class="coltop">{PHP.L.Comment}</th>
				<th style="width:64px;" class="coltop">{PHP.L.Open}</th>
			</tr>

			<!-- BEGIN: COMMENTS_LIST -->

			<tr>
				<td style="text-align:center;"><a href="{COMMENTS_LIST_DELETE_URL}" class="btn btn-small"><i class="ic-trash"></i></a></td>
				<td style="text-align:center;">{COMMENTS_LIST_ID}</td>
				<td style="text-align:center;">{COMMENTS_LIST_CODE}</td>
				<td>{COMMENTS_LIST_AUTHOR}</td>
				<td style="text-align:center;">{COMMENTS_LIST_DATE}</td>
				<td>{COMMENTS_LIST_TEXT}</td>
				<td style="text-align:center;"><a href="{COMMENTS_LIST_OPEN_URL}" class="btn btn-small"><i class="ic-arrow-right"></i></a></td>
			</tr>

			<!-- END: COMMENTS_LIST -->

			<tr>
				<td colspan="7">{PHP.L.Total} : {COMMENTS_TOTAL}</td>
			</tr>
		</table>

		<!-- BEGIN: COMMENTS_PAGINATION_BM -->
		
		<div class="paging">
			<ul class="pagination">
			  <li class="prev">{COMMENTS_PAGEPREV}</li>
			  {COMMENTS_PAGINATION}
			  <li class="next">{COMMENTS_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: COMMENTS_PAGINATION_BM -->
		
	</div>
	
</div>
	
<!-- END: ADMIN_COMMENTS -->
