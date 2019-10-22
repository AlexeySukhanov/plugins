jQuery(document).ready(function(){
    // console.log('script connected');
    // alert('lol');

let items = [];
let start =
`<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [`;
let end =
`   ]
}`;
let result = '';
    jQuery(document).on('input', '.sfg_input', function () {

        jQuery('.sfg_name_input').each(function(index, element){
            items[index] = { name: jQuery(element).val() } ;
        });

        jQuery('.sfg_text_input').each(function(index, element){
            items[index].text =  jQuery(element).val() ;
        });

        let result = '';

        for ( let i = 0; i < items.length; i++ ) {
            let coma = i == items.length - 1 ? '' : ',';
            console.log(i);
            result += `
                            {
                                "@type": "Question",
                                "name": "` + items[i].name + `",
                                "acceptedAnswer": {
                                    "@type": "Answer",
                                    "text": "` + items[i].text + `"
                                }
                            }
                            ` + coma;
        }



        sfg_output.innerHTML = start + result + end;

        console.log( items.valueOf() );
    });


});

// sfg_input.oninput = function() {
//     sfg_output.innerHTML = sfg_input.value;
//     let text = sfg_input.value
//     console.log(text);
// };


