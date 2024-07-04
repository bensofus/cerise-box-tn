{extends file="helpers/form/form.tpl"}

{block name="script"}


$(document).ready(function() {

		 $('.iframe-upload').fancybox({
			'width'		: 900,
			'height'	: 600,
			'type'		: 'iframe',
      		'autoScale' : false,
      		'autoDimensions': false,
      		 'fitToView' : false,
  			 'autoSize' : false,
  			 onUpdate : function(){ $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
			 	 $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));},
  			 afterShow: function(){
			 	 $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
			 	 $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
			},
  		  });
});

{/block}





{block name="input"}


	{if $input.type == 'pos_image'}

	 	{foreach from=$languages item=language}
	        {if $languages|count > 1}
	        <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $id_language}style="display:none"{/if}>
	            {/if}
	            <div class="col-lg-7">
	                <input type="text" id="{$input.name}_{$language.id_lang|intval}" name="{$input.name}_{$language.id_lang|intval}" value="{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}" data-serializable="true"/>
	                <a href="filemanager/dialog.php?type=1&field_id={$input.name}_{$language.id_lang|intval}" class="btn btn-default iframe-upload"  data-input-name="{$input.name}_{$language.id_lang|intval}" type="button">{l s='Select image' mod='pospopup'} <i class="icon-angle-right"></i></a>
	            </div>
	            {if $languages|count > 1}
	            <div class="col-lg-2">
	                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
	                    {$language.iso_code}
	                    <span class="caret"></span>
	                </button>
	                <ul class="dropdown-menu">
	                    {foreach from=$languages item=lang}
	                    <li><a href="javascript:hideOtherLanguage({$lang.id_lang});" tabindex="-1">{$lang.name}</a></li>
	                    {/foreach}
	                </ul>
	            </div>
	            {/if}
	            {if $languages|count > 1}
	        </div>
	        {/if}
        {/foreach}
	{elseif $input.type == 'image-select'}
		<div class="image-select {if isset($input.direction)} image-select-{$input.direction}{/if}">

			{foreach $input.options.query AS $option }
				<input id="{$input.name|escape:'html':'utf-8'}-{$option.id_option}" type="radio"
						name="{$input.name|escape:'html':'utf-8'}"
						value="{$option.id_option}" {if $fields_value[$input.name] == ''}{if $option@index eq 0} checked{/if}{/if} {if $option.id_option == $fields_value[$input.name]}checked{/if} />
				<div class="image-option">
					
					<label class="image-option-label"
							for="{$input.name|escape:'html':'utf-8'}-{$option.id_option}"></label>
					<img src="{$base_url}modules/pospopup/img/{$option.img}" alt="{$option.name}"
							class="img-responsive"/>
					<span class="image-option-title">{$option.name}</span>
				</div>
			{/foreach}
		</div>
	{else}
		{$smarty.block.parent}
    {/if}
{/block}



