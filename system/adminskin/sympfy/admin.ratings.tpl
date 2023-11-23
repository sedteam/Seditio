<!-- BEGIN: ADMIN_RATINGS -->

<div class="title">
	<span><i class="ic-ratings"></i></span>
	<h2>{ADMIN_RATINGS_TITLE}</h2>
</div>

<!-- BEGIN: RATINGS_BUTTONS -->

<ul class="shortcut-buttons-set">

	<!-- BEGIN: RATINGS_BUTTONS_CONFIG -->
	<li><a class="shortcut-button" href="{BUTTON_RATINGS_CONFIG_URL}"><span>
				<i class="ic-settings ic-3x"></i><br />
				{PHP.L.Configuration}
			</span></a></li>
	<!-- END: RATINGS_BUTTONS_CONFIG -->

</ul>

<div class="clear"></div>

<!-- END: RATINGS_BUTTONS -->

<div class="content-box">

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-center" style="width:20px;">{PHP.L.Delete}</div>
					<div class="table-th coltop text-left">{PHP.L.Code}</div>
					<div class="table-th coltop text-left">{PHP.L.Date} (GMT)</div>
					<div class="table-th coltop text-left">{PHP.L.Votes}</div>
					<div class="table-th coltop text-left">{PHP.L.Rating}</div>
					<div class="table-th coltop text-center" style="width:20px;">{PHP.L.Open}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: RATINGS_LIST -->

				<div class="table-row resp-table-row">
					<div class="table-td text-center resp-table-td ratings-action">
						<a href="{RATINGS_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="ic-trash"></i></a>
					</div>
					<div class="table-td text-left resp-table-td ratings-code" data-label="{PHP.L.Code}">
						{RATINGS_LIST_CODE}
					</div>
					<div class="table-td text-left resp-table-td ratings-date" data-label="{PHP.L.Date} (GMT)">
						{RATINGS_LIST_CREATIONDATE}
					</div>
					<div class="table-td text-left resp-table-td ratings-votes" data-label="{PHP.L.Votes}">
						{RATINGS_LIST_VOTES}
					</div>
					<div class="table-td text-left resp-table-td ratings-val" data-label="{PHP.L.Rating}">
						{RATINGS_LIST_AVERAGE}
					</div>
					<div class="table-td text-center resp-table-td ratings-open" data-label="{PHP.L.Open}">
						<a href="{RATINGS_LIST_URL}"><i class="ic-arrow-right"></i></a>
					</div>
				</div>

				<!-- END: RATINGS_LIST -->

			</div>

		</div>

		<div style="padding:10px;">
			{PHP.L.adm_ratings_totalitems} : {ADMIN_RATINGS_TOTALITEMS}<br />
			{PHP.L.adm_ratings_totalvotes} : {ADMIN_RATINGS_TOTALVOTES}
		</div>

		<script>
			function confirmDelete() {
				if (confirm("{PHP.L.adm_confirm_delete}")) {
				return true;
			} else {
				return false;
			}
			}
		</script>

		<!-- BEGIN: RATINGS_PAGINATION_BM -->

		<div class="paging">
			<ul class="pagination">
				<li class="prev">{RATINGS_PAGEPREV}</li>
				{RATINGS_PAGINATION}
				<li class="next">{RATINGS_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: RATINGS_PAGINATION_BM -->

	</div>
</div>

<!-- END: ADMIN_RATINGS -->