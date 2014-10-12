<!-- BEGIN: MAIN -->

<div id="title">

  <h2>{PAGEADD_PAGETITLE}</h2>
  
</div>

<div id="bolded-line"></div>

<div id="subtitle">

  {PAGEADD_SUBTITLE}
  
</div>

<div id="page">

<!-- BEGIN: PAGEADD_ERROR -->

<div class="error">

		{PAGEADD_ERROR_BODY}

</div>

<!-- END: PAGEADD_ERROR -->

<form action="{PAGEADD_FORM_SEND}" method="post" name="newpage">
  
<div class="sedtabs">
	
		<ul class="tabs">
      <li><a href="{PHP.sys.request_uri}#tab1">{PHP.L.Page}</a></li>
      <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.Meta}</a></li>
      <li><a href="{PHP.sys.request_uri}#tab3">{PHP.L.Options}</a></li>
    </ul>
    
    <div class="tab-box">
    
		<div id="tab1" class="tabs">
    
			<table class="cells striped" class="simple tableforms">

			<tr>
				<td style="width:176px;">{PHP.skinlang.pageadd.Category}</td>
				<td>{PAGEADD_FORM_CAT}</td>
			</tr>
		
			<tr>
				<td>{PHP.skinlang.pageadd.Title}</td>
				<td>{PAGEADD_FORM_TITLE}</td>
			</tr>
		
			<tr>
				<td>{PHP.skinlang.pageadd.Description}</td>
				<td>{PAGEADD_FORM_DESC}</td>
			</tr>
		
			<tr>
				<td>{PHP.skinlang.pageadd.Author}</td>
				<td>{PAGEADD_FORM_AUTHOR}</td>
			</tr>
		
			<tr>
				<td>{PHP.skinlang.pageadd.Alias}</td>
				<td>{PAGEADD_FORM_ALIAS}</td>
			</tr>
			
		  <!-- BEGIN: PAGEADD_PARSING -->
		
		  <tr>
		    <td>{PHP.skinlang.pageadd.Parsing}</td>
		    <td>{PAGEADD_FORM_TYPE}</td>
		  </tr>   
		  
		  <!-- END: PAGEADD_PARSING -->			
		  
			<tr>
				<td colspan="2">{PHP.skinlang.pageadd.Bodyofthepage}<br /><br />{PAGEADD_FORM_TEXT}</td>
			</tr>  
		
			</table>
					
		</div>
    <div id="tab2" class="tabs">
    
			<table class="cells striped" class="simple tableforms">
			
			<tr>
				<td>{PHP.L.mt_title}</td>
				<td>{PAGEADD_FORM_SEOTITLE}</td>
			</tr>
			
			<tr>
				<td>{PHP.L.mt_description}</td>
				<td>{PAGEADD_FORM_SEODESC}</td>
			</tr>
			
			<tr>
				<td>{PHP.L.mt_keywords}</td>
				<td>{PAGEADD_FORM_SEOKEYWORDS}</td>
			</tr>	
						
			</table>    
		
		</div>
		
		<div id="tab3" class="tabs">
		
			<table class="cells striped" class="simple tableforms">
		
			<tr>
				<td>{PHP.skinlang.pageadd.Begin}</td>
				<td>{PAGEADD_FORM_BEGIN}</td>
			</tr>
		
			<tr>
				<td>{PHP.skinlang.pageadd.Expire}</td>
				<td>{PAGEADD_FORM_EXPIRE}</td>
			</tr>
			
			<tr>
				<td>{PHP.skinlang.pageadd.Extrakey}</td>
				<td>{PAGEADD_FORM_KEY}</td>
			</tr>			
		
			<tr>
				<td>{PHP.skinlang.pageadd.Owner}</td>
				<td>{PAGEADD_FORM_OWNER}</td>
			</tr>			
		  
			<tr>
				<td>{PHP.skinlang.pageadd.Allowcomments}</td>
				<td>{PAGEADD_FORM_ALLOWCOMMENTS}</td>
			</tr>	
		
			<tr>
				<td>{PHP.skinlang.pageadd.Allowratings}</td>
				<td>{PAGEADD_FORM_ALLOWRATINGS}</td>
			</tr>	
		
			<tr>
				<td>{PHP.skinlang.pageadd.File}<br />
				{PHP.skinlang.pageadd.Filehint}</td>
				<td>{PAGEADD_FORM_FILE}</td>
			</tr>
		
			<tr>
				<td>{PHP.skinlang.pageadd.URL}<br />
				{PHP.skinlang.pageadd.URLhint}</td>
				<td>{PAGEADD_FORM_URL}</td>
			</tr>
		
			<tr>
				<td>{PHP.skinlang.pageadd.Filesize}<br />
				{PHP.skinlang.pageadd.Filesizehint}</td>
				<td>{PAGEADD_FORM_SIZE}</td>
			</tr>
					
		  </table>
		
		</div>

		<div class="help">{PHP.skinlang.pageadd.Formhint} </div>
		
			<div class="centered">
				<input type="submit" class="submit btn btn-big" value="{PHP.skinlang.pageadd.Submit}" />
				<!-- BEGIN: PAGEADD_PUBLISH -->
				<input type="submit" class="submit btn btn-big" name="newpagepublish" value="{PHP.skinlang.pageadd.Publish}" onclick="this.value='OK'; return true" />
				<!-- END: PAGEADD_PUBLISH -->
			</div>
		
		</div>
		
</div>

</form>

</div>

<!-- END: MAIN -->