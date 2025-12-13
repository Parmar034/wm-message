
$(document).on('click', '#select_layout', function() {
    $('#layout_modal').modal('show');
});
$(document).on('click', '.section_layout', function() {
    let layout = $(this).data('type');
    if (layout) {
        let order = $('.section-list').children().length + 1;
        createNewSection(layout, order);
    }
});

function createNewSection(layout, order) {
    var page_id = $('#page_id').val();
    $.ajax({
        type: "POST",
        url: admin_url +"page-builder-sections-store",
        data: {
            layout: layout,
            page_id: page_id,
            order: order
        },
        success: function(response) {
            toastr.success('New Section Created Successfully');
            makeLayout(layout, response.data.id, response.data.order);
        },
        error: function(xhr, status, error) {
            $('#layout_modal').modal('hide');
            toastr.success('Something Went Wrong!');
        }
    });
}

function makeLayout(layout, section_id, layout_ids) {
    let layoutStr = layout.toString();
    let colums = layoutStr.split(',');
    let columns_markup = '';
    for (let i = 0; i < colums.length; i++) {
        columns_markup +=
            `<div class="col-${colums[i]} p-0 section-column" style="border:1px solid" data-order="${i + 1}" data-section-id="${section_id}"></div>`;
    }
    let layout_markup = `
            <div class ="row p-10 full_section" id="section_${section_id}" data-id="${section_id}">
                <a  class="black my-auto drag-layout">
                    <i class="feather icon-command"></i>
                </a>
                    <div class="row my-2 mx-0 col-11 bg-white layout-height">` + columns_markup + `</div>
                    <a class="black my-auto edit-section set-sec-prop" data-id="${section_id}">
                        <i class="feather icon-settings"></i>
                    </a>
                    <a class="black my-auto ml-2 remove-section del-section" data-id="${section_id}">
                        <i class="feather icon-trash-2"></i>
                    </a>
                </div>
            `;

    $('.section-list').next().remove();
    $('.section-list').append(layout_markup);
    $('#layout_modal').modal('hide');
    $('.section-column').droppable({
    accept: ".widget-single",
    drop: function(event, ui) {
        let widget = $(ui.draggable).clone();
        const data = {
            widget_id: $(widget).data('widget-id'),
            order: $(this).data('order'),
            section_id: $(this).data('section-id')
        };
        saveWidget(data, widget, this);
    },
});
    // droppableAndSortableInit();
};

$(document).on('input', '#color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#background-color-id').val(colorValue);
});
$(document).on('click', '.set-sec-prop', function() {
    var section_id = $(this).data('id');
    var page_id = $('#page_id').val();
    $('#prop-section_id').val(section_id);
    $('#prop-page_id').val(page_id);
    $.ajax({
        type: 'POST',
        url: admin_url +"page-builder-sections-edit",
        data: {section_id:section_id},
        success: function(response) {
            var bladeData = document.getElementById('blade-data');
            var assetUrl = bladeData.getAttribute('data-asset');
            var imageUrl = assetUrl + '/' + response.bg_img;         
            $('#background-color-id').val(response.data.bg_color);
            if(imageUrl && imageUrl != null) {
                $('#sec_avtar').attr('src', imageUrl);
            }
            $('#sec_bg_size').val(response.data.bg_size);
            $('#sec_bg_position').val(response.data.bg_position);
            $('#sec_bg_repeate').val(response.data.bg_repeat);
            $('#sec_class').val(response.data.class);
            $('#sec_id').val(response.data.id);
            $('.container_class').removeClass('active');
            $('.container_class').each(function() {
                var label = $(this);
                var input = label.find('input');
                var inputValue = input.val();
                if (inputValue === response.data.container) {
                    label.addClass('active');
                    input.prop('checked', true)
                }else{
                    input.prop('checked', false);
                }
            });
            $('.vertical_align').removeClass('active');
            $('.vertical_align').each(function() {
                var label = $(this);
                var input = label.find('input');
                var inputValue = input.val();
                if (inputValue === response.data.v_alignment) {
                    label.addClass('active');
                    input.prop('checked', true)
                }else{
                    input.prop('checked', false);
                }
            });
            $('#section-property-sec').show();
        },
        error: function(xhr, status, error) {
            toastr.success(response.message);
        }
    });
    $('#widget-property-sec').hide();
    
});

