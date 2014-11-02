<!-- BEGIN: HEADER -->{HEADER_DOCTYPE}
<html>
<head>
<title>{HEADER_TITLE}</title>
{HEADER_METAS}
{HEADER_COMPOPUP}
<link href="skins/{PHP.skin}/{PHP.skin}.css" type="text/css" rel="stylesheet" />
<link rel="canonical" href="{HEADER_CANONICAL_URL}" />
</head>
<body>

<div id="wrapper">

<div id="user">

  <!-- BEGIN: USER -->
	<div class="notices">{HEADER_NOTICES}</div>  
  <ul> 
    <li><span>{HEADER_LOGSTATUS}</span></li>
    <li>{HEADER_USER_ADMINPANEL}</li>
    <li>{HEADER_USERLIST}</li>
    <li>{HEADER_USER_PROFILE}</li>
    <li>{HEADER_USER_PFS}</li>
    <li>{HEADER_USER_PMREMINDER}</li>
    <li>{HEADER_USER_LOGINOUT}</li>
  </ul>
  <!-- END: USER -->
  
  <!-- BEGIN: GUEST -->
  <ul>
      <li><a href="{PHP.out.auth_link}">{PHP.skinlang.header.Login}</a></li>
      <li><a href="{PHP.out.register_link}">{PHP.skinlang.header.Register}</a></li>
  </ul>
  <!-- END: GUEST -->  

</div> 

	<div id="header">

			<div id="logos">
				<a href="/" class="logo"></a>
				<div class="slogan">It's time will come soon!</div>
			</div>
               
      <div id="nav">  
          {PHP.cfg.menu1}          
          <div class="form-search">
            <form name="login" action="plug.php?e=search&a=search" method="post">
                <input type="text" name="sq" size="20" maxlength="32" class="search-text-box" />
            </form>  
          </div>              
      </div>
			  	     
  </div>

<div id="middle">

<!-- END: HEADER -->