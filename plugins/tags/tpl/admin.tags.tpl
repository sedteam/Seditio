<!-- BEGIN: ADMIN_TAGS -->
<div class="title">
	<span><i class="ic-tag"></i></span>
	<h2>{ADMIN_TAGS_TITLE}</h2>
</div>

{ADMIN_TAGS_MSG}

<div class="content-box">
	<div class="content-box-header">
		<div class="content-box-header-right">
			<form action="{ADMIN_TAGS_SEARCH_ACTION}" method="get" class="form-inline">
				<input type="hidden" name="m" value="tags" />
				<input type="text" name="search" value="{ADMIN_TAGS_SEARCH_VALUE}" placeholder="{PHP.L.tags_search}" />
				<button type="submit" class="btn">{PHP.L.tags_filter}</button>
			</form>
		</div>
	</div>
	<div class="content-box-content content-table">

		<div class="tags-admin-alphafilters">{ADMIN_TAGS_LETTERS}</div>

		<div class="table cells striped resp-table">
			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">
						<a href="{ADMIN_TAGS_SORT_TAG_URL}">{PHP.L.tags_tags}</a>
					</div>
					<div class="table-th coltop text-center" style="width:80px;">
						<a href="{ADMIN_TAGS_SORT_COUNT_URL}">{PHP.L.tags_count}</a>
					</div>
					<div class="table-th coltop text-left" style="width:120px;">{PHP.L.tags_area}</div>
					<div class="table-th coltop text-center" style="width:200px;">{PHP.L.tags_rename}</div>
					<div class="table-th coltop text-center" style="width:60px;">{PHP.L.Delete}</div>
				</div>
			</div>
			<div class="table-body resp-table-body">
				<!-- BEGIN: TAGS_LIST -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td">
						<a href="{TAGS_LIST_TAG_URL}">{TAGS_LIST_TAG_DISPLAY}</a>
					</div>
					<div class="table-td text-center resp-table-td">{TAGS_LIST_COUNT}</div>
					<div class="table-td text-left resp-table-td">{TAGS_LIST_AREAS}</div>
					<div class="table-td text-center resp-table-td tags-rename-cell" style="position:relative;">
						<button type="button" data-sed-popover-trigger data-sed-popover-position="left" class="btn btn-small" title="{PHP.L.tags_rename}"><i class="ic-edit"></i></button>
						<div data-sed-popover-content>
							<form action="{TAGS_LIST_RENAME_ACTION}" method="post" class="form-inline">
								<input type="hidden" name="oldtag" value="{TAGS_LIST_TAG}" />
								<input type="text" name="newtag" value="" placeholder="{PHP.L.tags_admin_newtag}" class="form-control input-sm" style="width:120px;" />
								<button type="submit" class="btn btn-pd">{PHP.L.tags_admin_update}</button>
							</form>
						</div>
					</div>
					<div class="table-td text-center resp-table-td">
						<a href="{TAGS_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirm('{PHP.L.adm_confirm_delete}');" class="btn btn-small"><i class="ic-trash"></i></a>
					</div>
				</div>
				<!-- END: TAGS_LIST -->
			</div>
		</div>

		<div style="padding:10px;">
			{ADMIN_TAGS_TOTAL}: {ADMIN_TAGS_TOTALITEMS}
			&nbsp; | &nbsp;
			<a href="{ADMIN_TAGS_CLEANUP_URL}" class="btn" onclick="return confirm('{PHP.L.adm_confirm_delete}');">{PHP.L.tags_admin_cleanup}</a>
		</div>

		<!-- BEGIN: TAGS_PAGINATION_TP -->
		<div class="paging">
			<ul class="pagination">
				<li class="prev">{TAGS_PAGEPREV}</li>
				{TAGS_PAGINATION}
				<li class="next">{TAGS_PAGENEXT}</li>
			</ul>
		</div>
		<!-- END: TAGS_PAGINATION_TP -->

	</div>
</div>
<!-- END: ADMIN_TAGS -->
