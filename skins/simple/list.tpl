<!-- BEGIN: MAIN -->

<div id="title">

  <h2>{LIST_PAGETITLE}</h2>

</div>

<div id="subtitle">

	{LIST_CATDESC}
	<div class="clear">{LIST_SUBMITNEWPAGE}</div>
	
</div>

<div id="list-text">

	{LIST_CATTEXT}

</div>

<div id="page">

	<ul>

	<!-- BEGIN: LIST_ROWCAT -->

		<li style="margin-bottom:8px;">
			<strong><a href="{LIST_ROWCAT_URL}">{LIST_ROWCAT_TITLE}</a></strong>
			<div class="desc">{LIST_ROWCAT_DESC}</div>
		</li>

	<!-- END: LIST_ROWCAT -->

	</ul>

  <!-- BEGIN: LIST_PAGINATION_TP -->
  
	<div class="paging">
    <ul class="pagination">
      <li class="prev">{LIST_TOP_PAGEPREV}</li>
      {LIST_TOP_PAGINATION}
      <li class="next">{LIST_TOP_PAGENEXT}</li>
    </ul>
  </div>
  
  <!-- END: LIST_PAGINATION_TP -->

	<table class="cells striped">

		<tr>
			<td class="coltop">{LIST_TOP_TITLE}</td>
			<td class="coltop" style="width:128px;">{LIST_TOP_DATE}</td>
			<td class="coltop" style="width:120px;">{LIST_TOP_COUNT}</td>
			<td class="coltop" style="width:128px;">{PHP.skinlang.list.Comments}</td>
		</tr>

		<!-- BEGIN: LIST_ROW -->

		<tr>
			<td>
			<strong><a href="{LIST_ROW_URL}">{LIST_ROW_TITLE}</a></strong> {LIST_ROW_FILEICON}<br />
			<span class="desc">{LIST_ROW_DESC}</span>
			</td>

			<td class="centerall">{LIST_ROW_DATE}</td>
			<td class="centerall">{LIST_ROW_COUNT}</td>
			<td class="centerall">{LIST_ROW_COMMENTS}</td>
		</tr>

	<!-- END: LIST_ROW -->

	</table>

  <!-- BEGIN: LIST_PAGINATION_BM -->
  
	<div class="paging">
    <ul class="pagination">
      <li class="prev">{LIST_TOP_PAGEPREV}</li>
      {LIST_TOP_PAGINATION}
      <li class="next">{LIST_TOP_PAGENEXT}</li>
    </ul>
  </div>
  
  <!-- END: LIST_PAGINATION_BM -->

</div>

<!-- END: MAIN -->