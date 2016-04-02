<!-- BEGIN: ADMIN_DIC -->

<!-- BEGIN: DIC_STRUCTURE -->

  <div class="content-box sedtabs">
	<div class="content-box-header">					
		<h3>{PHP.L.core_dic}</h3>					
		<ul class="content-box-tabs">
		  <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.adm_dic_list}</a></li>
		  <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.adm_dic_add}</a></li>
		</ul>					
		<div class="clear"></div>					
	</div>    
    
	<div class="content-box-content">
	<div class="tab-content default-tab" id="tab1">   
	  
		<table class="cells striped">
		<thead>
		<tr>
			<th class="coltop" style="width:20px;">{PHP.L.Id}</th>
			<th class="coltop">{PHP.L.Title}</th>			
			<th class="coltop">{PHP.L.adm_dic_code}</th>
			<th class="coltop">{PHP.L.Type}</th>
			<th class="coltop" style="width:100px;">{PHP.L.Options}</th>
		</tr>
		</thead>
		
		<!-- BEGIN: DIC_LIST -->
		
		<tr>
			<td style="text-align:center;">{DIC_LIST_ID}</td>
			<td><a href="{DIC_LIST_URL}">{DIC_LIST_TITLE}</a></td>			
			<td>{DIC_LIST_CODE}</td>
			<td>{DIC_LIST_TYPE}</td>
			<td style="text-align:center;">
      
      <!-- BEGIN: ADMIN_DELETE -->
      <a href="{DIC_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="fa fa-trash"></i></a>
      <!-- END: ADMIN_DELETE -->
      
      <!-- BEGIN: ADMIN_ACTIONS -->
      <a href="{DIC_LIST_EDIT_URL}" title="{PHP.L.Edit}" class="btn btn-small"><i class="fa fa-pencil"></i></a>
      <!-- END: ADMIN_ACTIONS -->      
      
      </td>
		</tr>	
		
		<!-- END: DIC_LIST -->
		
		</table>
	
	</div>
	
	<div class="tab-content" id="tab2"> 
  
  	<h4>{PHP.L.adm_dic_add}</h4>
  	 
	<form id="adddirectory" action="{DIC_ADD_SEND}" method="post">

	<table class="cells striped">
	
	<tr>
		<td style="width:180px;">{PHP.L.Title} :</td>
		<td>{DIC_ADD_TITLE} {PHP.L.adm_required}</td>
	</tr>

	<tr>
		<td>{PHP.L.adm_dic_code} :</td>
		<td>{DIC_ADD_CODE} {PHP.L.adm_required}</td>
	</tr>
	
	<tr>
		<td>{PHP.L.adm_dic_mera} :</td>
		<td>{DIC_ADD_MERA}</td>
	</tr>
	
	<tr>
		<td>{PHP.L.adm_dic_values} :</td>
		<td>{DIC_ADD_VALUES}{PHP.L.adm_dic_comma_separat}</td>
	</tr>
  
  <tr>
    <td>{PHP.L.adm_dic_form_title} :</td>
    <td>{DIC_ADD_FORM_TITLE}</td>
  </tr>  		 
 
  <tr> 
    <td>{PHP.L.adm_dic_form_desc} :</td>   
    <td>{DIC_ADD_FORM_DESC}</td>
  </tr>
 
  <tr>
    <td>{PHP.L.adm_dic_form_size} :</td>  
    <td>{DIC_ADD_FORM_SIZE}</td>
  </tr>
  
  <tr>
    <td>{PHP.L.adm_dic_form_maxsize} :</td>  
    <td>{DIC_ADD_FORM_MAXSIZE}</td>
  </tr>
  
  <tr>
    <td>{PHP.L.adm_dic_form_cols} :</td>  
    <td>{DIC_ADD_FORM_COLS}</td>
  </tr>
  
  <tr>
    <td>{PHP.L.adm_dic_form_rows} :</td>  
    <td>{DIC_ADD_FORM_ROWS}</td>	
	</tr>
  
	<tr>
		<td>{PHP.L.Type} :</td>
		<td>{DIC_ADD_TYPE}</td>
	</tr>
	
	<tr>
		<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Add}" /></td>
	</tr>
	
	</table>
	
	</form>  
	
	</div>
</div>
</div>	
	
<!-- END: DIC_STRUCTURE -->

<!-- BEGIN: DIC_EDIT -->

