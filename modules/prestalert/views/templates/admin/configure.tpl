{*
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<style>
	#urls-container {
		background-color: #fff;
		padding: 20px;
		width: 80%;
		text-align: center;
		margin: 0 auto;
		margin-top: 20px;
		text-align: -webkit-center;
	}

	#urlInputContainer {
		width: 80%;
		background-color: #fff;
		display: flex;
		flex-flow: row nowrap;
		align-items: center;
		margin-bottom: 20px;
		justify-content: space-between;
	}

	label.addurl {
		display: block;
		margin-bottom: 5px;
		font-size: 14px;
	}

	input#urlInput {
		width: 80%;
		padding: 8px;
		box-sizing: border-box;
		border: 1px solid #ccc;
		font-size: 14px;
		text-align: center;
	}

	button#addurl, button#addphone {
		background-color: black;
		color: white;
		border: none;
		padding: 8px 15px;
		cursor: pointer;
		font-size: 14px;
	}
	button#addphone {
		margin-left: 15px;
		padding: 10px;
	}

	button#addphone[disabled] {
		opacity: 0.5; /* Define la opacidad del botón cuando está deshabilitado */
	}
	.urlListItem {
		list-style: none;
		margin: 0 auto;
		border: 1px solid #ddd;
		margin-bottom: 10px;
		padding: 10px;
		display: flex;
		align-items: center;
	}

	.urlText {
		flex-grow: 1;
	}

	.urlActions button {
		background-color: #e74c3c;
		color: #fff;
		border: none;
		padding: 5px 10px;
		cursor: pointer;
		font-size: 12px;
	}

	ul#urlList {
		padding: unset;
	}

	.sidebar .list-group-item.tab-selected:hover {
		background-color: #25b9d7;
		color: black !important;
		font-weight: bolder;
	}

	.sidebar .list-group-item.tab-selected {
		background-color: #25b9d7;
		color: white !important;
		font-weight: bolder;
	}

	.sidebar .list-group-item.tab-selected:not(.tab-selected) {
		color: black !important;
	}

	#urls.panel {
		display: none;
	}

	#urls-container {
		display: none;
	}

	#sms.panel {
		display: none;
	}

	#sms-container {
		display: none;
	}

	#alerts.panel {
		display: none;
	}

	#alertList table {
		width: 100%;
		border-collapse: collapse;
		border-color: #eff1f2;
	}

	#alertList th, td {
		padding: 10px;
		text-align: center;
	}

	#alertList th {
		font-size: 1.2em;
		font-weight: normal;
		background-color: #25b9d7;
		color: white;
	}

	#alertList tbody tr:nth-child(even) {
		background-color: #f2f2f2; /* Color gris para las filas pares */
	}

	#alertList tbody tr:nth-child(odd) {
		background-color: #ffffff; /* Color blanco para las filas impares */
	}

	#alertList tbody tr {
		font-size: 12px;
	}

	#alertList .alert-upgrade, #alertList .alert-notfound, .alert-maxurls, .alert-subscribeurls, .alert-activateurls, .alert-domainerror, .alert-smsupgrade, .alert-phonesaved {
		display: none;
	}

	.alertList-table {
		display: none;
	}

	.iarrow {
		width: 15px;
		margin: 5px;
	}

	.liv-wa-group-number-phone .js-dropdown-item.dropdown-item {
		display: block;
		width: 100%;
		border: none;
		background: #fff;
		border-bottom: 1px solid #ddd;
		text-align: left;
	}
	.liv-wa-group-number-phone .btn.btn-secondary.dropdown-toggle:before {
		content: "";
		border-top: 5px solid #777;
		border-left: 5px solid transparent;
		border-right: 5px solid transparent;
		position: absolute;
		right: 8px;
		top: 50%;
		margin-top: -2px;
		border-radius: 2px;
	}
	.liv-wa-group-number-phone .js-dropdown-item.dropdown-item img {
		display: inline-block;
		vertical-align: -1px;
		margin-right: 2px;
	}
	.liv-wa-group-number-phone .js-dropdown-item.dropdown-item:last-child {
		border-bottom: none;
	}
	.input-group.liv-wa-group-number-phone .input-group-addon.call_prefix {
		padding-left: 0;
		padding-right: 0;
		width: 50px;
		border-right: none;
	}
	input#PRESTALERT_NUMBER_PHONE {
		width: 200px;
	}
	.liv-wa-group-number-phone .btn.btn-secondary.dropdown-toggle {
		width: 100%;
		background: none;
		white-space: nowrap;
		display: block;
		text-align: left;
		padding: 0 25px 0 10px;
		min-height: 36px;
		line-height: 34px;
	}
	.liv-wa-group-number-phone .input-group-addon.country {
		padding: 0;
	}
	.liv-wa-group-number-phone .dropdown-menu.js-choice-options {
		max-height: 200px;
		overflow-y: scroll;
		width: 100%;
		min-width: 300px;
		text-align: left;
	}
	.liv-wa-group-number-phone .dropdown-toggle{
		padding: 0;
	}
	.liv-wa-group-number-phone .input-group-addon.country {
		min-width: 200px;
		background: #ffffff;
	}
	.js-dropdown-item.dropdown-item {
		background: #fff none repeat scroll 0 0;
		border-bottom: 1px solid #ccc;
		border-right: 0 none;
		padding: 10px;
	}
	.liv-wa-group-number-phone button {
		outline: none!important;
		box-shadow: none!important;
	}
