<!-- BEGIN: ADMIN_THANKS -->
<div class="title">
	<span><i class="ic-message-circle"></i></span>
	<h2>{ADMIN_THANKS_TITLE}</h2>
</div>

<div class="content-box">
	<div class="content-box-content content-table">
		<div class="table cells striped resp-table">
			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">ID</div>
					<div class="table-th coltop text-left">{PHP.L.thanks_date}</div>
					<div class="table-th coltop text-left">{PHP.L.thanks_from}</div>
					<div class="table-th coltop text-left">{PHP.L.thanks_to}</div>
					<div class="table-th coltop text-left">{PHP.L.thanks_item}</div>
					<div class="table-th coltop text-center" style="width:60px;">{PHP.L.Delete}</div>
				</div>
			</div>
			<div class="table-body resp-table-body">
				<!-- BEGIN: THANKS_LIST -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td">{THANKS_LIST_ID}</div>
					<div class="table-td text-left resp-table-td">{THANKS_LIST_DATE}</div>
					<div class="table-td text-left resp-table-td">{THANKS_LIST_FROM}</div>
					<div class="table-td text-left resp-table-td">{THANKS_LIST_TO}</div>
					<div class="table-td text-left resp-table-td">{THANKS_LIST_ITEM}</div>
					<div class="table-td text-center resp-table-td">
						<a href="{THANKS_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirm('{PHP.L.adm_confirm_delete}');" class="btn btn-small"><i class="ic-trash"></i></a>
					</div>
				</div>
				<!-- END: THANKS_LIST -->
			</div>
		</div>
		<div style="padding:10px;">
			{ADMIN_THANKS_TOTAL} : {ADMIN_THANKS_TOTALITEMS}
		</div>
		<!-- BEGIN: THANKS_PAGINATION_TP -->
		<div class="paging">
			<ul class="pagination">
				<li class="prev">{THANKS_PAGEPREV}</li>
				{THANKS_PAGINATION}
				<li class="next">{THANKS_PAGENEXT}</li>
			</ul>
		</div>
		<!-- END: THANKS_PAGINATION_TP -->
	</div>
</div>
<!-- END: ADMIN_THANKS -->
