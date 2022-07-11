<!-- BEGIN: MAIN -->

{POLLS_STANDALONE_HEADER1}
<link href="skins/{PHP.skin}/css/framework.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/fonts.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/plugins.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/cms.css" type="text/css" rel="stylesheet" />		
<link href="skins/{PHP.skin}/css/sympfy.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/responsive.css" type="text/css" rel="stylesheet" />
{POLLS_STANDALONE_HEADER2}

<main id="standalone">
		
	<!-- BEGIN: POLLS_VIEW -->

	<div class="section-title">
		<h3>{POLLS_TITLE}</h3>			
	</div>

	<div class="section-desc"></div>

	<div class="section-body">
		
		<!-- BEGIN: POLLS_FORM -->
				
		<div class="poll-box">
		
			<form name="pollvotes" action="{POLLS_SEND_URL}" method="post">
			
				<!-- BEGIN: POLLS_ROW_OPTIONS -->				
				<div class="poll-item">
					{POLLS_ROW_RADIO_ITEM}
				</div>				
				<!-- END: POLLS_ROW_OPTIONS -->
				
				<div class="poll-btn">
					<button type="submit" class="btn">{PHP.L.Vote}</button>
				</div>
			
			</form>
		
		</div>
				
		<!-- END: POLLS_FORM -->
		
		<!-- BEGIN: POLLS_RESULT -->
		
		<div class="poll-box">
		
			<!-- BEGIN: POLLS_ROW_RESULT -->				
			<div class="poll-item">
				<div class="poll-head-table">
					<div class="poll-title-td">
						{POLLS_ROW_TEXT}
					</div>
					<div class="poll-count-td">
						({POLLS_ROW_COUNT})
					</div>
				</div>
				<div class="poll-bar">
					<div class="bar_back">
						<div class="bar_front" style="width:{POLLS_ROW_PERCENT}%;"></div>
					</div>
				</div>
				
			</div>				
			<!-- END: POLLS_ROW_RESULT -->
			
		</div>	

		<!-- END: POLLS_RESULT -->	
		
		<p> {POLLS_INFO}<br />
			{POLLS_VOTERS} {PHP.skinlang.polls.voterssince} {POLLS_SINCE}<br />
			{POLLS_VIEWALL}
		</p>	
		
		<!-- BEGIN: POLLS_COMMENTS -->
		<div class="poll-comments spoiler-container {POLLS_COMMENTS_ISSHOW}"> 
		
			<div class="comments-box-title">
				<h3><a href="{POLLS_COMMENTS_URL}">{PHP.skinlang.polls.Comments} <i class="ic-socialbtn"></i> <span class="comments-amount">({POLLS_COMMENTS_COUNT})</span>{POLLS_COMMENTS_JUMP}</a></h3>						
			</div>
			
			<div class="comments-box spoiler-body">
				{POLLS_COMMENTS_DISPLAY}	
			</div>
		
		</div>
		<!-- END: POLLS_COMMENTS -->			
		
	</div>

	<!-- END: POLLS_VIEW -->
			
	<!-- BEGIN: POLLS_VIEWALL -->
	
	<div class="section-title">
		<h3>{PHP.skinlang.polls.Allpolls}</h3>			
	</div>
	
	<div class="section-desc"></div>

	<div class="section-body">

		<div class="table-cells polls-table">
		
			<!-- BEGIN: POLLS_LIST -->		
					
			<div class="table-tr">
						
				<div class="table-td poll-date" style="width:130px;">
					{POLLS_LIST_DATE}
				</div>
				
				<div class="table-td poll-icon" style="width:30px;">
					<a href="{POLLS_LIST_URL}"><img src="system/img/admin/polls.png" alt="" /></a>
				</div>
				
				<div class="table-td poll-title">
					{POLLS_LIST_TEXT}
				</div>
				
			</div>
					
			<!-- END: POLLS_LIST -->
		
		</div>

	</div>

	<!-- END: POLLS_VIEWALL -->

</main>

<script src="skins/{PHP.skin}/js/jquery.min.js"></script>
<script src="skins/{PHP.skin}/js/jquery.plugins.min.js"></script>	
<script src="skins/{PHP.skin}/js/app.js"></script>

{POLLS_STANDALONE_FOOTER}

<!-- END: MAIN -->