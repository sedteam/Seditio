<!-- BEGIN: ADMIN_POLLS -->
	
	<!-- BEGIN: POLLS -->
	
	<div class="content-box sedtabs">
		<div class="content-box-header">					
			<h3>{PHP.L.Polls}</h3>					
			<ul class="content-box-tabs">
				  <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Polls}</a></li>
				  <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.addnewentry}</a></li>
			</ul>					
			<div class="clear"></div>					
		</div>    

		<div class="content-box-content">
		<div class="tab-content default-tab" id="tab1">

		<table class="cells striped">
		
		<thead>
		<tr>
			<th class="coltop" style="width:40px;">{PHP.L.Delete}</th>
			<th class="coltop" style="width:40px;">{PHP.L.Reset}</th>
			<th class="coltop" style="width:40px;">{PHP.L.Bump}</th>
			<th class="coltop" style="width:128px;">{PHP.L.Date}</th>
			<th class="coltop">{PHP.L.Poll} {PHP.L.adm_clicktoedit}</th>
			<th class="coltop" style="width:48px;">{PHP.L.Votes}</th>
			<th class="coltop" style="width:48px;">{PHP.L.Open}</th>
		</tr>	
		</thead>
		
		<!-- BEGIN: POLLS_LIST -->

		<tr>
			<td style="text-align:center;"><a href="{POLLS_LIST_DELETE_URL}" title="{PHP.L.Delete}"><i class="fa fa-lg fa-trash"></i></a></td>
			<td style="text-align:center;"><a href="{POLLS_LIST_RESET_URL}" title="{PHP.L.Reset}"><i class="fa fa-lg fa-eraser"></i></a></td>
			<td style="text-align:center;"><a href="{POLLS_LIST_BUMP_URL}" title="{PHP.L.Bump}"><i class="fa fa-lg fa-thumbs-up"></i></a></td>
			<td style="text-align:center;">{POLLS_LIST_DATE}</td>
			<td style="text-align:left;"><a href="{POLLS_LIST_OPTIONS_URL}">{POLLS_LIST_POLLTEXT}</a></td>
			<td style="text-align:center;">{POLLS_LIST_TOTALVOTES}</td>
			<td style="text-align:center;"><a href="{POLLS_LIST_OPEN_URL}"><i class="fa fa-lg fa-arrow-right"></i></a></td>
		</tr>
		
		<!-- END: POLLS_LIST -->
			
		<tr>
			<td colspan="8">{PHP.L.Total} : {POLLS_TOTAL}</td>
		</tr>
		
		</table>
		
		</div>
		
		<div class="tab-content" id="tab2">

    	<h4>{PHP.L.addnewentry}</h4>
    	 
		<form id="addpoll" action="{POLL_ADD_SEND}" method="post">
			
		<table class="cells striped">
		<tr>
			<td>Poll topic</td>
			<td>{POLL_ADD_TEXT}</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" class="submit btn" value="{PHP.L.Add}"> 
			</td>
		</tr>
		
		</table>
		
		</form>

		</div>
		</div>
	</div> 		
    	    	
    <!-- END: POLLS -->
    
    
	<!-- BEGIN: POLL_EDIT -->
	
	<div class="content-box">
		<div class="content-box-header">					
			<h3>{PHP.L.editdeleteentries}: {PHP.L.Poll} #{POLL_EDIT_ID}</h3>			
			<div class="clear"></div>										
		</div>    

	<div class="content-box-content">  	
	
 	<form id="pollchgtitle" action="{POLL_EDIT_SEND}" method="post">
			
	<table class="cells striped">
		<thead>
		<tr>
			<th>{PHP.L.Title}</th>
			<th>{PHP.L.Open}</th>
			<th></th>			
		</tr>
		</thead>
		<tr>
			<td>{POLL_EDIT_TEXT}</td>
			<td style="text-align:center;"><a href="javascript:sedjs.polls('{POLL_EDIT_ID}')"><i class="fa fa-lg fa-arrow-right"></i></a></td>
			<td style="text-align:center;"><input type="submit" class="submit btn" value="{PHP.L.Update}"></td>
		</tr>
		<tr>
			<td colspan="3">{PHP.L.Date} : {POLL_EDIT_CREATION_DATE} GMT</td>
		</tr>
	</table>
	
	</form>
	
	</div>
	</div>

	<div class="content-box">
		<div class="content-box-header">					
			<h3>{PHP.L.Poll} #{POLL_EDIT_ID} : Options</h3>			
			<div class="clear"></div>										
		</div>    

	<div class="content-box-content">  	
	
	<table class="cells striped">
	
	<tr>
		<td>{PHP.L.Delete}</td>
		<td>#</td>
		<td>{PHP.L.Option}</td>
		<td>&nbsp;</td>
	</tr>

	<!-- BEGIN: OPTIONS_LIST --> 
 		<form id="savepollopt" action="{POLL_EDIT_OPTIONS_SEND}" method="post">
		
		<tr>
			<td style="width:20px;"><a href="{POLL_EDIT_OPTIONS_DELETE_URL}" title="{PHP.L.Delete}"><i class="fa fa-lg fa-trash"></i></a></td>
			<td style="width:30px;">{POLL_EDIT_OPTIONS_ID}</td>
			<td>{POLL_EDIT_OPTIONS_TEXT}</td>
			<td><input type="submit" class="submit btn" value="{PHP.L.Update}"></td>
		</tr>
		
		</form>
	<!-- END: OPTIONS_LIST --> 

	</table>
	
	</div>
	</div>
	
	<div class="content-box">
		<div class="content-box-header">					
			<h3>{PHP.L.addnewentry} : Option</h3>			
			<div class="clear"></div>										
		</div>    

	<div class="content-box-content">  
		
	<form id="addpollopt" action="{POLL_OPTIONS_ADD_SEND}" method="post">
		
	<table class="cells striped">
		<tr>
			<td>{PHP.L.Option}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>{POLL_OPTIONS_ADD_TEXT}</td>
			<td><input type="submit" class="submit btn" value="{PHP.L.Add}"></td>
		</tr>
	</table>
		
	</form>
	
	</div>
</div>

	<!-- END: POLL_EDIT -->	
        	
<!-- END: ADMIN_POLLS -->
