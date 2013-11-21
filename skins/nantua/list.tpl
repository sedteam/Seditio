<!-- BEGIN: MAIN -->

<div id="title_back">

<div id="title">

	{LIST_PAGETITLE}

</div>

<div id="subtitle">

	{LIST_CATDESC}<br />{LIST_SUBMITNEWPAGE}

</div>

</div>

<div id="main">

	<ul>

	<!-- BEGIN: LIST_ROWCAT -->

		<li style="margin-bottom:8px;">
			<strong><a href="{LIST_ROWCAT_URL}">{LIST_ROWCAT_TITLE}</a> ({LIST_ROWCAT_COUNT})</strong>
			<div class="desc">{LIST_ROWCAT_DESC}</div>
		</li>

	<!-- END: LIST_ROWCAT -->

	</ul>

	<table class="cells">

		<tr>
			<td class="coltop">{LIST_TOP_TITLE} {LIST_TOP_COUNT}</td>
			<td class="coltop" style="width:128px;">{LIST_TOP_DATE}</td>
			<td class="coltop" style="width:80px;">{LIST_TOP_COUNT}</td>
			<td class="coltop" style="width:128px;">{PHP.skinlang.list.Comments}</td>
		</tr>

		<!-- BEGIN: LIST_ROW -->

		<tr>
			<td>
			<strong><a href="{LIST_ROW_URL}">{LIST_ROW_TITLE}</a></strong> {LIST_ROW_FILEICON}<br />
			<span class="desc">{LIST_ROW_DESC}</span>
			</td>

			<td class="centerall">{LIST_ROW_DATE}</td>
			<td class="centerall">{LIST_ROW_COUNT}</td>
			<td class="centerall">{LIST_ROW_COMMENTS}</td>
		</tr>

	<!-- END: LIST_ROW -->

	</table>

	<table class="paging">

		<tr>
			<td class="paging_left">{LIST_TOP_PAGEPREV}</td>
			<td class="paging_center">{LIST_TOP_PAGINATION}</td>
			<td class="paging_right">{LIST_TOP_PAGENEXT}</td>
		</tr>

	</table>

</div>

<!-- END: MAIN -->