const assetPath = "{{ asset('assets/images/user/img-demo_1041.jpg') }}";
$('#remove_image').click(function(event) {
    event.stopPropagation();
    $('#img_id').val(null);
    $('#profile_avtar').attr('src', assetPath);
    $('#remove_image').css('display', 'none');
    $('#profile_avtar').css('opacity', '1.0');
});

$(document).on('click', '#save_sec_prop', function() {
    var formData = $('#section-property-form').serialize();
    $.ajax({
        type: 'POST',
        url: admin_url +"page-builder-sections-prop-store",
        data: formData,
        success: function(response) {
            toastr.success(response.message);
            $('#section-property-form')[0].reset();
        },
        error: function(xhr, status, error) {
            toastr.success(response.message);
        }
    });
});

$(document).on('click', '.del-section', function() {
    var section_id = $(this).data('id');
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete the Section!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: admin_url +"page-builder-sections-delete",
                data: {
                    section_id: section_id
                },
                success: function(response) {
                    toastr.error(response.message);
                    $(".full_section[data-id='" + section_id + "']").remove();
                    if ($(".full_section").length === 0) {
                        $('.no-section-sec').show();
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Something Went Wrong!');
                }
            });
        }
    });
});

function updateSectionOrder(data) {
    $.ajax({
        type: 'post',
        url: admin_url +"page-builder-sections-change-order",
        data: data,
        success: function(response) {
            toastr.success(response.message);
        },
        error: function(xhr, status, error) {
            let message = "Section Sorting Request Failed";
            if (xhr.responseJSON) {
                message = xhr.responseJSON.message;
            }
            toastr.error(message, 'ERROR!!');
        }
    });
};
$('.section-list').sortable({
    handle: '.drag-layout',
    cursor: "move",
    axis: 'y',
    update: function(e, u) {
        let data = $(this).sortable('serialize');
        updateSectionOrder(data);
    }
});
$('.widget-single').draggable({
    revert: "invalid",
    helper: "clone",
    cursor: 'pointer',
    zIndex: 10000,
    start: function(event, ui) {
        ui.helper.addClass("widget-placeholder");
    }
});
$('.section-column').droppable({
    accept: ".widget-single",
    drop: function(event, ui) {
        let widget = $(ui.draggable).clone();
        console.log(widget);
        const data = {
            widget_id: $(widget).data('widget-id'),
            order: $(this).data('order'),
            section_id: $(this).data('section-id')
        };
        saveWidget(data, widget, this);
    },
});

function saveWidget(data, widget, section) {
    $.ajax({
        type: "post",
        url: admin_url +"section-widget-store",
        data: {
            section_layout_id: data.order,
            widget_id: data.widget_id,
            section_id: data.section_id
        },
        success: function(response) {
            toastr.success(response.message, 'Success');
            appendWidgetToLayout(widget, section, response.data.id, response.title);
            // updateWidgetOrder(data.order);
        },
        error: function(xhr, status, error) {
            let message = "Widget Adding Request Failed";
            if (xhr.responseJSON) {
                message = xhr.responseJSON.message;
            }
            toastr.error(message, 'ERROR!!');
        }
    });
};
function appendWidgetToLayout(widget, section, id, title) {
        $(widget).removeClass('mb-2');
        $(widget).removeClass('widget-single').addClass('section-widget');
        $(widget).addClass('d-flex');
        $(widget).removeClass('ui-draggable');
        $(widget).removeClass('ui-draggable-handle');
        $(widget).attr('data-layout-widget-id', id);
        $(widget).appendTo(section);
        let actionMarkup = `
                    <div class="widget-icons">
                        <a  class="black editWidget" data-widget-name="${title}" data-id="${id}"><i class="feather icon-settings mx-1"></i></a>
                        <a  class="black removeWidget" data-id="${id}"><i class="feather icon-trash-2"></i></a>
                    </div>`;
        $(widget).append(actionMarkup);
    };


