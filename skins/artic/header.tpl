<!-- BEGIN: HEADER -->{HEADER_DOCTYPE}
<html>
<head>
{HEADER_METAS}
{HEADER_COMPOPUP}
<title>{HEADER_TITLE}</title>

<link href="skins/{PHP.skin}/{PHP.skin}.css" type="text/css" rel="stylesheet" />

</head>

<body>


<div id="user_back">

  <div id="user">

    <!-- BEGIN: USER -->

    <ul>
      <li>{HEADER_LOGSTATUS}</li>
      <li>{HEADER_USER_ADMINPANEL}</li>
      <li>{HEADER_USERLIST}</li>
      <li>{HEADER_USER_PROFILE}</li>
      <li>{HEADER_USER_PFS}</li>
      <li>{HEADER_USER_PMREMINDER}</li>
      <li>{HEADER_USER_LOGINOUT}</li>
    </ul>
       
    {HEADER_NOTICES}

    <!-- END: USER -->

    <!-- BEGIN: GUEST -->

    <ul>
      <li><a href="users.php?m=auth">{PHP.skinlang.header.Login}</a></li>
      <li><a href="users.php?m=register">{PHP.skinlang.header.Register}</a></li>
    </ul>

    <!-- END: GUEST -->
  
  </div> 
      
</div> 

      
<div id="header_back">
      
  <div id="header">

    <div id="logo">
      <img src="skins/{PHP.skin}/img/seditio.png" />
    </div>
     
    <div id="nav">
      
      <form name="login" action="plug.php?e=search&a=search" method="post">
      <input type="text" class="text" style="background-color:#202020; color:#DDDDDD; border:1px solid #404040" name="sq" size="20" maxlength="32" />
      <input type="image" src="skins/{PHP.skin}/img/search.png" style="vertical-align:middle;">
      </form>    
        
      {PHP.cfg.menu1}

    </div>
  	     
  </div>

</div>


<div id="container_back">

<div id="container">

<!-- END: HEADER -->