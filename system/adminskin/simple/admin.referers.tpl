<!-- BEGIN: ADMIN_REFERERS -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.Referers}</h3>
	</div>
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

		<table class="cells striped">

			<thead>
				<tr>
					<th class="coltop">{PHP.L.Referer}</th>
					<th class="coltop" style="width:50px;">{PHP.L.Hits}</th>
				</tr>
			</thead>

			<!-- BEGIN: REFERERS_LIST -->

			<tr>
				<td colspan="2"><a href="{REFERER_GROUP_URL}">{REFERER_GROUP_URL}</a></td>
			</tr>

			<!-- BEGIN: REFERERS_LIST_ITEM -->

			<tr>
				<td><a href="{REFERER_URL}">{REFERER_TITLE}</a></td>
				<td>{REFERER_COUNT}</td>
			</tr>

			<!-- END: REFERERS_LIST_ITEM -->

			<!-- END: REFERERS_LIST -->

			<!-- BEGIN: REFERERS_NONE -->

			<tr>
				<td colspan="2">{PHP.L.None}</td>
			</tr>

			<!-- END: REFERERS_NONE -->

		</table>

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