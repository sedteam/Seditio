<!-- BEGIN: ADMIN_COMMENTS -->

<div class="title">
	<span><i class="ic-comments"></i></span>
	<h2>{ADMIN_COMMENTS_TITLE}</h2>
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

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-center" style="width:20px">{PHP.L.Delete}</div>
					<div class="table-th coltop text-left" style="width:20px">#</div>
					<div class="table-th coltop text-left" style="width:40px">{PHP.L.Code}</div>
					<div class="table-th coltop text-left">{PHP.L.Author}</div>
					<div class="table-th coltop text-left" style="width:128px;">{PHP.L.Date}</div>
					<div class="table-th coltop text-left">{PHP.L.Comment}</div>
					<div class="table-th coltop text-center" style="width:20px">{PHP.L.Open}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: COMMENTS_LIST -->

				<div class="table-row resp-table-row">

					<div class="table-td text-center resp-table-td banlist-action">
						<a href="{COMMENTS_LIST_DELETE_URL}" class="btn btn-small"><i class="ic-trash"></i></a>
					</div>

					<div class="table-td text-left resp-table-td banlist-until" data-label="#">
						{COMMENTS_LIST_ID}
					</div>

					<div class="table-td text-left resp-table-td banlist-until" data-label="{PHP.L.Code}">
						{COMMENTS_LIST_CODE}
					</div>

					<div class="table-td text-left resp-table-td banlist-until" data-label="{PHP.L.Author}">
						{COMMENTS_LIST_AUTHOR}
					</div>

					<div class="table-td text-left resp-table-td banlist-until" data-label="{PHP.L.Date}">
						{COMMENTS_LIST_DATE}
					</div>

					<div class="table-td text-left resp-table-td banlist-until" data-label="{PHP.L.Comment}">
						{COMMENTS_LIST_TEXT}
					</div>

					<div class="table-td text-left resp-table-td banlist-until">
						<a href="{COMMENTS_LIST_OPEN_URL}" class="btn btn-small"><i class="ic-arrow-right"></i></a>
					</div>

				</div>

				<!-- END: COMMENTS_LIST -->

			</div>

		</div>

		<div style="padding:10px 10px;">{PHP.L.Total} : {COMMENTS_TOTAL}</div>

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