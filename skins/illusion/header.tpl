<!-- BEGIN: HEADER -->{HEADER_DOCTYPE}
<html>
<head>
{HEADER_METAS}
{HEADER_COMPOPUP}
<title>{HEADER_TITLE}</title>
<link href="skins/{PHP.skin}/{PHP.skin}.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/{PHP.skin}.print.css" type="text/css" rel="stylesheet" media="print" title="print" />
<link rel="canonical" href="{HEADER_CANONICAL_URL}" />

</head>

<body>

<div id="user">
  <!-- BEGIN: USER -->
  <div class="notices">{HEADER_NOTICES}</div>
  <div class="user-menu">
  <ul>
    <li style="padding-top:6px;">{HEADER_LOGSTATUS}</li>
    <li>{HEADER_USER_ADMINPANEL}</li>
    <li>{HEADER_USERLIST}</li>
    <li>{HEADER_USER_PROFILE}</li>
    <li>{HEADER_USER_PFS}</li>
    <li>{HEADER_USER_PMREMINDER}</li>
    <li>{HEADER_USER_LOGINOUT}</li>
  </ul>
  </div>  
  <!-- END: USER -->
  
  <!-- BEGIN: GUEST -->
  <div class="user-menu">
  <ul>
      <li><a href="{PHP.out.auth_link}">{PHP.skinlang.header.Login}</a></li>
      <li><a href="{PHP.out.register_link}">{PHP.skinlang.header.Register}</a></li>
  </ul>
  </div>
  <!-- END: GUEST -->  
</div> 

<div id="wrapper">
	<div id="header">
      <div class="units-row">
      <!-- Logo -->
  		<div class="unit-50">
  			<div id="logo">
  				<a href="/" class="logos"></a>
  				<div id="slogan">It's time will come soon!</div>
  				<div class="clear"></div>
  			</div>
  		</div>
  		<!-- Social / Contact -->
  		<div class="unit-50">  			
  			<!-- Social Icons -->
  			<ul class="social-icons">
  				<li class="facebook"><a href="#">Facebook</a></li>
  				<li class="twitter"><a href="#">Twitter</a></li>
  				<li class="dribbble"><a href="#">Dribbble</a></li>
  				<li class="linkedin"><a href="#">LinkedIn</a></li>
  				<li class="pintrest"><a href="#">Pintrest</a></li>
  			</ul>  			
  			<div class="clear"></div>  			
  			<!-- Contact Details -->
  		</div>
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


<div id="main">

<!-- END: HEADER -->