</style>
<div class="sidebar navigation col-md-3">
	<nav class="list-group">
		<a href="#" class="list-group-item tab-selected ps-tab">{l s='Suscription Configuration' mod='prestalert'}</a>
		<a href="#urls" class="list-group-item cg-mnt-tab">{l s='Monitoring Configuration' mod='prestalert'}</a>
		<a href="#alerts" class="list-group-item sms-alerts-tab">{l s='SMS Alerts Configuration' mod='prestalert'}</a>
		<a href="#alerts" class="list-group-item cg-alerts-tab">{l s='Alerts Log' mod='prestalert'}</a>
	</nav>
</div>

<div class="col-md-9">
	<div class="ps-panel cg-panel">
		<prestashop-accounts></prestashop-accounts>
		<div id="prestashop-cloudsync"></div>
		<div id="ps-billing"></div>
	</div>

	<div id="ps-modal"></div>

	<div id="module-config">
		<div class="panel cg-panel" id="urls">
			<h3><i class="material-icons">link</i>&nbsp;&nbsp;&nbsp;&nbsp;{l s='Monitoring Configuration' mod='prestalert'}</h3>
			<div class="alert alert-warning d-print-none alert-maxurls" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true"><i class="material-icons">warning</i></span>
				</button>
				<div class="alert-text">
					<p>{l s='You have reached the maximum number of URLs to monitor included with your subscription' mod='prestalert'}. </br></br><a class="upgrade-link" style="font-weight: bold; text-decoration: underline" href="#">{l s='Upgrade your subscription now for an enhanced experience' mod='prestalert'}</a>.</p>
				</div>
			</div>
			<div class="alert alert-warning d-print-none alert-subscribeurls" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true"><i class="material-icons">warning</i></span>
				</button>
				<div class="alert-text">
					<p><a class="upgrade-link" style="font-weight: bold; text-decoration: underline" href="#">{l s='Upgrade your subscription now for an enhanced experience' mod='prestalert'}</a>.</p>
				</div>
			</div>
			<div class="alert alert-info d-print-none alert-activateurls" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true"><i class="material-icons">warning</i></span>
				</button>
				<div class="alert-text">
					<p><a class="upgrade-link" style="font-weight: bold; text-decoration: underline" href="#">{l s='Unlock the full experience – activate your subscription now and start enjoying the ultimate service!' mod='prestalert'}</a>.</p>
				</div>
			</div>
			<div class="alert alert-danger d-print-none alert-domainerror" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true"><i class="material-icons">danger</i></span>
				</button>
				<div class="alert-text">
					<p>{l s='The entered URL does not belong to the shop\'s domain' mod='prestalert'}.</p>
				</div>
			</div>
			<div id="urls-container">
				<div id="urlInputContainer">
					<label class="addurl" for="urlInput">URL:</label>
					<input type="url" id="urlInput" title="{l s='Enter a valid url' mod='prestalert'}" placeholder="{l s='Write down the URL to monitor' mod='prestalert'}" pattern="https?://.+" required>
					<button type="submit" id="addurl" ><strong>{l s='Add' mod='prestalert'}</strong></button>
				</div>
				<ul id="urlList"></ul>
			</div>
		</div>

		<div class="panel cg-panel" id="alerts">
			<h3><i class="material-icons">warning</i>&nbsp;&nbsp;&nbsp;&nbsp;{l s='Monitoring Log' mod='prestalert'}</h3>
			<div id="alerts-container">
				<div id="alertList">
					<div class="alert alert-info d-print-none alert-upgrade" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true"><i class="material-icons">info</i></span>
						</button>
						<div class="alert-text">
							<p>{l s='Upgrade to our Premium version to unlock this feature' mod='prestalert'}. </br></br><a class="upgrade-link" style="font-weight: bold; text-decoration: underline" href="#">{l s='Upgrade your subscription now for an enhanced experience' mod='prestalert'}</a>.</p>
						</div>
					</div>
					<div class="alert alert-warning d-print-none alert-notfound" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true"><i class="material-icons">warning</i></span>
						</button>
						<div class="alert-text">
							<p>{l s='No records found.'}</p>
						</div>
					</div>
					<table class="alertList-table" border="1">
						<thead>
						<tr>
							<th>{l s='Date' mod='prestalert'}</th>
							<th>URL</th>
							<th>{l s='Response Code' mod='prestalert'}</th>
						</tr>
						</thead>
						<tbody class="err-table-body"></tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="panel cg-panel" id="sms">
			<h3><i class="material-icons">link</i>&nbsp;&nbsp;&nbsp;&nbsp;{l s='SMS Notifications' mod='prestalert'}</h3>
			<div class="alert alert-info d-print-none alert-smsupgrade" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true"><i class="material-icons">warning</i></span>
				</button>
				<div class="alert-text">
					<p><a class="upgrade-link" style="font-weight: bold; text-decoration: underline" href="#">{l s='Unlock the full experience – activate your subscription now and start enjoying the ultimate service!' mod='prestalert'}</a>.</p>
				</div>
			</div>
			<div class="alert alert-success d-print-none alert-phonesaved" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true"><i class="material-icons">check</i></span>
				</button>
				<div class="alert-text">
					<p>{l s='Phone number saved with success' mod='prestalert'}.</p>
				</div>
			</div>
			<div id="sms-container">
				<div class="form-group">
					<input type="hidden" name="PRESTALERT_CALL_PREFIX" id="PRESTALERT_CALL_PREFIX" value="{$PRESTALERT_CALL_PREFIX|escape:'html':'UTF-8'}"/>
					<input type="hidden" name="PRESTALERT_COUNTRY_ISO" id="PRESTALERT_COUNTRY_ISO" value="{$PRESTALERT_COUNTRY_ISO}">
					<input type="hidden" name="PRESTALERT_COUNTRY_NAME" id="PRESTALERT_COUNTRY_NAME" value="{$PRESTALERT_COUNTRY_NAME}">
					<div class="input-group liv-wa-group-number-phone">
						<span class="input-group-addon country">
							<div class="dropdown">
								<button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-flip="false"><img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`prestalert/views/img/`$PRESTALERT_COUNTRY_ISO|strtolower|escape:'htmlall':'UTF-8'`")}.gif" />&nbsp; {$PRESTALERT_COUNTRY_NAME|escape:'html':'UTF-8'} </button>
								<div class="dropdown-menu js-choice-options">
									{if $countries}
										{foreach from =$countries item='country'}
											<button type="button" class="js-dropdown-item dropdown-item" data-call_prefix="{$country.call_prefix|escape:'html':'UTF-8'}" data-value="{$country.id_country|intval}" data-name="{$country.name|escape:'htmlall':'UTF-8'}" data-iso="{$country.iso_code|escape:'htmlall':'UTF-8'}"><img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`prestalert/views/img/`$country.iso_code|strtolower|escape:'htmlall':'UTF-8'`")}.gif" />&nbsp;<span>{$country.name|escape:'html':'UTF-8'}</span></button>
										{/foreach}
									{/if}
								</div>
							</div>
						</span>
						<span class="input-group-addon call_prefix">+{$PRESTALERT_CALL_PREFIX|escape:'html':'UTF-8'}</span>
						<input type="text" value="{$PRESTALERT_NUMBER_PHONE|escape:'html':'UTF-8'}" name="PRESTALERT_NUMBER_PHONE" id="PRESTALERT_NUMBER_PHONE" required pattern="\d{10}" minlength="10" maxlength="10"/>
						<button type="submit" id="addphone" disabled><strong>{l s='Save' mod='prestalert'}</strong></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<script src="{$urlAccountsCdn|escape:'htmlall':'UTF-8'}" rel=preload></script>
