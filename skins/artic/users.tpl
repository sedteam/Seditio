<!-- BEGIN: MAIN -->

<div id="title">

	{USERS_TITLE}

</div>

<div id="subtitle">

	{USERS_TOP_FILTERS}

</div>

<div id="main">

<table class="paging">

	<tr>
		<td class="paging_left">{USERS_TOP_PAGEPREV}</td>
		<td class="paging_center">{PHP.skinlang.users.Page} {USERS_TOP_CURRENTPAGE}/ {USERS_TOP_TOTALPAGE} - {USERS_TOP_MAXPERPAGE} {PHP.skinlang.users.usersperpage} - {USERS_TOP_TOTALUSERS} {PHP.skinlang.users.usersinthissection}</td>
		<td class="paging_right">{USERS_TOP_PAGENEXT}</td>
	</tr>

</table>

<table class="cells">

	<tr>
		<td class="coltop" style="width:20px;">{USERS_TOP_PM}</td>
		<td class="coltop">{USERS_TOP_NAME}</td>
		<td class="coltop" style="width:224px;" colspan="2">{USERS_TOP_MAINGRP}</td>
		<td class="coltop" style="width:128px;">{USERS_TOP_COUNTRY}</td>
		<td class="coltop" style="width:112px;">{USERS_TOP_REGDATE}</td>
	</tr>

	<!-- BEGIN: USERS_ROW -->

	<tr>
		<td>{USERS_ROW_PM}</td>
		<td><strong>{USERS_ROW_NAME}</strong> {USERS_ROW_TAG}</td>
		<td>{USERS_ROW_MAINGRP}</td>
		<td>{USERS_ROW_MAINGRPSTARS}</td>
		<td>{USERS_ROW_COUNTRYFLAG} {USERS_ROW_COUNTRY}</td>
		<td>{USERS_ROW_REGDATE}</td>
	</tr>

	<!-- END: USERS_ROW -->

</table>
       
</div>

<!-- END: MAIN -->