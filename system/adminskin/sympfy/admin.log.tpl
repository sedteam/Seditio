<!-- BEGIN: ADMIN_LOG -->

<div class="title">
	<span><i class="ic-referers"></i></span>
	<h2>{ADMIN_LOG_TITLE}</h2>
</div>

<div class="content-box">
	<div class="content-box-header">
		<div class="content-box-header-right">
			<form>{ADMIN_LOG_FILTER} {ADMIN_LOG_CLEAR}</form>
		</div>
	</div>
	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left" style="width:10px">#</div>
					<div class="table-th coltop text-left" style="width:150px">{PHP.L.Date} (GMT)</div>
					<div class="table-th coltop text-left">{PHP.L.Ip}</div>
					<div class="table-th coltop text-left">{PHP.L.User}</div>
					<div class="table-th coltop text-left">{PHP.L.Group}</div>
					<div class="table-th coltop text-left">{PHP.L.Log}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: LOG_LIST -->

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td log-id" data-label="#">
						{LOG_LIST_ID}
					</div>
					<div class="table-td text-left resp-table-td log-date" data-label="{PHP.L.Date} (GMT)">
						{LOG_LIST_DATE}
					</div>
					<div class="table-td text-left resp-table-td log-ip" data-label="{PHP.L.Ip}">
						{LOG_LIST_IP}
					</div>
					<div class="table-td text-left resp-table-td log-user" data-label="{PHP.L.User}">
						{LOG_LIST_USER}
					</div>
					<div class="table-td text-left resp-table-td log-group" data-label="{PHP.L.Group}">
						{LOG_LIST_GROUP}
					</div>
					<div class="table-td text-left resp-table-td log-desc" data-label="{PHP.L.Log}">
						<div class="desc">{LOG_LIST_DESC}</div>
					</div>
				</div>

				<!-- END: LOG_LIST -->

			</div>

		</div>

		<!-- BEGIN: LOG_PAGINATION_BM -->

		<div class="paging">
			<ul class="pagination">
				<li class="prev">{LOG_PAGEPREV}</li>
				{LOG_PAGINATION}
				<li class="next">{LOG_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: LOG_PAGINATION_BM -->

	</div>
</div>

<!-- END: ADMIN_LOG -->