$(document).on('click', '.editWidget', function() {
    var val = $(this).data('widget-name');
    var id = $(this).data('id');
    $.ajax({
        type: 'POST',
        url: admin_url +"section-widget-prop-get",
        data: {widget_id:id},
        success: function(response) {
            console.log(response);
            var bladeData = document.getElementById('blade-data');
            var assetUrl = bladeData.getAttribute('data-asset');
            var chide = false;
            var bhide = false;
            var pbhide = false;
            var sbhide = false;
            var tbhide = false;
            var gbhide = false;
            var cbhide = false;
            if(response.data.cont_img && response.data.cont_img != null)
            {
                var imageUrl = assetUrl + '/' + response.data.cont_img;
            }else{
                var imageUrl = window.assetPath;
                var chide = true; 
            }  
            if(response.data.back_img && response.data.back_img != null) 
            {
                var imageUrl1 = assetUrl + '/' + response.data.back_img; 
            }else{
                var bhide = true;
                var imageUrl1 = window.assetPath;
            }  
            if(response.data.post_back_img && response.data.post_back_img != null) 
            {
                var imageUrl2 = assetUrl + '/' + response.data.post_back_img; 
            }else{
                var pbhide = true;
                var imageUrl2 = window.assetPath;
            }  
            if(response.data.service_back_img && response.data.service_back_img != null) 
            {
                var imageUrl3 = assetUrl + '/' + response.data.service_back_img; 
            }else{
                var sbhide = true;
                var imageUrl3 = window.assetPath;
            }
            if(response.data.testi_back_img && response.data.testi_back_img != null) 
            {
                var imageUrl4 = assetUrl + '/' + response.data.testi_back_img; 
            }else{
                var tbhide = true;
                var imageUrl4 = window.assetPath;
            }  
            if(response.data.gallery_back_img && response.data.gallery_back_img != null) 
            {
                var imageUrl5 = assetUrl + '/' + response.data.gallery_back_img; 
            }else{
                var gbhide = true;
                var imageUrl5 = window.assetPath;
            }  
            if(response.data.contact_back_img && response.data.contact_back_img != null) 
            {
                var imageUrl6 = assetUrl + '/' + response.data.contact_back_img; 
            }else{
                var cbhide = true;
                var imageUrl6 = window.assetPath;
            }
            $('#background-color-id').val(response.data.bg_color);
            if(imageUrl && imageUrl != null) {
                $('#we_img_back_profile_avtar_we_img_back_id').attr('src', imageUrl);
                if(chide === false)
                {
                   $('#remove_sec_image').show(); 
                }else{
                    $('#remove_sec_image').hide();
                }
                if(response.data.content && response.data.content != null)
                {
                $('#we_img_back_img_id_we_img_back_id').val(response.data.content.image);
                }
            }
            if(imageUrl1 && imageUrl1 != null) {
                $('#we_edi_back_profile_avtar_we_edi_back_id').attr('src', imageUrl1);
                if(bhide === false)
                {
                   $('#remove_edi_img').show(); 
                }else{
                    $('#remove_edi_img').hide();
                }
                $('#we_edi_back_img_id_we_edi_back_id').val(response.data.background.bg_img);
            }
            if(imageUrl2 && imageUrl2 != null) {
                $('#we_po_back_profile_avtar_we_po_back_id').attr('src', imageUrl2);
                if(pbhide === false)
                {
                   $('#we_po_back_remove_image_we_po_back_id').show(); 
                }else{
                    $('#we_po_back_remove_image_we_po_back_id').hide();
                }
                $('#we_po_back_img_id_we_po_back_id').val(response.data.background.post_bg_img);
            }
            if(imageUrl3 && imageUrl3 != null) {
                $('#we_ser_back_profile_avtar_we_ser_back_id').attr('src', imageUrl3);
                if(sbhide === false)
                {
                   $('#we_ser_back_remove_image_we_ser_back_id').show(); 
                }else{
                    $('#we_ser_back_remove_image_we_ser_back_id').hide();
                }
                $('#we_ser_back_img_id_we_ser_back_id').val(response.data.background.service_bg_img);
            }
            if(imageUrl4 && imageUrl4 != null) {
                $('#we_testi_back_profile_avtar_we_testi_back_id').attr('src', imageUrl4);
                if(tbhide === false)
                {
                   $('#we_testi_back_remove_image_we_testi_back_id').show(); 
                }else{
                    $('#we_testi_back_remove_image_we_testi_back_id').hide();
                }
                $('#we_testi_back_img_id_we_testi_back_id').val(response.data.background.testimonial_bg_img);
            }
            if(imageUrl5 && imageUrl5 != null) {
                $('#we_gal_back_profile_avtar_we_gal_back_id').attr('src', imageUrl5);
                if(gbhide === false)
                {
                   $('#we_gal_back_remove_image_we_gal_back_id').show(); 
                }else{
                    $('#we_gal_back_remove_image_we_gal_back_id').hide();
                }
                $('#we_gal_back_img_id_we_gal_back_id').val(response.data.background.gallery_bg_img);
            }
            if(imageUrl6 && imageUrl6 != null) {
                $('#we_con_back_profile_avtar_we_con_back_id').attr('src', imageUrl6);
                if(cbhide === false)
                {
                   $('#we_con_back_remove_image_we_con_back_id').show(); 
                }else{
                    $('#we_con_back_remove_image_we_con_back_id').hide();
                }
                $('#we_con_back_img_id_we_con_back_id').val(response.data.background.contact_bg_img);
            }
            if(response.data.content && response.data.content != null && response.data.content.custom_link && response.data.content.custom_link != null)
            {
                $('#hide_url').show();
                $('#hide_url input[type="text"]').val(response.data.content.custom_link);
            }
            if(response.data.content && response.data.content != null && response.data.content.target_blank && response.data.content.target_blank != null)
            {
                if(response.data.content.target_blank == 'on' || response.data.content.target_blank == 1)
                {
                    $('input[name="target_blank"]').prop('checked', true);
                }else{
                    $('input[name="target_blank"]').prop('checked', false);
                }
                
            }else{
                $('input[name="target_blank"]').prop('checked', false);
            }
            if(response.data.content && response.data.content != null && response.data.content.post_type && response.data.content.post_type != null &&  response.data.content.post_type == "2")
            {
                $('#post_cat_div').show();
                $('select[name="post_cat_div"]').val(response.data.content.post_type);
            }
            if(response.data.content && response.data.content != null){
            $('.image_alignment').removeClass('active');
            $('.image_alignment').each(function() {
                var label = $(this);
                var input = label.find('input');
                var inputValue = input.val();
                if (inputValue === response.data.content.i_alignment) {
                    label.addClass('active');
                    input.prop('checked', true)
                }else{
                    input.prop('checked', false);
                }
            });
            }
            if(response.data.content && response.data.content != null){
            $('.heading_tag_align').removeClass('active');
            $('.heading_tag_align').each(function() {
                var label = $(this);
                var input = label.find('input');
                var inputValue = input.val();
                if (inputValue === response.data.content.t_alignment) {
                    label.addClass('active');
                    input.prop('checked', true)
                }else{
                    input.prop('checked', false);
                }
            });
            }
            if(response.data.content && response.data.content != null){
            $('.button_alignment').removeClass('active');
            $('.button_alignment').each(function() {
                var label = $(this);
                var input = label.find('input');
                var inputValue = input.val();
                if (inputValue === response.data.content.b_alignment) {
                    label.addClass('active');
                    input.prop('checked', true)
                }else{
                    input.prop('checked', false);
                }
            });
            }   
            if(response.data.content && response.data.content != null){
            $('.editor_alignment').removeClass('active');
            $('.editor_alignment').each(function() {
                var label = $(this);
                var input = label.find('input');
                var inputValue = input.val();
                if (inputValue === response.data.content.t_alignment) {
                    label.addClass('active');
                    input.prop('checked', true)
                }else{
                    input.prop('checked', false);
                }
            });
            }
            if(response.data.content && response.data.content != null){
            $('.post_column_lable').removeClass('active');
            $('.post_column_lable').each(function() {
                var label = $(this);
                var input = label.find('input');
                var inputValue = input.val();
                if (inputValue === response.data.content.column) {
                    label.addClass('active');
                    input.prop('checked', true)
                }else{
                    input.prop('checked', false);
                }
            });
            }
            if(response.data.content && response.data.content != null){
            $('.gallery_column_lable').removeClass('active');
            $('.gallery_column_lable').each(function() {
                var label = $(this);
                var input = label.find('input');
                var inputValue = input.val();
                if (inputValue === response.data.content.gallery_column) {
                    label.addClass('active');
                    input.prop('checked', true)
                }else{
                    input.prop('checked', false);
                }
            });
            }
            if(response.data.style && response.data.style != null)
            {
                $('#range_width').val(response.data.style.img_height);
                $('input[name="img_width"]').val(response.data.style.img_height);
                $('input[name="img_max_width"]').val(response.data.style.img_max_width);
                $('input[name="img_height"]').val(response.data.style.img_width);  
                $('input[name="text_color"]').val(response.data.style.text_color);
                $('input[name="normal_text_color"]').val(response.data.style.normal_text_color);
                $('input[name="normal_bg_color"]').val(response.data.style.normal_bg_color);
                $('input[name="hover_text_color"]').val(response.data.style.hover_text_color);
                $('input[name="hover_bg_color"]').val(response.data.style.hover_bg_color);
                $('input[name="font_size_c_"]').val(response.data.style.font_size);
                $('input[name="radius"]').val(response.data.style.radius);
                $('input[name="button_padding_top_c_"]').val(response.data.style.button_padding_top);
                $('input[name="button_padding_right_c_"]').val(response.data.style.button_padding_right);
                $('input[name="button_padding_bottom_c_"]').val(response.data.style.button_padding_left);
                $('input[name="button_padding_left_c_"]').val(response.data.style.button_padding_bottom);
                $('input[name="post_t_color"]').val(response.data.style.title_color);
                $('input[name="post_th_color"]').val(response.data.style.title_hover_color);
                $('input[name="post_st_color"]').val(response.data.style.subtitle_color);
                $('input[name="post_sth_color"]').val(response.data.style.subtitle_hover_color);
                $('input[name="service_t_color"]').val(response.data.style.service_title_color);
                $('input[name="service_th_color"]').val(response.data.style.service_title_hover_color);
                $('input[name="service_st_color"]').val(response.data.style.service_title_hover_color);
                $('input[name="service_sth_color"]').val(response.data.style.service_subtitle_hover_color);
                $('input[name="testimonial_t_color"]').val(response.data.style.testimonial_title_color);
                $('input[name="testimonial_th_color"]').val(response.data.style.testimonial_title_hover_color);
                $('input[name="testimonial_st_color"]').val(response.data.style.testimonial_subtitle_color);
                $('input[name="testimonial_sth_color"]').val(response.data.style.testimonial_subtitle_hover_color);
                $('input[name="gallery_t_color"]').val(response.data.style.gallery_title_color);
                $('input[name="gallery_th_color"]').val(response.data.style.gallery_title_hover_color);
                $('input[name="gallery_st_color"]').val(response.data.style.gallery_subtitle_color);
                $('input[name="gallery_sth_color"]').val(response.data.style.gallery_subtitle_hover_color);
            }
            if(response.data.background && response.data.background != null)
            {
               $('input[name="img_bg_color"]').val(response.data.background.bg_color); 
               $('input[name="bg_color"]').val(response.data.background.bg_color);
               $('input[name="text_bg_color"]').val(response.data.background.bg_color);
               $('select[name="text_bg_size"]').val(response.data.background.bg_size);
               $('select[name="text_bg_position"]').val(response.data.background.bg_position);
               $('select[name="text_bg_repeate"]').val(response.data.background.bg_repeate);
               $('input[name="post_bg_color"]').val(response.data.background.post_bg_color);
               $('select[name="post_bg_size"]').val(response.data.background.post_bg_size);
               $('select[name="post_bg_position"]').val(response.data.background.post_bg_position);
               $('select[name="post_bg_repeate"]').val(response.data.background.post_bg_repeate);
               $('input[name="service_bg_color"]').val(response.data.background.service_bg_color);
               $('select[name="service_bg_size"]').val(response.data.background.service_bg_size);
               $('select[name="service_bg_position"]').val(response.data.background.service_bg_position);
               $('select[name="service_bg_repeate"]').val(response.data.background.service_bg_repeate);
               $('input[name="testimonial_bg_color"]').val(response.data.background.testimonial_bg_color);
               $('select[name="testimonial_bg_size"]').val(response.data.background.testimonial_bg_size);
               $('select[name="testimonial_bg_position"]').val(response.data.background.testimonial_bg_position);
               $('select[name="testimonial_bg_repeate"]').val(response.data.background.testimonial_bg_repeate);
               $('input[name="gallery_bg_color"]').val(response.data.background.gallery_bg_color);
               $('select[name="gallery_bg_size"]').val(response.data.background.gallery_bg_size);
               $('select[name="gallery_bg_position"]').val(response.data.background.gallery_bg_position);
               $('select[name="gallery_bg_repeate"]').val(response.data.background.gallery_bg_repeate);
               $('input[name="contact_bg_color"]').val(response.data.background.contact_bg_color);
               $('select[name="contact_bg_size"]').val(response.data.background.contact_bg_size);
               $('select[name="contact_bg_position"]').val(response.data.background.contact_bg_position);
               $('select[name="contact_bg_repeate"]').val(response.data.background.contact_bg_repeate);
            }
            if(response.data.content && response.data.content != null)
            {

                $('#img_link').val(response.data.content.link);
                $('input[name="img_alt_name"]').val(response.data.content.alt_name);
                $('select[name="heading_tag"]').val(response.data.content.tag);
                $('input[name="heading_text"]').val(response.data.content.text);
                $('textarea[name="text_content"]').val(response.data.content.text_content);
                // $('input[name="text_content"]').val(response.data.content.text_content);
                // $('.note-editable').html(response.data.content.text_content);
                // $('input[name="heading_text"]').val(response.data.content.text);
                $('input[name="button_text"]').val(response.data.content.button_text);
                $('input[name="button_link"]').val(response.data.content.button_link);
                $('select[name="post_type"]').val(response.data.content.post_type);
                $('select[name="post_style"]').val(response.data.content.post_style);
                $('input[name="post_count"]').val(response.data.content.count);
                $('select[name="service_style"]').val(response.data.content.service_style);
                $('input[name="service_count"]').val(response.data.content.service_count);
                $('select[name="testimonial_style"]').val(response.data.content.testimonial_style);
                $('input[name="testimonial_count"]').val(response.data.content.testimonial_count);
                $('select[name="gallery_style"]').val(response.data.content.gallery_style);
                $('input[name="gallery_count"]').val(response.data.content.gallery_count);
            }
            if(response.data.advanced && response.data.advanced != null)
            {
                $('input[name="widget_class"]').val(response.data.advanced.class);
                $('input[name="widget_id"]').val(response.data.advanced.id);  
            }    
        },
        error: function(xhr, status, error) {
            toastr.error(response.message);
        }
    });
    $('#widget_id').val(id);
    $('#main_widget_name').val(val);
    $('#section-property-sec').hide();
    $('#widget-property-sec').show();
    $('#widget-title-dynamic').html(val + " Properties");
    let values = '';
    if(val == 'Button')
    {
        $('#widget-content-tab').show();
        $('#widget-style-tab').show();
        $('#widget-background-tab').removeClass('active show');
        $('#widget-background-tab').attr('aria-selected','false');
        values  = $('#button_html').html();

    }else if(val == 'Heading Tag')
    {
        $('#widget-content-tab').show();
        $('#widget-style-tab').show();
        $('#widget-background-tab').removeClass('active show');
        $('#widget-background-tab').attr('aria-selected','false');
        values  = $('#heading_html').html();
    }else if(val == 'Image')
    {
        $('#widget-content-tab').show();
        $('#widget-style-tab').show();
        $('#widget-background-tab').removeClass('active show');
        $('#widget-background-tab').attr('aria-selected','false');
        values  = $('#image_html').html();
    }else if(val == 'Text Editor')
    {
        $('#widget-content-tab').show();
        $('#widget-style-tab').hide();
        $('#widget-background-tab').removeClass('active show');
        $('#widget-background-tab').attr('aria-selected','false');
        values  = $('#text_editor_html').html();
        // $("#text_content").summernote({height:200,
        //     toolbar: [
        //     ['codeview']
        // ]
        // });
    }else if(val == 'Posts')
    {
        $('#widget-content-tab').show();
        $('#widget-style-tab').show();
        $('#widget-background-tab').removeClass('active show');
        $('#widget-background-tab').attr('aria-selected','false');
        values  = $('#posts_html').html();
    }else if(val == 'Service Section')
    {
        $('#widget-content-tab').show();
        $('#widget-style-tab').show();
        $('#widget-background-tab').removeClass('active show');
        $('#widget-background-tab').attr('aria-selected','false');
        values  = $('#services_html').html();
    }else if(val == 'Testimonial Section')
    {
        $('#widget-content-tab').show();
        $('#widget-style-tab').show();
        $('#widget-background-tab').removeClass('active show');
        $('#widget-background-tab').attr('aria-selected','false');
        values  = $('#testimonials_html').html();
    }else if(val == 'Our Gallary')
    {
        $('#widget-content-tab').show();
        $('#widget-style-tab').show();
        $('#widget-background-tab').removeClass('active show');
        $('#widget-background-tab').attr('aria-selected','false');
        values  = $('#our_gallery_html').html();
    }else if(val == 'Contact Form')
    {
        $('#widget-content-tab').hide();
        $('#widget-style-tab').hide();
        $('#widget-background-tab').addClass('active show');
        $('#widget-background-tab').attr('aria-selected','true');
        values  = $('#contact_form_html').html();
    }
    
    $('#widget-tabContent').html(values);
});
$(document).on('click', '.nav-link', function() {
// $('.nav-link').on('click', function() {
    $('.nav-link').removeClass('active show');
    $('.nav-link').attr('aria-selected', 'false');
    $(this).addClass('active show');
    $(this).attr('aria-selected', 'true');
});
// $(function() {
//   $("#text_content").summernote({height:200});
//   $("button#btnToggleStyle").on("click", function(e) {
//     e.preventDefault();
//     var styleEle = $("style#fixed");
//     if (styleEle.length == 0)
//       $("<style id=\"fixed\">.note-editor .dropdown-toggle::after { all: unset; } .note-editor .note-dropdown-menu { box-sizing: content-box; } .note-editor .note-modal-footer { box-sizing: content-box; }</style>")
//       .prependTo("body");
//     else
//       styleEle.remove();
//   })
// })
$(document).ready(function() {
    // $("#text_content").summernote({height:200,
    //     // toolbar: [
    //     //     ['codeview']
    //     // ]
    // });
    });