<div class="content-box">
	
	<div class="content-box-header">
		<h3>{PHP.L.adm_dic_edit}</h3>
		<div class="content-box-header-right">
			
			<div class="clear"></div>					
		</div>
	</div>

	<div class="content-box-content">	
   	 
	<form id="updatedirectory" action="{DIC_EDIT_SEND}" method="post">

	<table class="cells striped">
	
	<tr>
		<td style="width:180px;">{PHP.L.Title} :</td>
		<td>{DIC_EDIT_TITLE} {PHP.L.adm_required}</td>
	</tr>
	
	<tr>
		<td>{PHP.L.adm_dic_mera} :</td>
		<td>{DIC_EDIT_MERA}</td>
	</tr>
	
	<tr>
		<td>{PHP.L.adm_dic_values} :</td>
		<td>{DIC_EDIT_VALUES}{PHP.L.adm_dic_comma_separat}</td>
	</tr>	
  
  <tr>
    <td>{PHP.L.adm_dic_form_title} :</td>
    <td>{DIC_EDIT_FORM_TITLE}</td>
  </tr>  		 
 
  <tr> 
    <td>{PHP.L.adm_dic_form_desc} :</td>   
    <td>{DIC_EDIT_FORM_DESC}</td>
  </tr>
 
  <tr>
    <td>{PHP.L.adm_dic_form_size} :</td>  
    <td>{DIC_EDIT_FORM_SIZE}</td>
  </tr>
  
  <tr>
    <td>{PHP.L.adm_dic_form_maxsize} :</td>  
    <td>{DIC_EDIT_FORM_MAXSIZE}</td>
  </tr>
  
  <tr>
    <td>{PHP.L.adm_dic_form_cols} :</td>  
    <td>{DIC_EDIT_FORM_COLS}</td>
  </tr>
  
  <tr>
    <td>{PHP.L.adm_dic_form_rows} :</td>  
    <td>{DIC_EDIT_FORM_ROWS}</td>	
	</tr>
	
	<tr>
		<td>{PHP.L.Type} :</td>
		<td>{DIC_EDIT_TYPE}</td>
	</tr>
	
	<tr>
		<td>{PHP.L.adm_parentcat} :</td>
		<td>{DIC_EDIT_DICPARENT}</td>
	</tr>
	
	<tr>
		<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
	</tr>
	
	</table>
	
	</form>    
  
  </div>
  
</div>  

<!-- END: DIC_EDIT -->

<!-- BEGIN: DIC_TERMS -->

  <div class="content-box sedtabs">
	<div class="content-box-header">					
		<h3>{PHP.L.adm_directory} "{DIC_TERMS_DICTIONARY}"</h3>					
		<ul class="content-box-tabs">
		  <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.adm_dic_term_list}</a></li>
		  <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.adm_dic_add_term}</a></li>
		</ul>					
		<div class="clear"></div>					
	</div>    
    
	<div class="content-box-content">
	<div class="tab-content default-tab" id="tab1">   
	  
		<table class="cells striped">
		<thead>
		<tr>
			<th class="coltop" style="width:20px;">{PHP.L.Id}</th>
			<th class="coltop">{PHP.L.adm_dic_term_title}</th>			
			<th class="coltop">{PHP.L.adm_dic_term_value}</th>
			<th class="coltop">{PHP.L.adm_dic_children}</th>
			<th class="coltop" style="width:50px;">{PHP.L.adm_dic_term_defval}</th>
			<th class="coltop" style="width:100px;">{PHP.L.Options}</th>
		</tr>
		</thead>

		<!-- BEGIN: TERMS_LIST -->

		<tr>
			<td style="text-align:center;">{TERM_LIST_ID}</td>
			<td>{TERM_LIST_TITLE}</td>			
			<td style="width:50px; text-align:center">{TERM_LIST_CODE}</td>
			<td style="width:150px; text-align:center">{TERM_LIST_CHILDRENDIC}</td>
			<td style="width:50px; text-align:center">
			<!-- BEGIN: TERM_DEFAULT -->
			  <i class="fa fa-check"></i>
			<!-- END: TERM_DEFAULT -->
			</td>
			<td style="text-align:center;">
				<a href="{TERM_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="fa fa-trash"></i></a>
				<a href="{TERM_LIST_EDIT_URL}" title="{PHP.L.Edit}" class="btn btn-small"><i class="fa fa-pencil"></i></a>
			</td>
		</tr>	

		<!-- END: TERMS_LIST -->

		</table>
	
	</div>
	
	<div class="tab-content" id="tab2"> 
  
  	<h4>{PHP.L.adm_dic_add_term}</h4>
  	 
	<form id="addstructure" action="{TERM_ADD_SEND}" method="post">

	<table class="cells striped">
	
	<tr>
		<td style="width:180px;">{PHP.L.adm_dic_term_title} :</td>
		<td>{TERM_ADD_TITLE} {PHP.L.adm_required}</td>
	</tr>

	<tr>
		<td style="width:180px;">{PHP.L.adm_dic_term_value} :</td>
		<td>{TERM_ADD_CODE}</td>
	</tr>
	
	<tr>
		<td style="width:180px;">{PHP.L.adm_dic_children} :</td>
		<td>{TERM_ADD_CHILDRENDIC}</td>
	</tr>	
	
	<tr>
		<td>{PHP.L.adm_dic_term_defval} :</td>
		<td>{TERM_ADD_DEFVAL}</td>
	</tr>
	
	<tr>
		<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Add}" /></td>
	</tr>
	
	</table>
	
	</form>  
	
	</div>
