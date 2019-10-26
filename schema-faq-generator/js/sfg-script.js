jQuery( document ).ready( function(){

    function createSchema() {
        // prepare start end etc..
        let items = [];
        let start =
            `<script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [`;
        let end =`\t\t]\n}\n</script>`;
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
            let coma = i == items.length - 1 ? '\n' : ',';
            console.log(i);
            result += `
                            {
                                "@type": "Question",
                                "name": "` + items[i].name + `",
                                "acceptedAnswer": {
                                    "@type": "Answer",
                                    "text": "` + items[i].text + `"
                                }
                            }` + coma;
        }
        result = start + result + end;

        sfg_output.innerHTML = result;
        console.log( items.valueOf() );

    }

    function addNewItem() {
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
        console.log('element created');
    }
    jQuery('#sfg_add_new').on( 'click', function() {
        addNewItem();
        createSchema();
    });



    // init generate
        createSchema();
    // on input generate
    jQuery(document).on( 'input', '.sfg_input', function () {
        createSchema();
    });


});


// sfg_input.oninput = function() {
//     sfg_output.innerHTML = sfg_input.value;
//     let text = sfg_input.value
//     console.log(text);
// };


