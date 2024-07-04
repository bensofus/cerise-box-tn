<div class="pos-newsletter-widget block_newsletter">
<form class="pos-newsletter-form" action="{$urls.current_url}#footer" method="post">
    <input name="email" type="email" value="{$value}" placeholder="{if !empty($settings.placeholder)}{$settings.placeholder}{else}{l s='Your email address' d='Shop.Forms.Labels'}{/if}" required >
	 <input
              class="hidden-xs-up"
              name="submitNewsletter"
              type="submit"
              value="{l s='OK' d='Shop.Theme.Actions'}"
            >
   <button class="pos-newsletter-button" name="submitNewsletter" value="1" type="submit">
        {if $settings.use_icon == 'yes'}
            <i class="{$settings.icon}"></i>
        {else}
            <span>{$settings.subscribe_text}</span>
        {/if}
    </button>
    <input type="hidden" name="action" value="0">
    <div class="pos-newsletter-response"></div>
    {hook h='displayNewsletterRegistration'}
    {if isset($id_module) && !$settings.disable_psgdpr}
        {hook h='displayGDPRConsent' id_module=$id_module}
    {/if}
</form>
</div>