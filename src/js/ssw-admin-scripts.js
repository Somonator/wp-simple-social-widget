/**
* The plugin "Simple Soc Wdiget" scripts for admin panel.
*/

jQuery(function($) {
    let media_frame,
        sortable_options = {
            connectWith: '.form',
            handle: '.move',
            placeholder: 'portlet-placeholder',
            sort: function(event, ui) {
                let height = $(ui.item).height();

                $(ui.placeholder).css('height', height);
            },
            stop: function(event, ui) {
                let wrap = $(ui.item).parents('.forms'); // take forms on active form now

                update_forms(wrap);
            }
        },
        picker_options = {
            defaultColor: '#ffffff',
            change: function() {
                $(this).parents('form').change()
            }
        };

    
    $('.ssw .forms').sortable(sortable_options);
    $(document).delegate('.ssw .add-new', 'click', add_form);
    $(document).delegate('.ssw .form .delete', 'click', delete_form);    
    $(document).delegate('.ssw .form .head', 'click', toggle_form);
    $(document).delegate('.ssw .form .edit-image', 'click', edit_image);
    $(document).delegate('.ssw .form .delete-image', 'click', delete_image);
    $(document).delegate('[id*="simple_soc_link"] input[name="savewidget"]', 'click', custom_submit)


    function update_forms(wrap) {
        wrap.find('.form').each(function(index, element) {
            index++;

            let replace_title = $(this).find('.title').text().replace(/\d+/, index); // find any int

            $(this).find('.title').text(replace_title);
            
            $(this).find('*[name]').each(function() {
                let replace_name = $(this).attr('name').replace(/\]\[(\d+)\]\[/g, '][' + index + ']['); // find ][int][
                
                $(this).attr('name', replace_name);
            })
        });
        
        wrap.find('.form').length > 1 ? wrap.find('.manage').show() : wrap.find('.manage').hide()
    }

    function reset_colorpicker(wrap) {
        if (wrap.find('.wp-picker-container').length > 0) { // Reset and init again colorpicker 
            wrap.find('.wp-picker-container').each(function() {
                let initial_input = $(this).find('[data-colorpicker]').clone();

                initial_input.removeClass('wp-color-picker').removeAttr('value');
                console.log(initial_input);
                $(this).replaceWith(initial_input);
            });
        }
    }
    
    function add_form(event) {
        event.preventDefault();

        let parent = $(this).prev(),
            new_form = parent.find('.form').eq(-1).clone(),
            default_img = new_form.find('img').data('default');

        new_form.find('input:not([type="button"])').val('');
        new_form.find('img').attr('src', default_img);

        parent.append(new_form);

        update_forms(parent);
        reset_colorpicker(new_form);

        parent.find('.form').find('.content').slideUp(300);
        new_form.find('.content').slideUp();
        new_form.find('.head').click();

        $(this).parents('form').change(); 
    }

    function delete_form(event) {
        event.preventDefault();

        let parent = $(this).parents('.forms');

        $(this).parents('form').change();                
        $(this).parents('.form').remove();

        update_forms(parent);
    }

    function toggle_form(event) {
        event.preventDefault();

        $(this).next().slideToggle(300);
        
        $(this).next().find('input[data-colorpicker*="background"]').wpColorPicker($.extend(picker_options, {
            defaultColor: '#000000'
        }));
        
        $(this).next().find('input[data-colorpicker*="color"]').wpColorPicker(picker_options);
    }

    function edit_image(event) {
        event.preventDefault();

        let field = $(this).parent(),
            input = field.find('input')
            img = field.find('img');        

        if (media_frame) {
            media_frame.open();
            return;
        }

        media_frame = wp.media({
            library : {
                type : 'image'
            },
            multiple: false
        });

        media_frame.on('open', function(){
            if (input.val()) {
                media_frame.state().get('selection').add(wp.media.attachment(input.val()));                
            }
        });

        media_frame.on('select', function() {
            let attachment = media_frame.state().get('selection').first().toJSON();

            img.attr('src', attachment.url);
            input.val(attachment.url);
        });

        media_frame.open();

        $(this).parents('form').change(); 
    }

    function delete_image(event) {
        event.preventDefault();

        let field = $(this).parent(),
            input = field.find('input')
            img = field.find('img'),
            default_img = img.data('default');

        input.val('');
        img.attr('src', default_img);

        $(this).parents('form').change(); 
    }

    function custom_submit() {
        let form = $(this).parents('form');

        form.find('*[required]').each(function() { // check requied field
            if (!$(this)[0].checkValidity()) {
                $(this).parents('.form').find('.content').slideDown(300, () => {
                    $(this)[0].reportValidity();
                });
            }
        });
        
        setTimeout(function() { // re init js form after save
            $('.ssw .forms').sortable(sortable_options);

            form.find('label[for*="image"]').each(function() {
                if (!$(this).parent().find('input').attr('value') == '') {
                    let src = $(this).parent().find('input').attr('value');

                    $(this).parent().find('img').attr('src', src);
                }
            });

            form.find('input[data-colorpicker*="background"]').wpColorPicker($.extend(picker_options, {
                defaultColor: '#000000'
            }));
            
            form.find('input[data-colorpicker*="color"]').wpColorPicker(picker_options);
        }, 500)
    }
});