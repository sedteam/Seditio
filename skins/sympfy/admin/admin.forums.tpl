<!-- BEGIN: ADMIN_FORUMS -->

<!-- BEGIN: FORUMS_BUTTONS -->

	<ul class="shortcut-buttons-set">
		
		<!-- BEGIN: FORUMS_BUTTONS_CONFIG -->
		<li><a class="shortcut-button" href="{BUTTON_FORUMS_CONFIG_URL}"><span>
			<i class="ic-settings ic-3x"></i><br />
			{PHP.L.Configuration}
		</span></a></li>
		<!-- END: FORUMS_BUTTONS_CONFIG -->
		
		<!-- BEGIN: FORUMS_BUTTONS_STRUCTURE -->
		<li><a class="shortcut-button" href="{BUTTON_FORUMS_STRUCTURE_URL}"><span>
			<i class="ic-manual-gearbox ic-3x"></i><br />
			{PHP.L.Structure}
		</span></a></li>
		<!-- END: FORUMS_BUTTONS_STRUCTURE -->
		
	</ul>

	<div class="clear"></div>
	
<!-- END: USERS_BUTTONS -->

<div class="content-box">

<!-- BEGIN: FS_UPDATE -->

	<div class="content-box-header">
		<h3>{FS_UPDATE_FORM_TITLE}</h3>
	</div>
  
	<div class="content-box-content">

	<form id="updatesection" action="{FS_UPDATE_SEND}" method="post">
  
	<table class="cells striped">
	
    <tr>
      <td>{PHP.L.Section} :</td>
      <td>{FS_UPDATE_ID}</td>
    </tr>
  	
  	<tr>
      <td>{PHP.L.adm_parentcat} :</td>
      <td>{FS_UPDATE_PARENTCAT}</td>
    </tr>
  		
  	<tr>
      <td>{PHP.L.Category} :</td>
      <td>{FS_UPDATE_CATEGORY}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.Title} :</td>
      <td>{FS_UPDATE_TITLE}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.Description} :</td>
      <td>{FS_UPDATE_DESC}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.Icon} :</td>
      <td>{FS_UPDATE_ICON}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.adm_diplaysignatures} :</td>
      <td>{FS_UPDATE_ALLOWUSERTEXT}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.adm_enablebbcodes} :</td>
      <td>{FS_UPDATE_ALLOWBBCODES}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.adm_enablesmilies} :</td>
      <td>{FS_UPDATE_ALLOWSMILIES}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.adm_enableprvtopics} :</td>
      <td>{FS_UPDATE_ALLOWPRIVATETOPICS}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.adm_countposts} :</td>
      <td>{FS_UPDATE_COUNTPOST}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.Locked} :</td>
      <td>{FS_UPDATE_STATE}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.adm_autoprune} :</td>
      <td>{FS_UPDATE_AUTOPRUNE}</td>
    </tr>

    <!-- BEGIN: FS_ADMIN -->
    
  	<tr>
      <td>{PHP.L.adm_postcounters} :</td>
      <td>{FS_UPDATE_RESYNC}</td>
    </tr>
    
  	<tr>
      <td>{PHP.L.Delete} :</td>
      <td>{FS_UPDATE_DELETE}</td>
    </tr>
    
    <!-- END: FS_ADMIN -->    
    
  	<tr>
      <td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Update}" /></td>
    </tr>
  
	</table>
  
	</form>

	</div>
  
<!-- END: FS_UPDATE -->

<!-- BEGIN: FS_CAT -->

	<div class="sedtabs">

		<div class="content-box-header">
		
			<h3>{PHP.L.adm_forum_structure_cat}</h3>
			
			<ul class="content-box-tabs">
				<li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.editdeleteentries}</a></li>
				<li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.addnewentry}</a></li>
			</ul>	
			
			<div class="clear"></div>
			
		</div>
	  
		<div class="content-box-content">
	  
			<div class="tab-content default-tab" id="tab1">   

				<form id="updateorder" action="{FS_CAT_SEND}" method="post">

				<div class="table cells striped">

					<div class="table-head">
					
						<div class="table-row">
							<div class="table-th coltop text-left">{PHP.L.Section}</div>
							<div class="table-th coltop text-center">{PHP.L.Order}</div>
							<div class="table-th coltop text-center">{PHP.L.adm_enableprvtopics}</div>
							<div class="table-th coltop text-center" style="width:48px;">{PHP.L.Topics}</div>
							<div class="table-th coltop text-center" style="width:48px;">{PHP.L.Posts}</div>
							<div class="table-th coltop text-center" style="width:48px;">{PHP.L.Views}</div>
							<div class="table-th coltop text-center" style="width:80px;">{PHP.L.Rights}</div>
							<div class="table-th coltop text-center" style="width:64px;">{PHP.L.Open}</div>
						</div>
					
					</div>
					
					<div class="table-body">

						<!-- BEGIN: FS_LIST -->

						<!-- BEGIN: FN_CAT -->
						
						<div class="table-row" style="height:43px;">
							<div class="table-colspan-100 coltop text-left">
								<a href="{FN_CAT_URL}">{FN_CAT_TITLE} ({FN_CAT_PATH})</a>
							</div>
						</div>
						
						<!-- END: FN_CAT -->

						<div class="table-row">
						
							<div class="table-td">{FS_LIST_TITLE}</div>
							<div class="table-td text-center"><a href="{FS_LIST_ORDER_UP_URL}">{PHP.sed_img_up}</a><a href="{FS_LIST_ORDER_DOWN_URL}">{PHP.sed_img_down}</a></div>
							<div class="table-td text-center">{FS_LIST_ALLOWPRIWATETOPICS}</div>
							<div class="table-td text-center">{FS_LIST_TOPICCOUNT}</div>
							<div class="table-td text-center">{FS_LIST_POSTCONT}</div>
							<div class="table-td text-center">{FS_LIST_VIEWCOUNT}</div>
							<div class="table-td text-center"><a href="{FS_LIST_RIGHTS_URL}"><i class="ic-lock"></i></a></div>
							<div class="table-td text-center"><a href="{FS_LIST_OPEN_URL}"><i class="ic-arrow-right"></i></a></div>
						
						</div>

						<!-- END: FS_LIST -->
					
					</div>

				</div>

				<div class="table-btn text-center">
					<button type="submit" class="submit btn">{PHP.L.Update}</button>
				</div>	

				</form>

			</div>
	  
			<div class="tab-content" id="tab2">	  
		  
			  <h4>{PHP.L.addnewentry}</h4>
				
			  <form id="addsection" action="{FS_ADD_SEND}" method="post">
			  
			  <table class="cells striped">
			  
			  <tr>
				<td>{PHP.L.Category} :</td>
				<td>{FS_ADD_CATEGORY}</td>
			  </tr>
			  
			  <tr>
				<td>{PHP.L.Title} :</td>
				<td>{FS_ADD_TITLE} {PHP.L.adm_required}</td>
			  </tr>
			  
			  <tr>
				<td>{PHP.L.Description} :</td>
				<td>{FS_ADD_DESC}</td>
			  </tr>
			  
			  <tr>
				<td colspan="2"><input type="submit" class="submit btn" value="{PHP.L.Add}" /></td>
			  </tr>
			  
			  </table>
				
			  </form> 
			
			</div>
		
		</div>
	
	</div> 
  
<!-- END: FS_CAT -->
  
</div>

<!-- END: ADMIN_FORUMS -->
