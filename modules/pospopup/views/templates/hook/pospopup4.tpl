<div id="posnewsletterpopup" class="posnewsletterpopup-style-4 text-{$text_color}">
    <div class="pnp-close">
        <i class="icon-rt-close-outline"></i>
    </div>
	<div class="pnp-content-wrapper">
			<div class="pnp-content">
				<h3 class="pnp-title">{$title}</h3>
				<div class="pnp-desc">{$txt nofilter}</div>
				<div class="pnp-newsletter-form">
					<form data-action="{url entity=index params=['fc' => 'module', 'module' => 'ps_emailsubscription', 'controller' => 'subscription']}" method="post" class="flex-layout center-vertical">
						<input class="inputNew form-control grey newsletter-input" type="text" name="email" size="18"
							placeholder="{l s='Enter your e-mail' mod='pospopup'}" value=""/>
						<button type="submit" name="submitpNewsletter" class="btn btn-default pos-btn-newsletter">
							<span>{l s='Subscribe' mod='pospopup'}</span>
						</button>
						<input type="hidden" name="action" value="0"/>
					</form>
				</div>
				<div class="pnp-close-checkbox">
					<span class="custom-checkbox">
						<input type="checkbox" name="pnp-checkbox" id="pnp-checkbox"/>
						<span><i class="icon-rt-checkmark checkbox-checked"></i></span>
						<label for="pnp-checkbox">{l s='Do not show again.' mod='pospopup'}</label>
					</span>
				</div>
			</div>
			<div class="pnp-image"><img src="{$popup_image}" alt="" /></div>
	</div>
</div>
<div id="posnewsletterpopup-overlay"></div>
