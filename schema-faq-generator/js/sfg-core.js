jQuery( document ).ready( function(){

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
            result += `{"@type": "Question","name": "` + items[i].name + `","acceptedAnswer": {"@type": "Answer","text": "` + items[i].text + `"}}` + coma;
        }
        result = start + result + end;
        sfg_output.innerHTML = convertNewLinesToBr(result);
    }

    function addItem() {
        let newItem = `
             <li class="sfg_item">
                <div class="sfg_draggable">
                    <svg viewBox="0 0 24 24"><path d="M9,3H11V5H9V3M13,3H15V5H13V3M9,7H11V9H9V7M13,7H15V9H13V7M9,11H11V13H9V11M13,11H15V13H13V11M9,15H11V17H9V15M13,15H15V17H13V15M9,19H11V21H9V19M13,19H15V21H13V19Z"></path></svg>
                </div>
                <div class="sfg_item_content">
                    <textarea placeholder="Question" class="sfg_input sfg_name_input" contenteditable="true"></textarea>
                    <textarea placeholder="Answer" class="sfg_input sfg_text_input"contenteditable></textarea>
                </div>
                <button class="sfg_delete_item" type="button">
                    <svg viewBox="0 0 24 24"><path d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z"></path></svg>
                </button>
             </li>
        `;
        jQuery('.sfg_faq_list').append( newItem );

        jQuery('.sfg_delete_item').each( function () {
            jQuery(this).on( 'click', function () {
                jQuery(this).parent().remove();
                changePlaceholders();
                schemaGenerate();
            } );
        });
    }

    function changePlaceholders() {
        // for name areas
        jQuery('.sfg_name_input').each( function(index, element){
            jQuery(element).attr( 'placeholder', 'Question #' + ( index + 1 ) ) ;
        });
        // for text areas
        jQuery('.sfg_text_input').each( function(index, element){
            jQuery(element).attr( 'placeholder', 'Answer #' + ( index + 1 ) ) ;
        });
    }

    jQuery('.sfg_faq_list').sortable();
    autosize(document.querySelectorAll('.sfg_item_content textarea'));

    // init generate
        changePlaceholders();
        schemaGenerate();

    // on-input regenerate
    jQuery(document).on( 'input', '.sfg_input', function () {
        schemaGenerate();
    });

    // add-item regenerate
    jQuery('#sfg_add_new').on( 'click', function() {
        addItem();
        autosize(document.querySelectorAll('.sfg_item_content textarea'));
        changePlaceholders();
        schemaGenerate();
    });

    // remove-item regenerate
    jQuery('.sfg_delete_item').each( function () {
        jQuery(this).on( 'click', function () {
            jQuery(this).parent().remove();
            changePlaceholders();
            schemaGenerate();
        } );
    });

    // sort-items regenerate
    jQuery( '.sfg_faq_list' ).on( 'sortbeforestop', function() {
        changePlaceholders();
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