<script src="{$urlCloudsync|escape:'htmlall':'UTF-8'}"></script>
<script src="{$urlBilling|escape:'htmlall':'UTF-8'}"></script>

<script>
	var customer;
    window?.psaccountsVue?.init();

    if(window.psaccountsVue.isOnboardingCompleted() != true)
    {
    	document.getElementById("module-config").style.opacity = "0.5";
    }

	// Cloud Sync
	const cdc = window.cloudSyncSharingConsent;

	cdc.init('#prestashop-cloudsync');
	cdc.on('OnboardingCompleted', (isCompleted) => {
		console.log('OnboardingCompleted', isCompleted);
		
	});
	cdc.isOnboardingCompleted((isCompleted) => {
		console.log('Onboarding is already Completed', isCompleted);
	});


	window.psBilling.initialize(window.psBillingContext.context, '#ps-billing', '#ps-modal', (type, data) => {
		// Event hook listener
		switch (type) {
		  case window.psBilling.EVENT_HOOK_TYPE.BILLING_INITIALIZED:
		    console.log('Billing initialized', data);
		    customer = data;
		    break;
		  case window.psBilling.EVENT_HOOK_TYPE.SUBSCRIPTION_UPDATED:
		    console.log('Sub updated', data);
		    break;
		  case window.psBilling.EVENT_HOOK_TYPE.SUBSCRIPTION_CANCELLED:
		    console.log('Sub cancelled', data);
		    break;
		}
	});

	$(document).ready(function(){
		updateUrlList();
		tabInit();
		smsInit();
	});

	function tabInit() {
		$(document).on('click', '.sidebar .list-group-item.ps-tab', function() {
			$('.tab-selected').removeClass('tab-selected');
			$('.cg-panel').hide();
			$(this).addClass('tab-selected');
			$('.ps-panel').show();
		});

		$(document).on('click', '.sidebar .list-group-item.cg-mnt-tab', function() {
			$('.tab-selected').removeClass('tab-selected');
			$('.cg-panel').hide();
			$(this).addClass('tab-selected');
			$('#urls').show();
			$.ajax({
				url: "https://prestalert.com/subsconf.php",
				cache: false,
				dataType: 'json',
				data: {
					uuid: contextPsAccounts['currentShop']['uuid']
				}
			}).done(function(response) {
				if (response.active == '1') {
					$('.alert-activateurls').hide();
					$('#urls-container').show();
				} else {
					$('.alert-activateurls').show();
					$('#urls-container').hide();
				}
			});
		});

		$(document).on('click', '.sidebar .list-group-item.sms-alerts-tab', function() {
			$('.tab-selected').removeClass('tab-selected');
			$('.cg-panel').hide();
			$(this).addClass('tab-selected');
			$('#sms').show();
			$.ajax({
				url: "https://prestalert.com/subsconf.php",
				cache: false,
				dataType: 'json',
				data: {
					uuid: contextPsAccounts['currentShop']['uuid']
				}
			}).done(function(response) {
				if (response.sms == '1') {
					$('.alert-smsupgrade').hide();
					$('#sms-container').show();
				} else {
					$('.alert-smsupgrade').show();
					$('#sms-container').hide();
				}
			});
		});

		$(document).on('click', '.sidebar .list-group-item.cg-alerts-tab', function() {
			$('.tab-selected').removeClass('tab-selected');
			$('.cg-panel').hide();
			$(this).addClass('tab-selected');
			getAlerts(contextPsAccounts['currentShop']['uuid'], 200);
			$('#alerts').show();
		});

		$(document).on('click', '.upgrade-link', function() {
			$('.sidebar .list-group-item.ps-tab').click();
		});

		$(document).on('click', '#addurl', function() {
			addUrl(contextPsAccounts['currentShop']['uuid'], contextPsAccounts['currentShop']['domain']);
		});
	}

	function getAlerts(uuid, maxrows) {

		$('#alertList .alert-upgrade').hide();
		$('#alertList .alert-notfound').hide();
		$('#alertList .err-table-body').empty()
		$('.alertList-table').hide();

		$.ajax({
			url: "https://prestalert.com/errors.php",
			cache: false,
			dataType: 'json',
			data: {
				uuid: uuid
			}
		}).done(function(response) {
			if(response.allow_list) {
				if (response.data && response.data.length > 0) {
					let count_rows = 0;
					response.data.forEach(function (alert) {
						count_rows++;
						$('.alertList-table').show();
						let arrowtype = 'down';
						if(alert['restored']) {
							arrowtype = 'up';
						}
						if(count_rows <= maxrows) {
							$('#alertList .err-table-body').append('<tr><td><img class="iarrow" src="/modules/prestalert/views/img/' + arrowtype + '.svg" alt="' + arrowtype + ' down" title="' + arrowtype + ' down"/> ' + alert['fecha'] + '</td><td><a href="' + alert['url'] + '" target="_blank">' + alert['url'] + '</a></td><td>' + alert['error_code'] + '</td></tr>');
						}
					});
				} else {
					$('#alertList .alert-notfound').show();
				}
			} else {
				$('#alertList .alert-upgrade').show();
			}
			console.log(response);
		});
	}

	function validateURL() {
		var urlInput = document.getElementById('urlInput');
		var url = urlInput.value;

		if (urlInput.checkValidity()) {
			$('#urlInput').css('border', '1px solid black');
			return true; // Envía el formulario
		} else {
			$('#urlInput').css('border', '1px solid salmon');
			return false; // No envía el formulario
		}
	}

	function updateUrlList() {
		var urlList = document.getElementById("urlList");
		var uuid = contextPsAccounts['currentShop']['uuid'];

		$.ajax({
			url: "/modules/prestalert/urls.php?action=get_urls",
			cache: false
		}).done(function(html) {
			var items = JSON.parse(html);
			$('#urlList').empty();
			if (items && items['urls'] && items['urls'].length > 0) {
				items['urls'].forEach(function (row) {
					let listItem = document.createElement("li");
					listItem.className = "urlListItem";
					{literal}
					listItem.innerHTML = '<span class="urlText">' + row.url + '</span><div class="urlActions"> <button onclick="removeUrl(' + row.id_url + ',\'' + uuid + '\')">{/literal}{l s='Delete' mod='prestalert'}{literal}</button></div>';
					{/literal}
					urlList.appendChild(listItem);
				});
			}
		});
	}

	function addUrl(uuid, domain) {
		if(validateURL()) {
			var urlInput = document.getElementById("urlInput");
			if (urlInput.value.trim() !== "") {
				var urlValue = urlInput.value;
				var max_urls = 0;
				$.ajax({
					url: "https://prestalert.com/subsconf.php",
					cache: false,
					dataType: 'json',
					data: {
						uuid: uuid
					}
				}).done(function(response) {
					if (response.max_urls && response.max_urls > 0) {
						max_urls = response.max_urls;
					}
					if (response.max_urls && response.max_urls > 0) {
						max_urls = response.max_urls;
					}
					if(!response.id_subscription) {
						$('.alert-subscribeurls').show();
						return false;
					} else {
						$('.alert-subscribeurls').hide();
					}
					//Add after max_urls response
					$.ajax({
						url: "/modules/prestalert/urls.php?action=add",
						cache: false,
						dataType: 'json',
						data: {
							url: urlValue,
							max_urls : max_urls,
							domain: domain,
							uuid: uuid
						}
					}).done(function(response) {
						if(response.success) {
							$('#urlInput').css('border', '1px solid black');
							updateUrlList();
						} else {
							if(response.max_urls) {
								$('.alert-maxurls').show();

								setTimeout(function() {
									$('.alert-maxurls').fadeOut();
								}, 10000);
							}
							if(response.domain) {
								$('.alert-domainerror').show();

								setTimeout(function() {
									$('.alert-domainerror').fadeOut();
								}, 10000);
							}
							$('#urlInput').css('border', '1px solid salmon');
						}
					});
					console.log(response);
				});
			} else {
				console.error('La URL está vacía o contiene solo espacios.');
			}
		}
	}

	function removeUrl(id, uuid) {
		if(confirm('Delete selected URL ?')) {
			$.ajax({
				url: "/modules/prestalert/urls.php?action=delete",
				cache: false,
				data: {
					id: id,
					uuid: uuid
				}
			}).done(function () {
				updateUrlList();
			});
		}
	}

	function smsInit() {
		$(document).on('click','.liv-wa-group-number-phone .js-dropdown-item',function(){
			var prefix = $(this).attr('data-call_prefix');
			$('#PRESTALERT_CALL_PREFIX').val(prefix);
			var country_name = $(this).attr('data-name');
			$('#PRESTALERT_COUNTRY_NAME').val(country_name);
			var country_iso = $(this).attr('data-iso');
			$('#PRESTALERT_COUNTRY_ISO').val(country_iso);
			$('.input-group-addon.call_prefix').html('+'+$(this).data('call_prefix'));
			$('.liv-wa-group-number-phone .dropdown-toggle').html($(this).html());
		});

		$(document).on('click','#addphone',function(){
			var prefix = $('#PRESTALERT_CALL_PREFIX').val();
			var phone = $('#PRESTALERT_NUMBER_PHONE').val();
			var country_iso = $('#PRESTALERT_COUNTRY_ISO').val();
			var country_name = $('#PRESTALERT_COUNTRY_NAME').val();
			var internationalPhone = '' + prefix + '' + phone;
			//var phoneIsvalid = validatePhone(internationalPhone);
			if(true) {
				$.ajax({
					url: "/modules/prestalert/phone.php?action=addphone",
					cache: false,
					data: {
						prefix: prefix,
						phone: phone,
						country_iso: country_iso,
						country_name: country_name,
						uuid: contextPsAccounts['currentShop']['uuid']
					}
				}).done(function () {
					$('.alert-phonesaved').show();
				});
			}
		});
	}

	//Validación del teléfono
	document.getElementById('PRESTALERT_NUMBER_PHONE').addEventListener('input', function()
	{
		var phoneNumber = this.value;

		// Verificar si el número de teléfono es numérico
		var isNumeric = /^\d+$/.test(phoneNumber);

		// Verificar si el número de teléfono tiene al menos 6 dígitos y no más de 10
		var isValidLength = phoneNumber.length >= 6 && phoneNumber.length <= 10;

		// Cambiar el color del borde del input dependiendo de si el número de teléfono es válido o no
		if (isNumeric && isValidLength) {
			this.style.border = '1px solid black'; // Si es válido, cambiar a color negro
			document.getElementById('addphone').removeAttribute('disabled'); // Habilitar el botón
		} else {
			this.style.border = '1px solid salmon'; // Si no es válido, cambiar a color salmón
			document.getElementById('addphone').setAttribute('disabled', 'disabled'); // Deshabilitar el botón
		}
	});

</script>
