jQuery(document).ready(function($){
    $('#proyecto_galeria_btn').click(function(e){
        e.preventDefault();
        var imageInput = $('#proyecto_galeria');
        var custom_uploader = wp.media({
            title: 'Selecciona imágenes',
            button: { text: 'Añadir imágenes' },
            multiple: true
        }).on('select', function(){
            var selection = custom_uploader.state().get('selection');
            var ids = [];
            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                ids.push(attachment.id);
            });
            imageInput.val(ids.join(','));
        }).open();
    });
});
