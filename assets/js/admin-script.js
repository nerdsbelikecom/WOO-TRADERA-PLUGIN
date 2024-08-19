jQuery(document).ready(function($) {
    function toggleAdFields() {
        var isIntegrated = $('#_tradera_integration').is(':checked');
        var adFormat = $('#_tradera_ad_format').val();
        var $adFields = $('#tradera_ad_fields');

        // Om integration inte är aktiverad, dölja alla fält
        if (!isIntegrated) {
            $adFields.empty().hide();
            return;
        }

        // Om integration är aktiverad, visa fälten
        $adFields.show().empty();

        // Dynamiskt visa fält baserat på annonsformat
        if (adFormat === 'auction' || adFormat === 'auction_buy_now') {
            $adFields.append(`
                <p class='form-field'>
                    <label for='_tradera_auction_price'>Utropspris</label>
                    <input type='text' class='short' id='_tradera_auction_price' name='_tradera_auction_price' value='${$('#_tradera_auction_price').val()}' />
                    <span class='description'>Ange utropspriset för auktionen.</span>
                </p>
                <p class='form-field'>
                    <label for='_tradera_auction_end_date'>Avslutningsdatum</label>
                    <input type='date' class='short' id='_tradera_auction_end_date' name='_tradera_auction_end_date' value='${$('#_tradera_auction_end_date').val()}' />
                    <span class='description'>Välj ett datum mellan 3 och 14 dagar från nu för avslutning.</span>
                </p>
                <p class='form-field'>
                    <label for='_tradera_auction_end_time'>Avslutningstid</label>
                    <input type='time' class='short' id='_tradera_auction_end_time' name='_tradera_auction_end_time' value='${$('#_tradera_auction_end_time').val()}' />
                    <span class='description'>Välj en tid för auktionen att avslutas.</span>
                </p>
            `);
        }

        if (adFormat === 'buy_now' || adFormat === 'auction_buy_now') {
            $adFields.append(`
                <p class='form-field'>
                    <label for='_tradera_buy_now_price'>Köp nu pris</label>
                    <input type='text' class='short' id='_tradera_buy_now_price' name='_tradera_buy_now_price' value='${$('#_tradera_buy_now_price').val()}' />
                    <span class='description'>Lämna detta fält tomt om du vill använda din webshops pris.</span>
                </p>
            `);
        }
    }

    // Initialt tillstånd
    toggleAdFields();

    // När integration checkboxen eller annonsformatet ändras
    $('#_tradera_integration, #_tradera_ad_format').change(function() {
        toggleAdFields();
    });
});