</div>
</div>	

<!-- END: DIC_TERMS -->

<!-- BEGIN: DIC_ITEM_EDIT -->

<div class="content-box">
	
	<div class="content-box-header">
		<h3>{PHP.L.adm_dic_term_edit} : {DIC_TITLE}</h3>
		<div class="content-box-header-right">
			
			<div class="clear"></div>					
		</div>
	</div>

	<div class="content-box-content">	
   	 
	<form id="updatedirectory" action="{DIC_ITEM_EDIT_SEND}" method="post">

	<table class="cells striped">
	
	<tr>
		<td style="width:180px;">{PHP.L.adm_dic_term_title} :</td>
		<td>{DIC_ITEM_EDIT_TITLE} {PHP.L.adm_required}</td>
	</tr>

	<tr>
		<td style="width:180px;">{PHP.L.adm_dic_term_value} :</td>
		<td>{DIC_ITEM_EDIT_CODE}</td>
	</tr>
	
	<tr>
		<td style="width:180px;">{PHP.L.adm_dic_children} :</td>
		<td>{DIC_ITEM_EDIT_CHILDRENDIC}</td>
	</tr>		
	
	<tr>
		<td>{PHP.L.adm_dic_term_defval} :</td>
		<td>{DIC_ITEM_EDIT_DEFVAL}</td>
	</tr>
	
	<tr>
		<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
	</tr>
	
	</table>
	
	</form>    
  
  </div>
  
</div>  

<!-- END: DIC_ITEM_EDIT -->


<!-- BEGIN: DIC_EXTRA -->

<div class="content-box">
	
	<div class="content-box-header">
		<h3>{PHP.L.adm_dic_extra} / {DIC_EXTRA_TITLE}</h3>
		<div class="content-box-header-right">
			
			<div class="clear"></div>					
		</div>
	</div>

	<div class="content-box-content">	
   	 
	<form id="addextra" action="{DIC_EXTRA_SEND}" method="post">
  
  <table class="cells striped">
	
	<tr>
		<td style="width:180px;">{PHP.L.adm_dic_code} :</td>
		<td>{DIC_EXTRA_DICCODE}</td>
	</tr>
  
  <tr>
		<td style="width:180px;">{PHP.L.adm_dic_extra_location} :</td>
		<td>{DIC_EXTRA_LOCATION}</td>
	</tr>
	
	<tr>
		<td>{PHP.L.adm_dic_extra_type} :</td>
		<td>{DIC_EXTRA_TYPE}</td>
	</tr>
	
	<tr>
		<td>{PHP.L.adm_dic_extra_size} :</td>
		<td>{DIC_EXTRA_SIZE}</td>
	</tr>
  
  <!-- BEGIN: DIC_EXTRA_DELETE -->
  
	<tr>
		<td>{PHP.L.Delete} :</td>
		<td>{DIC_EXTRA_DELETE}</td>
	</tr>
    
  <!-- END: DIC_EXTRA_DELETE -->
  	
	<tr>
		<td colspan="2"><input type="submit" class="submit btn" value="{DIC_EXTRA_SUBMIT_NAME}" /></td>
	</tr>
	
	</table>
	
	</form>    
  
  </div>
  
</div>  

<!-- END: DIC_EXTRA -->

<!-- END: ADMIN_DIC -->
