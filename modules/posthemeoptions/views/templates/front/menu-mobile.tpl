<div id="menu-icon"><i class="icon-rt-bars-solid"></i></div> 
<div class="menu-mobile-content" id="mobile_menu_wrapper">
	<div class="menu-mobile-inner">
		{hook h='displayMegamenuMobileTop'} 
		{if $vmenu}
		<ul class="nav nav-mobile-menu" role="tablist"> 
			<li class="nav-item">
				<a class="nav-link active"  data-toggle="tab" href="#tab-mobile-megamenu" role="tab" aria-controls="mobile-megamenu" aria-selected="true">{l s='Menu' mod='posthemeoptions'}</a>
				
			</li>
			<li class="nav-item">
				<a class="nav-link"  data-toggle="tab" href="#tab-mobile-vegamenu" role="tab" aria-controls="mobile-vegamenu" aria-selected="true">{l s='Categories' mod='posthemeoptions'}</a>
			</li>
		</ul>
		{else}
		<h4 class="menu-mobile-title">{l s='Menu' mod='posthemeoptions'}</h4>
		{/if}
		{if $vmenu}
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab-mobile-megamenu" role="tabpanel" aria-labelledby="megamenu-tab">
		{/if}
			{$hmenu nofilter}	
		{if $vmenu}
			</div>
			<div class="tab-pane fade" id="tab-mobile-vegamenu" role="tabpanel" aria-labelledby="vegamenu-tab">
			{$vmenu nofilter}
			</div>
		</div>
		{/if}
		<div class="menu-mobile-bottom">
			{hook h='displayMegamenuMobileBottom'}
			<div class="menu-close btn btn-primary"> 
				{l s='Close' mod='posthemeoptions'}
			</div>
		</div>
	</div>
</div>