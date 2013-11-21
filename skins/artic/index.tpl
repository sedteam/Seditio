<!-- BEGIN: MAIN -->

<div id="title">

<table class="flat">

		<tr>
			<td style="width:50%; vertical-align:top; padding:5px;">
      
      {PHP.cfg.menu2}     &nbsp;
			
      </td>

			<td style="width:50%; vertical-align:top; padding:5px;">

      {PHP.cfg.menu3}     &nbsp;

			</td>

		</tr>
		
</table>

</div>
  
<div id="main">

	<table class="flat">

		<tr>
			<td style="width:50%; vertical-align:top; padding-right:32px;">

			{INDEX_NEWS}

			</td>

			<td style="width:50%; padding:0; vertical-align:top;">

			<div class="block" style="margin-top:0;">
				<h3 style="margin-top:0;">{PHP.skinlang.index.Newinforums}</h3>
				{PLUGIN_LATESTTOPICS}
			</div>

			<div class="block">
				<h3 style="margin-top:0;">{PHP.skinlang.index.Recentadditions}</h3>
				{PLUGIN_LATESTPAGES}
			</div>

			<div class="block">
				<h3 style="margin-top:0;">{PHP.skinlang.index.Online}</h3>
				<a href="plug.php?e=whosonline">{PHP.out.whosonline}</a> : {PHP.out.whosonline_reg_list}
			</div>

			</td>

		</tr>

	</table>

</div>

<!-- END: MAIN -->