$(document).on('change', '#img_link', function() {
    var val = $(this).val();
    if(val == 'custom_url')
    {
        $('#hide_url').show();
    }else{
        $('#hide_url').hide();
    }
        
});
$(document).on('change', '#posts_type', function() {
    var val = $(this).val();
    if(val == '2')
    {
        $('#post_cat_div').show();
    }else{
        $('#post_cat_div').hide();
    }
        
});
$(document).ready(function() {
    var $rangeInput = $("#range_width");
    var $textInput = $("#height_c_");
    $rangeInput.on("input", function() {
        $textInput.val($rangeInput.val());
    });
});
$(document).on('click', '#save_widget_prop', function() {

    var formData = $('#widget-property-form').serializeArray();
    // var textContent = $('#text_content').summernote('code');
    // var textContent = $('.note-editable').html();
    // formData.push({ name: 'text_content', value: textContent });
    $.ajax({
        type: 'POST',
        url: admin_url +"section-widget-prop-store",
        data: formData,
        success: function(response) {
            toastr.success(response.message);
            $('#widget-property-form')[0].reset();
        },
        error: function(xhr, status, error) {
            toastr.error('Something Went Wrong!');
        }
    });
});
$(document).on('click', '.removeWidget', function() {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete the Widget!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: admin_url +"section-widget-delete",
                data: {
                    id: id
                },
                success: function(response) {
                    toastr.error(response.message);
                    $(".section-widget[data-layout-widget-id='" + id + "']").remove();
                },
                error: function(xhr, status, error) {
                    toastr.error('Something Went Wrong!');
                }
            });
        }
    });
});
$(document).on('input', '#normal-text-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#normal-text-color-id').val(colorValue);
});
$(document).on('input', '#normal-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#normal-background-color-id').val(colorValue);
});
$(document).on('input', '#hover-text-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#hover-text-color-id').val(colorValue);
});
$(document).on('input', '#hover-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#hover-background-color-id').val(colorValue);
});
$(document).on('input', '#back-text-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#back-text-color-id').val(colorValue);
});

