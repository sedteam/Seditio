<!-- BEGIN: HEADER -->
{HEADER_DOCTYPE}
<html>
<head>
{HEADER_METAS}
{HEADER_COMPOPUP}
<title>{HEADER_TITLE}</title>

<link href="skins/{PHP.skin}/{PHP.skin}.css" type="text/css" rel="stylesheet" />

</head>

<body>

<div id="header_back">

<div id="header">

  <table style="width:100%;">
    <tr>
    
      <td style="padding:10px 0 0 0;"  rowspan="2">
        <img src="skins/{PHP.skin}/img/seditio.png" />
      </td>  
      
      <td style="width:70%; padding:0; text-align:right;">

      <div id="user">

      <!-- BEGIN: USER -->

        <ul>
		      <li>{HEADER_USER_ADMINPANEL}</li>
		      <li>{HEADER_USERLIST}</li>
		      <li>{HEADER_USER_PROFILE}</li>
		      <li>{HEADER_USER_PFS}</li>
		      <li>{HEADER_USER_PMREMINDER}</li>
		      <li>{HEADER_USER_LOGINOUT}</li>
        </ul>
       
       {HEADER_NOTICES} &nbsp; {HEADER_LOGSTATUS}
       

	    <!-- END: USER -->

      <!-- BEGIN: GUEST -->

        <ul>
          <li><a href="users.php?m=auth">{PHP.skinlang.header.Login}</a></li>
          <li><a href="users.php?m=register">{PHP.skinlang.header.Register}</a></li>
        </ul>

      <!-- END: GUEST -->
  
      </div>
      
      </td>  
    </tr>  
    
    <tr>
    
      <td style="vertical-align:bottom; width:100%;" rowspan="2">    
    
    	<div id="nav">

		  	{PHP.cfg.menu1}

	     </div>

      </td>  
    </tr> 
  </table>	     
	     
</div>

</div>

<div id="container_back">

<div id="container">

<!-- END: HEADER -->