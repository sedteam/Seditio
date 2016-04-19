<!-- BEGIN: MAINTENANCE --> 

{MAINTENANCE_HEADER1}

<title>{MAINTENANCE_MAINTITLE} - {MAINTENANCE_SUBTITLE}</title>
<link href="skins/{PHP.skin}/{PHP.skin}.css" type="text/css" rel="stylesheet">

{MAINTENANCE_HEADER2}

<div style="margin:20% 0 0 0;">

  <div class="width-40 margin-auto">
    
    <div id="title"><h2>{PHP.L.Maintenance}</h2></div>
    <div id="bolded-line"></div>
    
    <div id="subtitle">{MAINTENANCE_REASON}</div>
    
    <form name="login" action="{MAINTENANCE_FORM_SEND}" method="post">
      <table class="cells striped">
        <tr><td>{PHP.skinlang.maintenance.Username}</td><td>{MAINTENANCE_USER}</td></tr>
    		<tr><td>{PHP.skinlang.maintenance.Password}</td><td>{MAINTENANCE_PASSWORD}</td></tr>
        <tr><td colspan="2"><input type="submit" class="submit btn btn-big" value="{PHP.skinlang.maintenance.Login}"></td></tr>
      </table>   
    </form>
  </div>
</div>

{MAINTENANCE_FOOTER}

<!-- END: MAINTENANCE -->