<!-- BEGIN: MAIN -->

<div class="units-row">
    
    <div class="unit-60">
        {INDEX_NEWS}
    </div>
    
    <div class="unit-40">
      
      <div class="headline"><h3>{PHP.skinlang.index.Newinforums}</h3></div>
			<div class="box odd">				
				{PLUGIN_LATESTTOPICS}
			</div>
      
      <div class="headline"><h3>{PHP.skinlang.index.Recentadditions}</h3></div>
			<div class="box even">				
				{PLUGIN_LATESTPAGES}
			</div>
      
      <div class="headline"><h3>{PHP.skinlang.index.Recentcomments}</h3></div>
			<div class="box odd">				
				{PLUGIN_LATESTCOMMENTS}
			</div>
      
      <div class="headline"><h3>{PHP.skinlang.index.Polls}</h3></div>
			<div class="box even">	      
      {PLUGIN_LATESTPOLL}
      </div>
			
      <div class="headline"><h3>{PHP.skinlang.index.Online}</h3></div>
			<div class="box odd">
				<a href="{PHP.out.whosonline_link}">{PHP.out.whosonline}</a> : {PHP.out.whosonline_reg_list}
			</div>    
    
    </div>
    
</div>

<!-- END: MAIN -->