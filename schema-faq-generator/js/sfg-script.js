jQuery( document ).ready( function(){
    jQuery('.sfg_faq_list').sortable();

    function convertNewLinesToBr(result) {
        result = result.replace(/(?:\n)/g, '\\n');
        return result;
    }

    function schemaGenerate() {
        // prepare start, end etc..
        let items = [];
        let start =
            `<script type="application/ld+json">{"@context": "https://schema.org","@type": "FAQPage","mainEntity": [`;
        let end =`]}</script>`;
        let result = '';

        // add name
        jQuery('.sfg_name_input').each( function(index, element){
            items[index] = { name: jQuery(element).val() } ;
        });
        // add text
        jQuery('.sfg_text_input').each( function(index, element){
            items[index].text =  jQuery(element).val() ;
        });

        // generate items list
        for ( let i = 0; i < items.length; i++ ) {
            let coma = i == items.length - 1 ? '' : ',';
            console.log(i);
            result += `{"@type": "Question","name": "` + items[i].name + `","acceptedAnswer": {"@type": "Answer","text": "` + items[i].text + `"}}` + coma;
        }
        result = start + result + end;
        sfg_output.innerHTML = convertNewLinesToBr(result);
    }

    function addItem() {
        let newItem = `
             <li class="sfg_item">
                <div class="sfg_draggable">draggable</div>
                <div class="sfg_item_content">
                    <input placeholder="question" type="text" class="sfg_input sfg_name_input">
                    <textarea placeholder="answer" class="sfg_input sfg_text_input"></textarea>
                </div>
                <button class="sfg_delete_item" type="button">delete item</button>
             </li>
        `;
        jQuery('.sfg_faq_list').append( newItem );

        jQuery('.sfg_delete_item').each( function () {
            jQuery(this).on( 'click', function () {
                jQuery(this).parent().remove();
                console.log('button deleted');
                schemaGenerate();
            } );
        });
    }

    // init generate
        schemaGenerate();

    // on-input regenerate
    jQuery(document).on( 'input', '.sfg_input', function () {
        schemaGenerate();
    });

    // add-item regenerate
    jQuery('#sfg_add_new').on( 'click', function() {
        addItem();
        schemaGenerate();
    });

    // remove-item regenerate
    jQuery('.sfg_delete_item').each( function () {
        jQuery(this).on( 'click', function () {
            jQuery(this).parent().remove();
            schemaGenerate();
        } );
    });

    // sort-items regenerate
    jQuery( '.sfg_faq_list' ).on( 'sortbeforestop', function() {
        schemaGenerate();
    });

    // copy schema
    jQuery('#sfg_copy').on( 'click', function () {
        let sourceElement = document.getElementById('sfg_output');
        sourceElement.select();
        sourceElement.setSelectionRange(0, 99999);
        document.execCommand("copy");
    });
});



