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

	<ul class="arrow_list">

		<!-- BEGIN: LIST_ROWCAT -->

		<li style="margin-bottom:8px;">
			<strong><a href="{LIST_ROWCAT_URL}">{LIST_ROWCAT_TITLE}</a> ({LIST_ROWCAT_COUNT})</strong>
			<div class="desc">{LIST_ROWCAT_DESC}</div>
		</li>

		<!-- END: LIST_ROWCAT -->

	</ul>

	<ul class="circle_list">

		<!-- BEGIN: LIST_ROW -->

		<li>
			<strong><a href="{LIST_ROW_URL}">{LIST_ROW_TITLE}</a></strong> {LIST_ROW_FILEICON}<br />
			<span class="desc">{LIST_ROW_DESC} ({LIST_ROW_COUNT} {PHP.skinlang.list.hits})</span>
		</li>

		<!-- END: LIST_ROW -->

	</ul>
  
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