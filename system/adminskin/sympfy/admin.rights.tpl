<!-- BEGIN: ADMIN_RIGHTS -->

	<div class="title">
		<span><i class="ic-forums"></i></span><h2>{ADMIN_RIGHTS_TITLE}</h2>
	</div>

	<div class="content-box">
		<!-- BEGIN: RIGHTS_COPY -->
		<div class="content-box-header">										
			<div class="content-box-header-right">
				<form id="copyrights" action="{RIGHTS_UPDATE_SEND}" method="post">
					{RIGHTS_COPYRIGHTSCONF} {PHP.L.adm_copyrightsfrom} : {RIGHTS_COPYRIGHTSFROM} 
					<button type="submit" class="submit btn">{PHP.L.Update}</button>
				</form>	
			</div>	
		</div> 
		<!-- END: RIGHTS_COPY -->

		<div class="content-box-content content-table">  

		<form id="saverights" action="{RIGHTS_UPDATE_SEND}" method="post">

		<!-- BEGIN: RIGHTS_GROUP -->

		<table class="cells striped" style="margin-bottom:15px;">

		<thead>
			
		<tr>	
			<th class="coltop">{RIGHTS_GROUP_TITLE}</th>
			<th class="coltop" style="width:128px; text-align:center;">{PHP.L.adm_rightspergroup}</th>
			<th style="width:24px;" class="coltop"><img src="system/img/admin/auth_r.gif" alt="" /></th>
			<th style="width:24px;" class="coltop"><img src="system/img/admin/auth_w.gif" alt="" /></th>
			
			<!-- BEGIN: ADVANCED_RIGHTS -->

			<th style="width:24px;" class="coltop"><img src="system/img/admin/auth_1.gif" alt="" /></th>
			<th style="width:24px;" class="coltop"><img src="system/img/admin/auth_2.gif" alt="" /></th>
			<th style="width:24px;" class="coltop"><img src="system/img/admin/auth_3.gif" alt="" /></th>
			<th style="width:24px;" class="coltop"><img src="system/img/admin/auth_4.gif" alt="" /></th>
			<th style="width:24px;" class="coltop"><img src="system/img/admin/auth_5.gif" alt="" /></th>

			<!-- END: ADVANCED_RIGHTS -->

			<th style="width:24px;" class="coltop"><img src="system/img/admin/auth_a.gif" alt="" /></th>
			<th class="coltop" style="width:80px; text-align:center;">{PHP.L.adm_setby}</th>
		</tr>
		
		</thead>

		<!-- BEGIN: RIGHTS_LIST -->
		<tr>
			<td><a href="{RIGHTS_LIST_URL}"><span class="icon"><i class="ic-{RIGHTS_LIST_CODE}"></i></span> {RIGHTS_LIST_TITLE}</a></td>
			<td style="text-align:center;"><a href="{RIGHTS_LIST_RIGHTBYITEM_URL}"><i class="ic-lock"></i></a></td>
			
			<!-- BEGIN: RIGHTS_LIST_OPTIONS -->
			<td style="text-align:center;">
					{RIGHTS_OPTIONS}
			</td>	
			<!-- END: RIGHTS_LIST_OPTIONS -->
				
			<td style="text-align:center;">{RIGHTS_LIST_SETBYUSER}</td>
		</tr>
		<!-- END: RIGHTS_LIST -->
		
		<!-- BEGIN: RIGHTS_UPDATE -->
		<tr>
			<td colspan="{RIGHTS_UPDATECOLUMN_COUNT}" style="text-align:center;">
				<input type="submit" class="submit btn" value="{PHP.L.Update}" />
			</td>
		</tr>
		<!-- END: RIGHTS_UPDATE -->


		</table>
		<!-- END: RIGHTS_GROUP -->
		
		</form>

		</div>
</div>

<!-- END: ADMIN_RIGHTS -->
