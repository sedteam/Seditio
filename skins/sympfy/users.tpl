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
				{USERS_TOP_FILTERS}				
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

			<div class="centered">{PHP.skinlang.users.Page} {USERS_TOP_CURRENTPAGE}/ {USERS_TOP_TOTALPAGE} - {USERS_TOP_MAXPERPAGE} {PHP.skinlang.users.usersperpage} - {USERS_TOP_TOTALUSERS} {PHP.skinlang.users.usersinthissection}</div>

			<table class="cells striped">

				<tr>
					<td class="coltop" style="width:20px;">{USERS_TOP_PM}</td>
					<td class="coltop">{USERS_TOP_NAME}</td>
					<td class="coltop" style="width:180px;" colspan="2">{USERS_TOP_MAINGRP}</td>
					<td class="coltop" style="width:128px;">{USERS_TOP_COUNTRY}</td>
					<td class="coltop" style="width:200px;">{USERS_TOP_REGDATE}</td>
				</tr>

				<!-- BEGIN: USERS_ROW -->

				<tr>
					<td>{USERS_ROW_PM}</td>
					<td><strong>{USERS_ROW_NAME}</strong> {USERS_ROW_TAG}</td>
					<td>{USERS_ROW_MAINGRP}</td>
					<td style="width:90px;">{USERS_ROW_MAINGRPSTARS}</td>
					<td>{USERS_ROW_COUNTRYFLAG} {USERS_ROW_COUNTRY}</td>
					<td>{USERS_ROW_REGDATE}</td>
				</tr>

				<!-- END: USERS_ROW -->

			</table>

			<div class="centered">{PHP.skinlang.users.Page} {USERS_TOP_CURRENTPAGE}/ {USERS_TOP_TOTALPAGE} - {USERS_TOP_MAXPERPAGE} {PHP.skinlang.users.usersperpage} - {USERS_TOP_TOTALUSERS} {PHP.skinlang.users.usersinthissection}</div>  
	
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