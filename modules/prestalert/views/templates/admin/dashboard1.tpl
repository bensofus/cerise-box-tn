<style>
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

    #alertList .alert-upgrade, #alertList .alert-notfound {
        display: none;
    }

    .alertList-table {
        display: none;
    }

    .iarrow {
        width: 15px;
        margin: 5px;
    }
</style>
<div class="panel cg-panel" id="alerts">
    <h3><i class="material-icons">warning</i>&nbsp;&nbsp;&nbsp;&nbsp;PrestAlert {l s='Monitoring Log' mod='prestalert'}</h3>
    <div id="alerts-container">
        <div id="alertList">
            <div class="alert alert-info d-print-none alert-upgrade" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="material-icons">info</i></span>
                </button>
                <div class="alert-text">
                    <p>{l s='Upgrade to our Premium version to unlock this feature' mod='prestalert'}. </br></br><a style="font-weight: bold; text-decoration: underline" href="{$link->getAdminLink('AdminModules', true)|cat:'&configure=prestalert'}">{l s='Upgrade your subscription now for an enhanced experience' mod='prestalert'}</a>.</p>
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

<script>

    $(document).ready(function(){
        getAlerts(contextPsAccounts['currentShop']['uuid'], 5);
    });

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
                    $('#alerts.panel').show();
                } else {
                    $('#alertList .alert-notfound').show();
                }
            } else {
                $('#alertList .alert-upgrade').show();
            }
            console.log(response);
        });
    }
</script>