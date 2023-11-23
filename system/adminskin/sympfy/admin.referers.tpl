<!-- BEGIN: ADMIN_REFERERS -->

<div class="title">
	<span><i class="ic-referers"></i></span>
	<h2>{ADMIN_REFERERS_TITLE}</h2>
</div>

<div class="content-box">

	<div class="content-box-content">

		<!-- BEGIN: REFERERS_PURGE -->

		<ul class="arrow_list">
			<li>{PHP.L.adm_purgeall} : [<a href="{REFERERS_PURGEALL_URL}">x</a>]</li>
			<li>{PHP.L.adm_ref_lowhits} : [<a href="{REFERERS_PURGE_LOWHITS_URL}">x</a>]</li>
		</ul>

		<!-- END: REFERERS_PURGE -->

		<!-- BEGIN: REFERERS_PAGINATION_TP -->

		<div class="paging">
			<ul class="pagination">
				<li class="prev">{REFERERS_PAGEPREV}</li>
				{REFERERS_PAGINATION}
				<li class="next">{REFERERS_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: REFERERS_PAGINATION_TP -->

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">{PHP.L.Referer}</div>
					<div class="table-th coltop text-left" style="width:50px">{PHP.L.Hits}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: REFERERS_LIST -->

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td referers-title" data-label="{PHP.L.Referer}">
						<a href="{REFERER_GROUP_URL}"><strong>{REFERER_GROUP_URL}</strong></a>
					</div>
					<div class="table-td text-left resp-table-td referers-code">

					</div>
				</div>

				<!-- BEGIN: REFERERS_LIST_ITEM -->

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td referers-title" data-label="{PHP.L.Referer}">
						<a href="{REFERER_URL}">{REFERER_TITLE}</a>
					</div>
					<div class="table-td text-left resp-table-td referers-code" data-label="{PHP.L.Hits}">
						{REFERER_COUNT}
					</div>
				</div>

				<!-- END: REFERERS_LIST_ITEM -->

				<!-- END: REFERERS_LIST -->

				<!-- BEGIN: REFERERS_NONE -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td referers-title">
						<div class="desc">{PHP.L.None}</div>
					</div>
					<div class="table-td text-left resp-table-td referers-code">

					</div>
				</div>
				<!-- END: REFERERS_NONE -->

			</div>

		</div>

		<!-- BEGIN: REFERERS_PAGINATION_BM -->

		<div class="paging">
			<ul class="pagination">
				<li class="prev">{REFERERS_PAGEPREV}</li>
				{REFERERS_PAGINATION}
				<li class="next">{REFERERS_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: REFERERS_PAGINATION_BM -->

	</div>

</div>

<!-- END: ADMIN_REFERERS -->