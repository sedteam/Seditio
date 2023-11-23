<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{USERS_SHORTTITLE}</h1>

			<div class="section-desc">
				{USERS_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<div class="filters">
				<div class="filters-form">{USERS_TOP_FILTERS}</div>
				<div class="filters-alphafilters">{USERS_TOP_ALPHAFILTERS}</div>
			</div>

			<!-- BEGIN: USERS_PAGINATION_TP -->

			<div class="pagination-box">

				<ul class="pagination">
					<li class="page-item">{USERS_TOP_PAGEPREV}</li>
					{USERS_TOP_PAGINATION}
					<li class="page-item">{USERS_TOP_PAGENEXT}</li>
				</ul>

			</div>

			<!-- END: USERS_PAGINATION_TP -->

			<div class="text-center descr">{PHP.skinlang.users.Page} {USERS_TOP_CURRENTPAGE}/ {USERS_TOP_TOTALPAGE} - {USERS_TOP_MAXPERPAGE} {PHP.skinlang.users.usersperpage} - {USERS_TOP_TOTALUSERS} {PHP.skinlang.users.usersinthissection}</div>

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">

					<div class="table-row resp-table-row">
						<div class="table-th coltop text-center" style="width:120px;">{USERS_TOP_PM}</div>
						<div class="table-th coltop text-left">{USERS_TOP_NAME}</div>
						<div class="table-th coltop text-left" style="width:210px;">{USERS_TOP_MAINGRP}</div>
						<div class="table-th coltop text-left" style="width:128px;">{USERS_TOP_COUNTRY}</div>
						<div class="table-th coltop text-center" style="width:200px;">{USERS_TOP_REGDATE}</div>
					</div>

				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: USERS_ROW -->

					<div class="table-row resp-table-row">
						<div class="table-td text-center resp-table-td users-pm">
							{USERS_ROW_PM}
						</div>
						<div class="table-td text-left resp-table-td users-name" data-label="{PHP.L.Username}">
							<strong>{USERS_ROW_NAME}</strong> {USERS_ROW_TAG}
						</div>
						<div class="table-td text-left resp-table-td users-maingrp" data-label="{PHP.L.Maingroup}">
							<div class="maingrp-table">
								<div class="maingrp-td-title">{USERS_ROW_MAINGRP}</div>
								<div class="maingrp-td-icon">{USERS_ROW_MAINGRPSTARS}</div>
							</div>
						</div>
						<div class="table-td text-left resp-table-td users-country" data-label="{PHP.L.Country}">
							{USERS_ROW_COUNTRYFLAG} {USERS_ROW_COUNTRY}
						</div>
						<div class="table-td text-center resp-table-td users-regdate" data-label="{PHP.L.Registered}">
							{USERS_ROW_REGDATE}
						</div>
					</div>

					<!-- END: USERS_ROW -->

				</div>

			</div>

			<div class="text-center descr">{PHP.skinlang.users.Page} {USERS_TOP_CURRENTPAGE}/ {USERS_TOP_TOTALPAGE} - {USERS_TOP_MAXPERPAGE} {PHP.skinlang.users.usersperpage} - {USERS_TOP_TOTALUSERS} {PHP.skinlang.users.usersinthissection}</div>

			<!-- BEGIN: USERS_PAGINATION_BM -->

			<div class="pagination-box">

				<ul class="pagination">
					<li class="page-item">{USERS_TOP_PAGEPREV}</li>
					{USERS_TOP_PAGINATION}
					<li class="page-item">{USERS_TOP_PAGENEXT}</li>
				</ul>

			</div>

			<!-- END: USERS_PAGINATION_BM -->

		</div>

	</div>

</main>

<!-- END: MAIN -->