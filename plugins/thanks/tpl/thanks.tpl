<!-- BEGIN: MAIN -->
<main id="plugins">

	<div class="container">
		<div class="section-title">
			{THANKS_BREADCRUMBS}
			<h1>{THANKS_TITLE}</h1>
		</div>
		<div class="section-body">

			<!-- BEGIN: THANKS_LIST -->
			<div class="table cells striped resp-table">
				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left">{PHP.L.thanks_date}</div>
						<div class="table-th coltop text-left">{PHP.L.thanks_from}</div>
						<div class="table-th coltop text-left">{PHP.L.thanks_for}</div>
						<div class="table-th coltop text-left">{PHP.L.thanks_category}</div>
						<div class="table-th coltop text-left">{PHP.L.thanks_item}</div>
					</div>
				</div>
				<div class="table-body resp-table-body">
					<!-- BEGIN: THANKS_LIST_ROW -->
					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.thanks_date}">{THANKS_LIST_ROW_DATE}</div>
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.thanks_from}">{THANKS_LIST_ROW_FROM}</div>
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.thanks_for}">{THANKS_LIST_ROW_TYPE}</div>
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.thanks_category}">{THANKS_LIST_ROW_CATEGORY}</div>
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.thanks_item}">{THANKS_LIST_ROW_ITEM}</div>
					</div>
					<!-- END: THANKS_LIST_ROW -->
				</div>
			</div>
			
			<div class="pagination-box">

				<ul class="pagination">
					{THANKS_PAGINATION}
				</ul>

			</div>
			
			<!-- END: THANKS_LIST -->

			<!-- BEGIN: THANKS_TOP -->
			<div class="table cells striped resp-table">
				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left" style="width:40px;">#</div>
						<div class="table-th coltop text-left">{PHP.L.thanks_to}</div>
						<div class="table-th coltop text-left">{PHP.L.thanks_title_short}</div>
						<div class="table-th coltop text-left" style="width:50px;"></div>
					</div>
				</div>
				<div class="table-body resp-table-body">
					<!-- BEGIN: THANKS_TOP_ROW -->
					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.Rank}">{THANKS_TOP_ROW_NUM}</div>
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.thanks_to}">{THANKS_TOP_ROW_USER}</div>
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.thanks_title_short}">{THANKS_TOP_ROW_CNT}</div>
						<div class="table-td text-left resp-table-td" data-label="{PHP.L.Open}">{THANKS_TOP_ROW_LINK}</div>
					</div>
					<!-- END: THANKS_TOP_ROW -->
				</div>
			</div>
			<!-- END: THANKS_TOP -->

			<!-- BEGIN: THANKS_EMPTY -->
			<p>{PHP.L.thanks_none}</p>
			<!-- END: THANKS_EMPTY -->

		</div>
	</div>
	
</main>
<!-- END: MAIN -->
