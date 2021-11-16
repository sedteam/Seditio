<!-- BEGIN: ADMIN_PAGE -->

	<!-- BEGIN: STRUCTURE_UPDATE -->
	<div class="content-box">
	
	<div class="content-box-header">
		<h3>{PHP.L.editdeleteentries}</h3>
	</div>
	
	<div class="content-box-content">
			
    	<form id="savestructure" action="{STRUCTURE_UPDATE_SEND}" method="post">
     
    	<table class="cells striped">
			
    	<tr>
			<td>{PHP.L.Code} :</td>
			<td>{STRUCTURE_UPDATE_CODE}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Path} :</td>
			<td>{STRUCTURE_UPDATE_PATH}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Title} :</td>
			<td>{STRUCTURE_UPDATE_TITLE}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Description} :</td>
			<td>{STRUCTURE_UPDATE_DESC}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Icon} :</td>
			<td>{STRUCTURE_UPDATE_ICON}</td>
		</tr>
    	
    	<tr>
			<td colspan="2">{PHP.L.Text} :<br />{STRUCTURE_UPDATE_TEXT}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Group} :</td>
			<td>{STRUCTURE_UPDATE_GROUP}</td>
		</tr>
		
    	<tr>
			<td>{PHP.L.adm_tpl_mode} :</td>
			<td>{STRUCTURE_UPDATE_TPL}</td>
		</tr>
    	<tr>
			<td>{PHP.L.adm_enablecomments} :</td>
			<td>{STRUCTURE_UPDATE_ALLOWCOMMENTS}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.adm_enableratings} :</td>
			<td>{STRUCTURE_UPDATE_ALLOWRATINGS}</td>
		</tr>
    	
    	<tr>
			<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
		</tr>
    	
    	</table>
    	
    	</form>
		
	</div>
	</div>		
	<!-- END: STRUCTURE_UPDATE -->
	
	<!-- BEGIN: PAGE_STRUCTURE -->
	
	<div class="content-box sedtabs">
		<div class="content-box-header">					
			<h3>{PHP.L.Structure}</h3>					
			<ul class="content-box-tabs">
				  <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Structure}</a></li>
				  <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.addnewentry}</a></li>
			</ul>					
			<div class="clear"></div>					
		</div>    

		<div class="content-box-content">
		<div class="tab-content default-tab" id="tab1">

		<form id="savestructure" action="{PAGE_STRUCTURE_SEND}" method="post">  
      
    	<table class="cells striped">
    	
    	<thead>
		<tr>
			<th class="coltop">{PHP.L.Delete}</th>
			<th class="coltop">{PHP.L.Code}</th>
			<th class="coltop">{PHP.L.Title}</th>
			<th class="coltop">{PHP.L.Path}</th>
			<th class="coltop">{PHP.L.TPL}</th>
			<th class="coltop">{PHP.L.Group}</th>
			<th class="coltop">{PHP.L.Pages}</th>
			<th class="coltop">{PHP.L.Rights}</th>
		</tr>
		</thead>
    	
    	<!-- BEGIN: STRUCTURE_LIST -->
    	
		<tr>
			<td style="text-align:center;">{STRUCTURE_LIST_DELETE}</td>
			<td>{STRUCTURE_LIST_CODE}</td>
			<td>{STRUCTURE_LIST_TITLE}</td>
			<td>{STRUCTURE_LIST_PATH}</td>
			<td>{STRUCTURE_LIST_TPL}</td>    
			<td style="text-align:center;">{STRUCTURE_LIST_GROUP}</td>          
			<td style="text-align:right;">{STRUCTURE_LIST_PAGECOUNT} <a href="{STRUCTURE_LIST_OPEN_URL}" class="btn btn-small"><i class="fa fa-arrow-right"></i></a></td>
			<td style="text-align:center;"><a href="{STRUCTURE_LIST_RIGHTS_URL}" class="btn btn-small"><i class="fa fa-unlock-alt"></i></a></td>
		</tr>
    	
		<!-- END: STRUCTURE_LIST -->
		
    	<tr>
			<td colspan="9"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
		</tr>
    	
    	</table>
    	
    	</form>
		
		</div>
		
		<div class="tab-content" id="tab2">

    	<h4>{PHP.L.addnewentry}</h4>
    	 
    	<form id="addstructure" action="{PAGE_STRUCTURE_ADD_SEND}" method="post">
      
    	<table class="cells striped">
			
    	<tr>
			<td style="width:160px;">{PHP.L.Code} :</td>
			<td>{PAGE_STRUCTURE_ADD_CODE} {PHP.L.adm_required}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Path} :</td>
			<td>{PAGE_STRUCTURE_ADD_PATH} {PHP.L.adm_required}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Title} :</td>
			<td>{PAGE_STRUCTURE_ADD_TITLE} {PHP.L.adm_required}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Description} :</td>
			<td>{PAGE_STRUCTURE_ADD_DESC}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Icon} :</td>
			<td>{PAGE_STRUCTURE_ADD_ICON}</td>
		</tr>
    	
    	<tr>
			<td>{PHP.L.Group} :</td>
			<td>{PAGE_STRUCTURE_ADD_GROUP}</td>
		</tr>
    	
    	<tr>
			<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Add}" /></td>
		</tr>
    	
    	</table>
    	
    	</form> 

		</div>
		</div>
	</div> 		
    	    	
    <!-- END: PAGE_STRUCTURE -->
    
    <!-- BEGIN: PAGE_SORTING -->

    <div class="content-box">
    
		<div class="content-box-header">					
			<h3>{PHP.L.adm_sortingorder}</h3>					    
		</div>
    
		<div class="content-box-content">
    
		<form id="chgorder" action="{PAGE_SORTING_SEND}" method="post">
    
		<table class="cells striped">
		<thead>
			<tr>
				<th class="coltop">{PHP.L.Code}</th>
				<th class="coltop">{PHP.L.Path}</th>
				<th class="coltop">{PHP.L.Title}</th>
				<th class="coltop">{PHP.L.Order}</th>
			</tr>
		</thead>
    
	<!-- BEGIN: SORTING_STRUCTURE_LIST -->
	
	<tr>
		<td>{STRUCTURE_LIST_CODE}</td>
		<td>{STRUCTURE_LIST_PATH}</td>
		<td>{STRUCTURE_LIST_TITLE}</td>
		<td>{STRUCTURE_LIST_ORDER}</td>
	</tr>
	
	<!-- END: SORTING_STRUCTURE_LIST -->
    
    <tr>
		<td colspan="4"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
	</tr>
    
    </table>
    </form>
    
    </div></div>   
    
    <!-- END: PAGE_SORTING -->
    
 	<!-- BEGIN: PAGE_QUEUE -->   
    
    <div class="content-box">
    
		<div class="content-box-header">					
			<h3>{PHP.L.adm_valqueue}</h3>					    
		</div>
    
		<div class="content-box-content">

		<table class="cells striped">
		<thead>
			<tr>
				<th class="coltop">#</th>
				<th class="coltop">{PHP.L.Title} {PHP.L.adm_clicktoedit}</th>
				<th class="coltop">{PHP.L.Category}</th>
				<th class="coltop">{PHP.L.Date}</th>
				<th class="coltop">{PHP.L.Owner}</th>
				<th class="coltop">{PHP.L.Validate}</th>
			</tr>
		</thead>
    
	<!-- BEGIN: PAGE_QUEUE_LIST -->

		<tr>
			<td>{PAGE_LIST_ID}</td>
			<td>{PAGE_LIST_TITLE}</td>
			<td>{PAGE_LIST_CATPATH}</td>
			<td style="text-align:center;">{PAGE_LIST_DATE}</td>
			<td style="text-align:center;">{PAGE_LIST_OWNER}</td>  	
			<td style="text-align:center;">{PAGE_LIST_VALIDATE}</td>
		</tr>    

	<!-- END: PAGE_QUEUE_LIST -->    
    
    </table>
    
    <!-- BEGIN: WARNING -->
    
		<p>{PHP.L.None}</p>
    
    <!-- END: WARNING -->
    
    </div>
    </div>    
    
    <!-- END: PAGE_QUEUE -->
    	
<!-- END: ADMIN_PAGE -->
