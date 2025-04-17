jQuery(document).ready(function($){
    // Para el campo de Banner Image
    $('#banner-image_btn').on('click', function(e){
        e.preventDefault();
        var input = $('#banner-image');
        var frame = wp.media({
            title: 'Seleccionar imagen de Banner',
            button: { text: 'Usar imagen' },
            multiple: false
        });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            input.val(attachment.url);
        });
        frame.open();
    });
    
    // Para el campo de Side Image
    $('#side-image_btn').on('click', function(e){
        e.preventDefault();
        var input = $('#side-image');
        var frame = wp.media({
            title: 'Seleccionar imagen Lateral',
            button: { text: 'Usar imagen' },
            multiple: false
        });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            input.val(attachment.url);
        });
        frame.open();
    });
});
