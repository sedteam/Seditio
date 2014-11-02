<!-- BEGIN: MAIN -->

<div id="title">

	<h2>{PLUGIN_CONTACT_TITLE}</h2>
	
</div>

<div id="subtitle">

	{PLUGIN_CONTACT_EXPLAIN}

</div>

<div id="page">

  <!-- BEGIN: PLUGIN_CONTACT_ERROR -->

  <div class="error">

	 	{PLUGIN_CONTACT_ERROR_BODY}

  </div>

  <!-- END: PAGEADD_ERROR -->

  
  <form action="{PLUGIN_CONTACT_FORM}" method="post" name="sendmail">
  
  <table class="cells striped">
    
    <tr>
      <td>{PLUGIN_CONTACT_RECIPIENTS_TITLE} : </td>
      <td>
      {PLUGIN_CONTACT_RECIPIENTS}
    </td>
  </tr>
  
  <tr>
    <td>{PLUGIN_CONTACT_EMAIL_TITLE} : </td>
    <td>{PLUGIN_CONTACT_EMAIL} *
    </td>
  </tr>
  
  <tr>
    <td>{PLUGIN_CONTACT_NAME_TITLE} :</td>
    <td>{PLUGIN_CONTACT_NAME} *</td>
  </tr>

  <tr>
    <td>{PLUGIN_CONTACT_PHONE_TITLE} :</td>
    <td>{PLUGIN_CONTACT_PHONE}</td>
  </tr>

  <tr>
    <td>{PLUGIN_CONTACT_SUBJECT_TITLE} :</td>
    <td>{PLUGIN_CONTACT_SUBJECT} *</td>
  </tr>

   
  <tr>
    <td>{PLUGIN_CONTACT_BODY_TITLE} :</td>
    <td>{PLUGIN_CONTACT_BODY} *</td>
  </tr>

  <tr>
    <td colspan="2">{PLUGIN_CONTACT_REQUIRED}</td>
  </tr>  

  <tr>
    <td colspan="2" style="text-align:center;">{PLUGIN_CONTACT_ANTISPAM}</td>
  </tr>

  <tr>
    <td colspan="2" style="text-align:center;">
    <input type="submit" class="submit btn btn-big" value="{PLUGIN_CONTACT_SEND}" /></td>
  </tr>
  
  </table>
  
  </form>

</div>


<!-- END: MAIN -->