// post
$(document).on('input', '#post-title-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#post-title-color-id').val(colorValue);
});
$(document).on('input', '#post-titlehover-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#post-titlehover-color-id').val(colorValue);
});
$(document).on('input', '#post-subtitle-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#post-subtitle-color-id').val(colorValue);
});
$(document).on('input', '#post-subtitlehover-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#post-subtitlehover-color-id').val(colorValue);
});
$(document).on('input', '#post-background-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#post-background-color-id').val(colorValue);
});
// services
$(document).on('input', '#service-title-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#service-title-color-id').val(colorValue);
});
$(document).on('input', '#service-titlehover-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#service-titlehover-color-id').val(colorValue);
});
$(document).on('input', '#service-subtitle-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#service-subtitle-color-id').val(colorValue);
});
$(document).on('input', '#service-subtitlehover-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#service-subtitlehover-color-id').val(colorValue);
});
$(document).on('input', '#service-background-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#service-background-color-id').val(colorValue);
});
// testimonials
$(document).on('input', '#testimonial-title-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#testimonial-title-color-id').val(colorValue);
});
$(document).on('input', '#testimonial-titlehover-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#testimonial-titlehover-color-id').val(colorValue);
});
$(document).on('input', '#testimonial-subtitle-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#testimonial-subtitle-color-id').val(colorValue);
});
$(document).on('input', '#testimonial-subtitlehover-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#testimonial-subtitlehover-color-id').val(colorValue);
});
$(document).on('input', '#testimonial-background-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#testimonial-background-color-id').val(colorValue);
});

// gallery
$(document).on('input', '#gallery-title-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#gallery-title-color-id').val(colorValue);
});
$(document).on('input', '#gallery-titlehover-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#gallery-titlehover-color-id').val(colorValue);
});
$(document).on('input', '#gallery-subtitle-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#gallery-subtitle-color-id').val(colorValue);
});
$(document).on('input', '#gallery-subtitlehover-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#gallery-subtitlehover-color-id').val(colorValue);
});
$(document).on('input', '#gallery-background-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#gallery-background-color-id').val(colorValue);
});
// contact
$(document).on('input', '#contact-background-color-picker-id', function(e) {
    var colorValue = $(this).val();
    $('#contact-background-color-id').val(colorValue);
});