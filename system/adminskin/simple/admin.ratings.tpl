<!-- BEGIN: ADMIN_RATINGS -->

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
	<div class="content-box-header">
		<h3>{PHP.L.Ratings}</h3>
	</div>
	<div class="content-box-content">

		<!-- BEGIN: RATINGS_PAGINATION_TP -->

		<div class="paging">
			<ul class="pagination">
				<li class="prev">{RATINGS_PAGEPREV}</li>
				{RATINGS_PAGINATION}
				<li class="next">{RATINGS_PAGENEXT}</li>
			</ul>
		</div>

		<!-- END: RATINGS_PAGINATION_TP -->

		<table class="cells striped">

			<thead>
				<tr>
					<th class="coltop" style="width:40px;">{PHP.L.Delete}</th>
					<th class="coltop">{PHP.L.Code}</th>
					<th class="coltop">{PHP.L.Date} (GMT)</th>
					<th class="coltop">{PHP.L.Votes}</th>
					<th class="coltop">{PHP.L.Rating}</th>
					<th class="coltop" style="width:64px;">{PHP.L.Open}</th>
				</tr>
			</thead>

			<tbody>

				<!-- BEGIN: RATINGS_LIST -->

				<tr>
					<td style="text-align:center;"><a href="{RATINGS_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="ic-trash"></i></a></td>
					<td style="text-align:center;">{RATINGS_LIST_CODE}</td>
					<td style="text-align:center;">{RATINGS_LIST_CREATIONDATE}</td>
					<td style="text-align:center;">{RATINGS_LIST_VOTES}</td>
					<td style="text-align:center;">{RATINGS_LIST_AVERAGE}</td>
					<td style="text-align:center;"><a href="{RATINGS_LIST_URL}"><img src="system/img/admin/jumpto.png" alt="" /></a></td>
				</tr>

				<!-- END: RATINGS_LIST -->

			</tbody>

			<tfoot>
				<tr>
					<td colspan="6">
						{PHP.L.adm_ratings_totalitems} : {ADMIN_RATINGS_TOTALITEMS}<br />
						{PHP.L.adm_ratings_totalvotes} : {ADMIN_RATINGS_TOTALVOTES}
					</td>
				</tr>
			</tfoot>

		</table>

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