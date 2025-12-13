    $('textarea#p_description').summernote({
        placeholder: 'Product Description',
        tabsize: 2,
        height: 150,
        toolbar: [
                ['font', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol']],
              ],
      });


$(document).ready(function () {
        $("#inputTag").tagsinput('items');

        $('#product_add_form').parsley();

         var $sections = $('.form-step');

         function navigateTo(index) {
            $sections
              .removeClass('current')
              .eq(index)
                .addClass('current');
            // Show only the navigation buttons that make sense for the current section:
            $('.step-form-button-sec .previous-button').toggle(index > 0);
            var atTheEnd = index >= $sections.length - 1;
            $('.step-form-button-sec .next-step').toggle(!atTheEnd);
            $('.step-form-button-sec [type=submit]').toggle(atTheEnd);
            $('html, body').scrollTop(0);
          }
          function curIndex() {
            // Return the current index by looking at which section has the class 'current'
            return $sections.index($sections.filter('.current'));
          }

          $('.step-form-button-sec .previous-button').click(function() {
            navigateTo(curIndex() - 1);
          });
          $('.step-form-button-sec .next-step').click(function() {
            $('#product_add_form').parsley().whenValidate({
              group: 'block-' + curIndex()
            }).done(function() {
              navigateTo(curIndex() + 1);
            });
          });

          $sections.each(function(index, section) {
            $(section).find(':input').attr('data-parsley-group', 'block-' + index);
          });
          navigateTo(0);
        

        // $('.next-step').click(function () {
        //     var currentForm = $(this).closest('.form-step').find('#product_add_form');
        //     currentForm.parsley().whenValidate().done(function () {
        //         currentStep++;
        //         currentForm.hide();
        //         $('#step' + currentStep).show();
        //     });
        // });
  
        // $('.previous-button').click(function () {
        //     currentStep--;
        //     $(this).closest('.form-step').hide();
        //     $('#step' + currentStep).show();
        // });
  
        $(document).on("change", "#all_cats", function() {
            var cat = this.value;
            $("#all_families").html('');
            $.ajax({
                url: admin_url +"all-data",
                type: "POST",
                data: {
                    cat: cat,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: 'json',
                success: function(result) {
                    $('#all_families').html('<option value="">Select Family</option>');
                    $.each(result.families, function(key, value) {
                        $("#all_families").append('<option value="' + value.id + '">' + value.family + '</option>');
                    });
                    $('#details_sec').html('');
                    // $('#details_sec').html(result.html);
                    if(result.cat == 9)
                    {
                        $('#metal_sec_div').hide();
                        $('#gemstone_div').show();
                        // $('#p_metal_purity').prop('required',false);
                        // $('#p_metal_weigth').prop('required',false);
                    }else{
                        $('#metal_sec_div').show();
                        $('#gemstone_div').hide();
                        // $('#p_metal_purity').prop('required',true);
                        // $('#p_metal_weigth').prop('required',true);
                    }
                    if(result.html == '' || result.status == 'all')
                    {
                        $('.diamond-deatils-add-more-btn').hide();
                    }else{
                        $('.diamond-deatils-add-more-btn').show();
                    }
                }
            }); 
        });
        $(document).on("click", "#remove_certificate", function() {
            $('#old_certi_file').val('');
            $('#certificatePreviewContainer').html('');
        });
        $(".delivery-field-add-more-btn").click(function() {
            var counter = $('#del_counter').val();
            $.ajax({
            url: admin_url +"get-deliver-data",
            type: "POST",
            data: {
                counter: parseInt(counter) + 1,
              _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function(result) {

                if(result.status == 1)
                {
                    $('#del_counter').val(result.counter);
                    $("#delivery_all_div").append(result.html);
                }
                $('.select2').select2({
                    placeholder: "Select"
                });
            }
          });
            // var field = '<div class="delivery-field-sec mt-4"><div class="row delivery-field-sec-row"><a><span class="remove_deliver_sec"><i class="fa fa-times-circle"></i></span></a><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Country <span data-bs-toggle="tooltip" data-bs-placement="right" title="Dummy information text"><i class="fa fa-info-circle" aria-hidden="true"></i></span> </label><select name="dCountry" class="form-control"><option selected="" disabled>Select Country</option><option value="Option 1"> Option 1</option><option value="Option 2">Option 2</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">State (Multiple) <span data-bs-toggle="tooltip" data-bs-placement="right" title="Dummy information text"><i class="fa fa-info-circle" aria-hidden="true"></i></span> </label><select name="dState" class="form-control"><option selected="" disabled>Select State</option><option value="Option 1"> Option 1</option><option value="Option 2">Option 2</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Post Code / Zip Code (Multiple) <span data-bs-toggle="tooltip" data-bs-placement="right" title="Dummy information text"><i class="fa fa-info-circle" aria-hidden="true"></i></span> </label><select name="dPost Code" class="form-control"><option selected="" disabled>Enter Duration / Days</option><option value="Option 1"> Option 1</option><option value="Option 2">Option 2</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Duration / Days <span data-bs-toggle="tooltip" data-bs-placement="right" title="Dummy information text"><i class="fa fa-info-circle" aria-hidden="true"></i></span> </label><select name="dDuration" class="form-control"><option selected="" disabled>Enter Duration / Days</option><option value="Option 1"> Option 1</option><option value="Option 2">Option 2</option></select></div></div></div></div>';
            // $("#delivery_all_div").append(field);
        });
        $(document).on("click", ".diamond-deatils-add-more-btn", function() {
            var field = '<div class="diamond-deatils-sec mt-4"><div class="each-diamond-details row"><a><span class="remove_details"><i class="fa fa-times-circle"></i></span></a><div class="col-sm-12 col-xs-12 col-lg-12 col-md-12 col-xl-12 col-xxl-12 sub-heading"><h3>Diamonds Details</h3></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Type <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select type of diamond"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><select class="form-control" name="attr_type[]"><option disabled="disabled" selected="selected">Select</option><option value="Natural">Natural</option><option value="Lab-Grown">Lab-Grown</option><option value="Lab-Grown">Cultured</option><option value="Lab-Grown">Saltwater</option><option value="Lab-Grown">Imitation</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Fancy Colour <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select fancy colour for diamond"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><select class="form-control" name="attr_fancy_color[]"><option disabled="disabled" selected="selected">Select</option><option value="White">White</option><option value="Yellow">Yellow</option><option value="Pink">Pink</option><option value="Purple">Purple</option><option value="Blue">Blue</option><option value="Green">Green</option><option value="Orange">Orange</option><option value="Brown">Brown</option><option value="Black">Black</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Colour <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select colour for diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_color[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="D">D</option><option value="E">E</option><option value="F">F</option><option value="G">G</option><option value="H">H</option><option value="I">I</option><option value="J">J</option><option value="K">K</option><option value="L">L</option><option value="M">M</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 caret_sec"><div class="form-sec"><label for="">Carat <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter carat for diamond"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><input class="form-control attr_carat" name="attr_diamond_caret[]" step="0.0001" placeholder="Enter Carat" type="number"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Gemstone <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select gemstone for diamond"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><select class="form-control" name="attr_gemstone[]"><option disabled="disabled" selected="selected">Select</option><option value="Diamond">Diamond</option><option value="Sapphire Yellow">Sapphire Yellow</option><option value="Sapphire Blue">Sapphire Blue</option><option value="Sapphire Pink">Sapphire Pink</option><option value="Ruby">Ruby</option><option value="Emerald">Emerald</option><option value="Moissanite">Moissanite</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Shape <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select shape for diamond"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><select class="form-control" name="attr_shape[]"><option disabled="disabled" selected="selected">Select</option><option value="Round">Round</option><option value="Princess">Princess</option><option value="Emerald">Emerald</option><option value="Sq. Emerald">Sq. Emerald</option><option value="Asscher">Asscher</option><option value="Cushion">Cushion</option><option value="Oval">Oval</option><option value="Radiant">Radiant</option><option value="Pear">Pear</option><option value="Marquise">Marquise</option><option value="Heart">Heart</option><option value="Triangle">Triangle</option><option value="Trilliant">Trilliant</option><option value="Baguette">Baguette</option><option value="Trapezoid">Trapezoid</option><option value="Kite">Kite</option><option value="Rose Cut">Rose Cut</option><option value="Briolette">Briolette</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Clarity <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select clarity for diamond"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><select class="form-control" name="attr_clarity[]"><option disabled="disabled" selected="selected">Select</option><option value="FL">FL</option><option value="IF">IF</option><option value="VVS1">VVS1</option><option value="VVS2">VVS2</option><option value="VS1">VS1</option><option value="VS2">VS2</option><option value="SI1">SI1</option><option value="SI2">SI2</option><option value="SI3">SI3</option><option value="I1">I1</option><option value="I2">I2</option><option value="I3">I3</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Cut <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select cut for diamond"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><select class="form-control" name="attr_cut[]"><option disabled="disabled" selected="selected">Select</option><option value="Ideal">Ideal</option><option value="Excellent">Excellent</option><option value="Very Good">Very Good</option><option value="Good">Good</option><option value="Fair">Fair</option><option value="Poor">Poor</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Setting <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select setting for diamond"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><select class="form-control" name="attr_setting[]"><option disabled="disabled" selected="selected">Select</option><option value="Prong">Prong</option><option value="Bezel">Bezel</option><option value="Channel">Channel</option><option value="Pave">Pave</option><option value="Bar">Bar</option><option value="Cluster">Cluster</option><option value="Halo">Halo</option><option value="Tension">Tension</option><option value="Invisible">Invisible</option><option value="Bead">Bead</option><option value="Flush">Flush</option><option value="Cup">Cup</option><option value="Wire">Wire</option><option value="Button">Button</option><option value="Cage">Cage</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Total Diamond Count <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter total diamond count"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><input class="form-control attr_total_dimond" name="attr_total_diamond_count[]" placeholder="Enter Total Diamond Count"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_wight_sec"><div class="form-sec"><label for="">Total Diamond Weight <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter total diamond weight"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><input class="form-control total_weight" name="attr_total_diamond_wight[]" placeholder="Enter Total Diamond Weight "></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec" caret_sec><label for="">Diamond Price Per Carat <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter diamond price per carat"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><input class="form-control price_caret" name="attr_diamond_per_carat[]" placeholder="Enter Diamond Price Per Carat"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_final_sec"><div class="form-sec"><label for="">Final Diamond Price <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter final diamond price"><i aria-hidden="true" class="fa fa-info-circle"></i></span></label><input class="form-control final_total" name="attr_final_diamond_price[]" placeholder="Enter Final Diamond Price"></div></div></div></div>';
            $("#details_sec").append(field);
        });
        $(document).on("click", ".remove_details", function() {
            $(this).closest('.diamond-deatils-sec').remove();
            calculate_tax_values();
        });
        $(document).on("click", ".remove_deliver_sec", function() {
            $(this).closest('.delivery-field-sec').remove();
        });
        $(document).on("click", ".all_dimond_add", function() {
            var html = '<div class="diamond-deatils-sec mt-4"><div class="each-diamond-details row"><a><span class="remove_details"><i class="fa fa-times-circle"></i></span></a><div class="col-sm-12 col-xs-12 col-lg-12 col-md-12 col-xl-12 col-xxl-12 sub-heading"><h3>Diamonds Details</h3></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Type <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select type of diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select class="form-control" name="attr_type[]"><option disabled="disabled" selected="selected">Select</option><option value="Natural">Natural</option><option value="Lab-Grown">Lab-Grown</option><option value="Lab-Grown">Cultured</option><option value="Lab-Grown">Saltwater</option><option value="Lab-Grown">Imitation</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Fancy Colour <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select fancy colour for diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select class="form-control" name="attr_fancy_color[]"><option disabled="disabled" selected="selected">Select</option><option value="White">White</option><option value="Yellow">Yellow</option><option value="Pink">Pink</option><option value="Purple">Purple</option><option value="Blue">Blue</option><option value="Green">Green</option><option value="Orange">Orange</option><option value="Brown">Brown</option><option value="Black">Black</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Colour <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select colour for diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_color[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="D">D</option><option value="E">E</option><option value="F">F</option><option value="G">G</option><option value="H">H</option><option value="I">I</option><option value="J">J</option><option value="K">K</option><option value="L">L</option><option value="M">M</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 caret_sec"><div class="form-sec"><label for="">Total Carat <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter carat for diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input class="form-control attr_carat" name="attr_diamond_caret[]" step="0.0001" placeholder="Enter Carat" type="number"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Gemstone <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select gemstone for diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select class="form-control" name="attr_gemstone[]"><option disabled="disabled" selected="selected">Select</option><option value="Diamond">Diamond</option><option value="Sapphire Yellow">Sapphire Yellow</option><option value="Sapphire Blue">Sapphire Blue</option><option value="Sapphire Pink">Sapphire Pink</option><option value="Ruby">Ruby</option><option value="Emerald">Emerald</option><option value="Moissanite">Moissanite</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Shape <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select shape for diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select class="form-control" name="attr_shape[]"><option disabled="disabled" selected="selected">Select</option><option value="Round">Round</option><option value="Princess">Princess</option><option value="Emerald">Emerald</option><option value="Sq. Emerald">Sq. Emerald</option><option value="Asscher">Asscher</option><option value="Cushion">Cushion</option><option value="Oval">Oval</option><option value="Radiant">Radiant</option><option value="Pear">Pear</option><option value="Marquise">Marquise</option><option value="Heart">Heart</option><option value="Triangle">Triangle</option><option value="Trilliant">Trilliant</option><option value="Baguette">Baguette</option><option value="Trapezoid">Trapezoid</option><option value="Kite">Kite</option><option value="Rose Cut">Rose Cut</option><option value="Briolette">Briolette</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Clarity <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select clarity for diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select class="form-control" name="attr_clarity[]"><option disabled="disabled" selected="selected">Select</option><option value="FL">FL</option><option value="IF">IF</option><option value="VVS1">VVS1</option><option value="VVS2">VVS2</option><option value="VS1">VS1</option><option value="VS2">VS2</option><option value="SI1">SI1</option><option value="SI2">SI2</option><option value="SI3">SI3</option><option value="I1">I1</option><option value="I2">I2</option><option value="I3">I3</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Cut <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select cut for diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select class="form-control" name="attr_cut[]"><option disabled="disabled" selected="selected">Select</option><option value="Ideal">Ideal</option><option value="Excellent">Excellent</option><option value="Very Good">Very Good</option><option value="Good">Good</option><option value="Fair">Fair</option><option value="Poor">Poor</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Setting <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please select setting for diamond"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select class="form-control" name="attr_setting[]"><option disabled="disabled" selected="selected">Select</option><option value="Prong">Prong</option><option value="Bezel">Bezel</option><option value="Channel">Channel</option><option value="Pave">Pave</option><option value="Bar">Bar</option><option value="Cluster">Cluster</option><option value="Halo">Halo</option><option value="Tension">Tension</option><option value="Invisible">Invisible</option><option value="Bead">Bead</option><option value="Flush">Flush</option><option value="Cup">Cup</option><option value="Wire">Wire</option><option value="Button">Button</option><option value="Cage">Cage</option></select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec"><label for="">Total Diamond Count <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter total diamond count"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input class="form-control attr_total_dimond" name="attr_total_diamond_count[]" placeholder="Enter Total Diamond Count"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_wight_sec"><div class="form-sec"><label for="">Total Diamond Weight <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter total diamond weight"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input class="form-control total_weight" name="attr_total_diamond_wight[]" placeholder="Enter Total Diamond Weight"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec caret_sec"><label for="">Diamond Price Per Carat <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter diamond price per carat"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input class="form-control price_caret" name="attr_diamond_per_carat[]" placeholder="Enter Diamond Price Per Carat"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_final_sec"><div class="form-sec diamonds_total_sec"><label for="">Final Diamond Price <span data-bs-placement="right" data-bs-toggle="tooltip" title="Please enter final diamond price"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input class="form-control final_total" name="attr_final_diamond_price[]" placeholder="Enter Final Diamond Price"></div></div></div></div>';
            $('#all_diamond_div').append(html);
        });

        $(document).on("click", ".all_gemstone_add", function() {
            var gem_html = '<div class="diamond-deatils-sec"><div class="row each-diamond-details"><a><span class="remove_details"><i class="fa fa-times-circle"></i></span></a><div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 sub-heading"><h3>Gemstones Details</h3></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Type <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select type of gemstone"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_gemstone_type[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Natural">Natural</option><option value="Lab-Grown">Lab-Grown</option><option value="Lab-Grown">Cultured</option><option value="Lab-Grown">Saltwater</option><option value="Lab-Grown">Imitation</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Colour <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select color for gemstone"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_gemstone_color[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Banded">Banded</option><option value="Bi-color">Bi-color</option><option value="Black">Black</option><option value="Blue">Blue</option><option value="Brown">Brown</option><option value="Color-Change">Color-Change</option><option value="Colorless">Colorless</option><option value="Cream">Cream</option><option value="Golden">Golden</option><option value="Gray">Gray</option><option value="Green">Green</option><option value="London Blue">London Blue</option><option value="Metallic">Metallic</option><option value="Multicolored">Multicolored</option><option value="Orange">Orange</option><option value="Paraiba">Paraiba</option><option value="Pink">Pink</option><option value="Purple">Purple</option><option value="Red">Red</option><option value="Royal Blue">Royal Blue</option><option value="Silver">Silver</option><option value="Sky">Sky</option><option value="Sky Blue">Sky Blue</option><option value="Smoky">Smoky</option><option value="Swiss">Swiss</option><option value="Swiss Blue">Swiss Blue</option><option value="Violet">Violet</option><option value="White">White</option><option value="Yellow">Yellow</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 gemstone_caret_sec"><div class="form-sec"><label for="">Total Carat <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter carat for gemstone"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="number" step="0.0001" class="form-control attr_gemstone_carat" name="attr_gemstone_caret[]" id="" placeholder="Enter Carat"></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Gemstone <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select gemstone"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_gemstone_gem[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Diamond">Diamond</option><option value="Sapphire Yellow">Sapphire Yellow</option><option value="Sapphire Blue">Sapphire Blue</option><option value="Sapphire Pink">Sapphire Pink</option><option value="Ruby">Ruby</option><option value="Emerald">Emerald</option><option value="Moissanite">Moissanite</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Shape <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select shape for gemstone"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_gemstone_shape[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Round">Round</option><option value="Princess">Princess</option><option value="Emerald">Emerald</option><option value="Sq. Emerald">Sq. Emerald</option><option value="Asscher">Asscher</option><option value="Cushion">Cushion</option><option value="Oval">Oval</option><option value="Radiant">Radiant</option><option value="Pear">Pear</option><option value="Marquise">Marquise</option><option value="Heart">Heart</option><option value="Triangle">Triangle</option><option value="Trilliant">Trilliant</option><option value="Baguette">Baguette</option><option value="Trapezoid">Trapezoid</option><option value="Kite">Kite</option><option value="Rose Cut">Rose Cut</option><option value="Briolette">Briolette</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Clarity <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select clarity for gemstone"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_gemstone_clarity[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Translucent">Translucent</option><option value="Opaque">Opaque</option><option value="FL">FL</option><option value="IF">IF</option><option value="VVS1">VVS1</option><option value="VVS2">VVS2</option><option value="VS1">VS1</option><option value="VS2">VS2</option><option value="SI1">SI1</option><option value="SI2">SI2</option><option value="SI3">SI3</option><option value="I1">I1</option><option value="I2">I2</option><option value="I3">I3</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Cut <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select cut for gemstone"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_gemstone_cut[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Brilliant">Brilliant</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Setting <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select setting for gemstone"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_gemstone_setting[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Prong">Prong</option><option value="Bezel">Bezel</option><option value="Channel">Channel</option><option value="Pave">Pave</option><option value="Bar">Bar</option><option value="Cluster">Cluster</option><option value="Halo">Halo</option><option value="Tension">Tension</option><option value="Invisible">Invisible</option><option value="Bead">Bead</option><option value="Flush">Flush</option><option value="Cup">Cup</option><option value="Wire">Wire</option><option value="Button">Button</option><option value="Cage">Cage</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Total Gemstone Count <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter total gemstone count"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="text" class="form-control attr_total_gemstone" name="attr_gemstone_total_gem_count[]" id="" placeholder="Enter Total Gemstone Count"></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 total_gemstone_wight_sec"><div class="form-sec"><label for="">Total Gemstone Weight <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter total gemstone weight"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="text" class="form-control total_gemstone_weight" name="attr_gemstone_total_wight[]" id="" placeholder="Enter Total Gemstone Weight"></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec gemstone_caret_sec"><label for="">Gemstone Price Per Carat <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter gemstone price per carat"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="text" class="form-control gemstone_price_caret" name="gemstone_price_carat[]" id="" placeholder="Enter Gemstone Price Per Carat"></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 gemstone_total_final_sec"><div class="form-sec gemstone_total_sec"><label for="">Final Gemstone Price <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter final gemstone price"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="text" class="form-control gemstone_final_total" name="gemstone_final_total[]" id="" placeholder="Enter Final Gemstone Price"></div></div></div></div>';
            $('#all_gemstone_div').append(gem_html);
        });

        $(document).on("click", ".all_pearl_add", function() {
           var pearl_html = '<div class="diamond-deatils-sec"><div class="row each-diamond-details"><a><span class="remove_details"><i class="fa fa-times-circle"></i></span></a><div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 sub-heading"><h3>Pearls Details</h3></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Type <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select type for pearl"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_pearl_type[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Natural">Natural</option><option value="Lab-Grown">Lab-Grown</option><option value="Cultured">Cultured</option><option value="Saltwater">Saltwater</option><option value="Imitation">Imitation</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Colour <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select colour for pearl"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_pearl_color[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Black">Black</option><option value="Blue">Blue</option><option value="Brown">Brown</option><option value="Chocolate">Chocolate</option><option value="Cream">Cream</option><option value="Golden">Golden</option><option value="Grey">Grey</option><option value="Ivory">Ivory</option><option value="Lavender">Lavender</option><option value="Multicolour">Multicolour</option><option value="Orange">Orange</option><option value="Peach">Peach</option><option value="Pink">Pink</option><option value="Purple">Purple</option><option value="Silver">Silver</option><option value="White">White</option><option value="White Gold">White Gold</option><option value="White Pink">White Pink</option><option value="Yellow">Yellow</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 pearl_caret_sec"><div class="form-sec"><label for="">Total Carat <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter carat for pearl"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="number" step="0.0001" class="form-control attr_pearl_carat" name="attr_pearl_caret[]" id="" placeholder="Enter Carat"></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Pearl <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select type of pearl"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_pearl_gem[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Akoya">Akoya</option><option value="Freshwater">Freshwater</option><option value="Tahitian">Tahitian</option><option value="South Sea">South Sea</option><option value="Basara">Basara</option><option value="Khakho">Khakho</option><option value="Keshi">Keshi</option><option value="Venezuela">Venezuela</option><option value="Jeko">Jeko</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Shape <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select shape for pearl"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_pearl_shape[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Round">Round</option><option value="Princess">Princess</option><option value="Emerald">Emerald</option><option value="Sq. Emerald">Sq. Emerald</option><option value="Asscher">Asscher</option><option value="Cushion">Cushion</option><option value="Oval">Oval</option><option value="Radiant">Radiant</option><option value="Pear">Pear</option><option value="Marquise">Marquise</option><option value="Heart">Heart</option><option value="Triangle">Triangle</option><option value="Trilliant">Trilliant</option><option value="Baguette">Baguette</option><option value="Trapezoid">Trapezoid</option><option value="Kite">Kite</option><option value="Rose Cut">Rose Cut</option><option value="Briolette">Briolette</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Grade <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select grade for pearl"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_pearl_grade[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="A">A</option><option value="AA">AA</option><option value="AAA">AAA</option><option value="Hanadama">Hanadama</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Setting <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please select setting for pearl"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><select name="attr_pearl_setting[]" class="form-control"><option selected="" disabled="disabled">Select</option><option value="Prong">Prong</option><option value="Bezel">Bezel</option><option value="Channel">Channel</option><option value="Pave">Pave</option><option value="Bar">Bar</option><option value="Cluster">Cluster</option><option value="Halo">Halo</option><option value="Tension">Tension</option><option value="Invisible">Invisible</option><option value="Bead">Bead</option><option value="Flush">Flush</option><option value="Cup">Cup</option><option value="Wire">Wire</option><option value="Button">Button</option><option value="Cage">Cage</option></select></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec"><label for="">Total Pearl Count <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter total pearl count"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="text" class="form-control attr_total_pearl" name="attr_pearl_total_gem_count[]" id="" placeholder="Enter Total Pearl Count"></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 total_pearl_wight_sec"><div class="form-sec"><label for="">Total Pearl Weight <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter total pearl weight"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="text" class="form-control total_pearl_weight" name="attr_pearl_total_wight[]" id="" placeholder="Enter Total Pearl Weight"></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-sec pearl_caret_sec"><label for="">Pearl Price Per Carat <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter price per carat for pearl"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="text" class="form-control pearl_price_caret" name="pearl_price_carat[]" id="" placeholder="Enter Pearl Price Per Carat"></div></div><div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 pearl_total_final_sec"><div class="form-sec pearl_total_sec"><label for="">Final Pearl Price <span data-bs-toggle="tooltip" data-bs-placement="right" title="Please enter final pearl price"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><input type="text" class="form-control pearl_final_total" name="pearl_final_total[]" id="" placeholder="Enter Final Pearl Price"></div></div></div></div>';
            $('#all_pearl_div').append(pearl_html);
        });
        $(document).on("change", "#p_sku", function() {
            var sku = $(this).val();
            $.ajax({
            url: admin_url +"product-sku-check",
            type: "POST",
            data: {
              sku: sku,
              _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function(result) {
                if(result.status == 1)
                { 
                    $('#sku_error').text(result.message);
                    $('#exist_sku').text('Click Here to view product having same SKU');
                    $('#exist_sku').attr('href',result.url);
                    $('.step-form-button-sec').find('.next-step').hide();
                }else{
                    $('#sku_error').text('');
                    $('#exist_sku').text('');
                    $('#exist_sku').attr('href','javascript:;');
                    $('.step-form-button-sec').find('.next-step').show();
                }
            }
          });
        });
        $(document).on("change", "#p_title", function() {
            var title = $(this).val();
            var slug = title.toString().toLowerCase()
                .replace(/\s+/g, '-') // Replace spaces with -
                .replace(/[^\w-]+/g, '') // Remove all non-word characters
                .replace(/--+/g, '-') // Replace multiple - with single -
                .replace(/^-+/, '') // Trim - from start of text
                .replace(/-+$/, '');

            // Update the value of #p_slug
            $('#p_slug').val(slug);
        });
        $('#large_file').on('change', function () {
            var input = this;
            var imagePreviewContainer = $('#image-preview-container');
            var maxImages = 4;
            var maxSizeKB = 5000; // Maximum size in KB
            var existingImageCount = parseInt($('#existing_image_count').val());
            var appeneded_img = imagePreviewContainer.find('.p_img_parent.not_saved').length;
            if (appeneded_img === 0) {
                // No files selected, do nothing
                $('#large-item-img-output-single').remove();
            }
            // Validate the number of selected files
            if (input.files.length === 0) {
                // No files selected, do nothing
                return;
            }

            if (input.files.length + existingImageCount + appeneded_img > maxImages) {
                alert('You can only select up to ' + maxImages + ' images.');
                // Reset the input field
                $(this).val('');
                return;
            }

            var allFileData = [];
            for (var i = 0; i < input.files.length; i++) {
                var file = input.files[i];

                // Validate file size
                if (file.size > maxSizeKB * 1024) {
                    alert('File size for each image should be up to 5 MB.');
                    // Reset the input field
                    $(this).val('');
                    return;
                }

                // Use a closure to capture the file variable
                (function (file) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        var img = new Image();
                        img.src = e.target.result;

                        // Validate image dimensions
                        img.onload = function () {
                            // Append an image tag to the preview container if it's not an existing image
                            if (!$(this).hasClass('existing-image')) {
                                var p_img_parent = $('<div class="p_img_parent not_saved" style="position: relative;"></div>');
                                var previewImage = $('<img class="img-fluid preview_image" src="' + e.target.result + '" style="margin-right:10px;">');
                                var removeIcon = $('<a><span class="remove_icons not_saved"><i class="fa fa-times-circle"></i></span></a>');

                                // Append the remove icon and image to the p_img_parent div
                                p_img_parent.append(previewImage);
                                p_img_parent.append(removeIcon);

                                // Create a hidden file input and append it to the .p_img_parent container
                                var hiddenFileInput = $('<input>', {
                                    type: 'file',
                                    name: 'p_image[]',
                                    style: 'display: none;',
                                    class: 'f_not_saved'
                                });

                                // Create a new DataTransfer instance for each file
                                var dt = new DataTransfer();
                                dt.items.add(file);
                                hiddenFileInput[0].files = dt.files;

                                // Append the hidden file input to the .p_img_parent container
                                p_img_parent.append(hiddenFileInput);

                                // Append the p_img_parent to the imagePreviewContainer
                                imagePreviewContainer.append(p_img_parent);
                            }
                        };
                    };

                    reader.readAsDataURL(file);
                })(file); // Pass file into the closure
            }

            // Code for handling existing images remains unchanged
            for (var i = 0; i < existingImageCount; i++) {
                var imageUrl = $('#existing_image_' + i).val();
                var p_id = $('#existing_id_' + i).val();
                if (!imagePreviewContainer.find('[data-id="' + p_id + '"]').length) {
                    imagePreviewContainer.append('<div class="p_img_parent" style="position: relative;" data-id="' + p_id + '"><img class="img-fluid preview_image" src="' + imageUrl + '" style="margin-right:10px;"><a><span class="remove_icons" data-id="'+p_id+'"><i class="fa fa-times-circle"></i></span></a></div>');
                }
            }
        });

        $(document).on('click', '.remove_icons.not_saved', function(){
            $(this).closest('.p_img_parent').remove();
            $(this).closest('.p_img_parent').next().remove();
            var old_count = $('#existing_image_count').val();
            old_count--;
            $('#existing_image_count').val(old_count);
        });
        $(document).on('click', '.contry_check', function(){
            if($(this).prop('checked')){
                $(this).closest('tr').find('.int_tax').attr('name', 'p_int_tax[]');
                $(this).closest('tr').find('.int_above').attr('name', 'p_int_above[]');
            }else {
                $(this).closest('tr').find('.int_tax').removeAttr('name');
                $(this).closest('tr').find('.int_above').removeAttr('name');
            }
        });
        $("#video_file").change(function () {
            var fileInput = this;
            var previewImage = $("#video-output");
            var videoModal = $("#videoModal");
            var videoPlayer = $("#videoPlayer")[0];

            var file = fileInput.files[0];

            if (file) {
                var fileType = file.type;
                var fileSize = file.size;

                if (fileType.startsWith('video/')) {
                    // It's a video file
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        previewImage.attr("src", "https://jewelxy.workdemo.in.net/images/videoicon.jpg");
                        videoPlayer.src = e.target.result; // Replace with the path to your play icon
                    };
                    previewImage.addClass('show_video');
                    reader.readAsDataURL(file);
                } else {
                    alert('Please select a valid video file.');
                    $(this).val("");
                }
            }
        });
        $(document).on("click", ".show_video", function() 
        {
            $('#video_modal').modal('show');
        });
        $(document).on("click", ".del_video", function(){
            $('#old_p_video').val('');
            $('#video-output').removeClass('show_video');
            $('#video-output').attr('src','https://jewelxy.workdemo.in.net/assets/images/user/img-demo_1041.jpg')
            $(this).hide();
        });
        $(document).on("click", ".del_p_image", function(){
            var del_img_id = $(this).data('id');
            $.ajax({
            url: admin_url +"product-del-img",
            type: "POST",
            data: {
              del_img_id: del_img_id,
              _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function(result) {
                if(result.status == 1)
                {
                    $('.p_img_parent[data-id="' + del_img_id + '"]').remove();
                    toastr.error(result.message);
                }else{
                    toastr.error(result.message);
                }
            }
          });
        });
        $('#certificate_file').change(function () {
            var certificate = this;
            var uploadText = $('#uploadText');
            var previewContainer = $('#certificatePreviewContainer');
            previewContainer.empty(); // Clear previous preview

            if (certificate.files && certificate.files[0]) {
                var file = certificate.files[0];

                if (file.type === 'application/pdf') {
                    previewContainer.html('<a href="' + URL.createObjectURL(file) + '" target="_blank" style="margin-left:10px;">View PDF</a>');
                    $('#old_certi_file').val('');
                } else if (file.type.startsWith('image/')) {
                    // Handle image preview
                    var img = $('<img>').attr('src', URL.createObjectURL(file)).addClass('img-fluid');
                    previewContainer.append(img);
                    $('#old_certi_file').val('');
                } else {
                    // Handle other file types or show an error message
                    alert('Unsupported file type. Please upload an image or PDF.');
                    $(certificate).val(''); // Reset the certificate field
                    uploadText.text('Upload');
                }
            }
        });
    $('.p_status').on('change', function () {
        // alert($(this).val());
        var val = $(this).val();
        if(val == 'ready_stock')
        {
            $('#avil_stock_qty').show();
            $('#ltd_stock_qty').show();
        }else{
            $('#avil_stock_qty').hide();
            $('#ltd_stock_qty').hide();
        }
    });
    $('#p_metal_purity').on('change', function () {
        var val = $(this).val();
        $.ajax({
            url: admin_url +"get-metal-rate",
            type: "POST",
            data: {
              val: val,
              _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function(result) {
              $('#metal_rate').val(result.rate);
              total_metal_price_calculate();
            }
        });
    });
    $('#p_metal_weight_unit,#p_metal_weigth').on('change', function () {
        total_metal_price_calculate();
    });

    function total_metal_price_calculate(){
        var weight = parseFloat($("#p_metal_weigth").val());
        var rate = parseFloat($('#metal_rate').val());
        var weight_unit = $('#p_metal_weight_unit').val();

        if(!isNaN(rate) && !isNaN(weight) && weight_unit != null)
        {
            if (weight_unit === 'grams') {
                result = weight * rate;
            } else if (weight_unit === 'Kilogram') {
                result = weight * 1000 * rate;
            }else{
                var result = weight * rate;
            }
            $("#exis_total_price").val(result.toFixed(2));
            $("#total_metal_price").val(result.toFixed(2));
            var metal_string = '(' +rate + ' * ' + weight + ' ' + weight_unit + ' = ' + result.toFixed(2) + ')';
            $('#metal_text_string').text(metal_string); 
        }
    }

    //$('#p_metal_weigth').keyup(function (){
    $(document).on("keyup change", "#p_metal_weigth", function() {
        total_metal_price_calculate();
    });
    // diamond calculations start
    $(document).on("keyup", ".attr_total_dimond", function() {
        var weight = $(this).val();
        var caret = $(this).closest('.each-diamond-details').find('.caret_sec .attr_carat').val();
        var result = weight * caret;
        var caret = $(this).closest('.each-diamond-details').find('.total_wight_sec .total_weight').val(result.toFixed(2));
        // $('#total_metal_price').val(result);
    });
        $(document).on("keyup", ".price_caret, .attr_carat, .attr_total_dimond", function() {
        
        var dync = $(this).closest('.each-diamond-details').find('.type-sec .attr_type_dynamic_class').val();
        
        var price_caret = $(this).closest('.each-diamond-details').find('.caret_sec .price_caret').val();
        var caret = $(this).closest('.each-diamond-details').find('.caret_sec .attr_carat').val();
        var total_count = $(this).closest('.each-diamond-details').find('.diamond_count_sec .attr_total_dimond').val();
        if(dync){
            var result = price_caret * caret;
        }else{
            var result = price_caret * caret * total_count;
        }
        var caret_n = $(this).closest('.each-diamond-details').find('.total_final_sec .final_total').val(result);
        if(dync){
            var final_string_diamond = '('+ caret +' carat * '+ price_caret +' = '+ result +') ';
        }else{
            var final_string_diamond = '('+ caret +' carat * '+ total_count +' * '+ price_caret +' = '+ result +') ';
        }
        dimondgrandTotal = calculatediamondGrandTotal();
        $('#total_diamond_charge').val(dimondgrandTotal);
        $(this).closest('.each-diamond-details').find('.diamonds_total_sec .dyn_summry').text(final_string_diamond);
    });
        $(document).on("click", ".all_diamond_calculate", function() {
            var totalclickedDiamondPrice = 0;
            $(".diamonds_total_sec").each(function() {
                var finalDiamondPrice = parseFloat($(this).find(".final_total").val()) || 0;
                totalclickedDiamondPrice += finalDiamondPrice;
            });
            $('#total_diamond_charge').val(totalclickedDiamondPrice);
            calculate_tax_values();
        });
        function calculatediamondGrandTotal() {
        var total = 0;

        // Iterate through each .each-diamond-details and accumulate the results
        $('.each-diamond-details').each(function() {
            var result = parseFloat($(this).find('.total_final_sec .final_total').val()) || 0;
            total += result;
        });
        return total;
    }
    // diamond calculations end

    // gemstone calculations start

        $(document).on("keyup", ".attr_total_gemstone", function() {
                var gem_weight = $(this).val();
                var gem_caret = $(this).closest('.each-diamond-details').find('.gemstone_caret_sec .attr_gemstone_carat').val();
                var gem_result = gem_weight * gem_caret;
                var gem_total = $(this).closest('.each-diamond-details').find('.total_gemstone_wight_sec .total_gemstone_weight').val(gem_result);
                // $('#total_metal_price').val(result);
            });
        $(document).on("keyup", ".gemstone_price_caret, .attr_gemstone_carat, .attr_total_gemstone", function() {
            var gemstone_price_caret = $(this).closest('.each-diamond-details').find('.gemstone_caret_sec .gemstone_price_caret').val();
            var gem_caret = $(this).closest('.each-diamond-details').find('.gemstone_caret_sec .attr_gemstone_carat').val();
            var gem_total_count = $(this).closest('.each-diamond-details').find('.total-gem-count-sec .attr_total_gemstone').val();
            var result = gemstone_price_caret * gem_caret * gem_total_count;
            var total_caret = $(this).closest('.each-diamond-details').find('.gemstone_total_final_sec .gemstone_final_total').val(result);
            var final_string_gem = '('+ gem_caret +' carat * '+ gem_total_count +' * '+ gemstone_price_caret +' = '+ result +') ';
            gemstoneTotal = calculategemstoneGrandTotal();
            $('#total_gemstone_charge').val(gemstoneTotal);
            $(this).closest('.each-diamond-details').find('.gemstone_total_sec .dyn_summry_gemstone').text(final_string_gem);
        });
        $(document).on("click", ".all_gemstone_calculate", function() {
            var totalclickedgemstonePrice = 0;
            $(".gemstone_total_sec").each(function() {
                var finalgemstonePrice = parseFloat($(this).find(".gemstone_final_total").val()) || 0;
                totalclickedgemstonePrice += finalgemstonePrice;
            });
            $('#total_gemstone_charge').val(totalclickedgemstonePrice);
            calculate_tax_values();
        });
        function calculategemstoneGrandTotal() {
            var total = 0;

            // Iterate through each .each-diamond-details and accumulate the results
            $('.each-diamond-details').each(function() {
                var result = parseFloat($(this).find('.gemstone_total_final_sec .gemstone_final_total').val()) || 0;
                total += result;
            });
            return total;
        }
    
    // gemstone calculations end

    // pearl calculations start
    $(document).on("keyup", ".attr_total_pearl", function() {
                var pearl_weight = $(this).val();
                var pearl_caret = $(this).closest('.each-diamond-details').find('.pearl_caret_sec .attr_pearl_carat').val();
                var pearl_result = pearl_weight * pearl_caret;
                var pearl_total = $(this).closest('.each-diamond-details').find('.total_pearl_wight_sec .total_pearl_weight').val(pearl_result);
                // $('#total_metal_price').val(result);
            });
        $(document).on("keyup", ".pearl_price_caret, .attr_pearl_carat, .attr_total_pearl", function() {
            var pearl_price_caret = $(this).closest('.each-diamond-details').find('.pearl_caret_sec .pearl_price_caret').val();
            var pearl_caret = $(this).closest('.each-diamond-details').find('.pearl_caret_sec .attr_pearl_carat').val();
            var pearl_total_count = $(this).closest('.each-diamond-details').find('.total-pear-count-sec .attr_total_pearl').val();
            var result = pearl_price_caret * pearl_caret * pearl_total_count;
            var total_caret = $(this).closest('.each-diamond-details').find('.pearl_total_final_sec .pearl_final_total').val(result);
            var final_string_pearl = '('+ pearl_caret +' carat * '+ pearl_total_count +' * '+ pearl_price_caret +' = '+ result +') ';
            pearlTotal = calculatepearlGrandTotal();
            $('#total_pearl_charge').val(pearlTotal);
            $(this).closest('.each-diamond-details').find('.pearl_total_sec .dyn_summry_pearl').text(final_string_pearl);
        });
        $(document).on("click", ".all_pearl_calculate", function() {
            var totalclickedpearlPrice = 0;
            $(".pearl_total_sec").each(function() {
                var finalpearlPrice = parseFloat($(this).find(".pearl_final_total").val()) || 0;
                totalclickedpearlPrice += finalpearlPrice;
            });
            $('#total_pearl_charge').val(totalclickedpearlPrice);
            calculate_tax_values();
        });
        function calculatepearlGrandTotal() {
            var total = 0;

            // Iterate through each .each-diamond-details and accumulate the results
            $('.each-diamond-details').each(function() {
                var result = parseFloat($(this).find('.pearl_total_final_sec .pearl_final_total').val()) || 0;
                total += result;
            });
            return total;
        }
    // pearls calculations end

    // master Calculation start
    $(document).on("click", ".calculate-btn", function() {
        // making charge
        // var total_making_price = parseFloat($('#total_making_price').val()) || 0;
        // var dis_making_price = parseFloat($('#dis_making_price').val()) || 0;
        // var make_dis_check = $('input[name="make_dis"]:checked').val();

        // if(make_dis_check == 'percentage')
        // {
        //     var discountAmount = (dis_making_price / 100) * total_making_price;
        //     var discountedmakingTotal = total_making_price - discountAmount;
        // }else{
        //     var discountedmakingTotal = total_making_price - dis_making_price;
        // }
        // // diamond charge
        // var total_diamond_charge = parseFloat($('#total_diamond_charge').val()) || 0;
        // var dis_diamond_price = parseFloat($('#dis_diamond_price').val()) || 0;
        // var diamond_dis_check = $('input[name="diamond_dis"]:checked').val();

        // if(diamond_dis_check == 'percentage')
        // {
        //     var discountdiamondAmount = (dis_diamond_price / 100) * total_diamond_charge;
        //     var discounteddiamondTotal = total_diamond_charge - discountdiamondAmount;
        // }else{
        //     var discounteddiamondTotal = total_diamond_charge - dis_diamond_price;
        // }

        // // pearl charge
        // var total_pearl_charge = parseFloat($('#total_pearl_charge').val()) || 0;
        // var dis_pearl_price = parseFloat($('#dis_pearl_price').val()) || 0;
        // var pearl_dis_check = $('input[name="pearl_dis"]:checked').val();

        // if(pearl_dis_check == 'percentage')
        // {
        //     var discountpearlAmount = (dis_pearl_price / 100) * total_pearl_charge;
        //     var discountedpearlTotal = total_pearl_charge - discountpearlAmount;
        // }else{
        //     var discountedpearlTotal = total_pearl_charge - dis_pearl_price;
        // }

        // // gemstone charge 
        // var total_gemstone_charge = parseFloat($('#total_gemstone_charge').val()) || 0;
        // var dis_gemstone_price = parseFloat($('#dis_gemstone_price').val()) || 0;
        // var gemstone_dis_check = $('input[name="gemstone_dis"]:checked').val();

        // if(gemstone_dis_check == 'percentage')
        // {
        //     var discountgemstoneAmount = (dis_gemstone_price / 100) * total_gemstone_charge;
        //     var discountedgemstoneTotal = total_gemstone_charge - discountgemstoneAmount;
        // }else{
        //     var discountedgemstoneTotal = total_gemstone_charge - dis_gemstone_price;
        // }

        // // other chage
        // var total_other_charge = parseFloat($('#total_other_charge').val()) || 0;
        // var dis_other_price = parseFloat($('#dis_other_price').val()) || 0;
        // var other_dis_check = $('input[name="other_dis"]:checked').val();

        // if(other_dis_check == 'percentage')
        // {
        //     var discountotherAmount = (dis_other_price / 100) * total_other_charge;
        //     var discountedotherTotal = total_other_charge - discountotherAmount;
        // }else{
        //     var discountedotherTotal = total_other_charge - dis_other_price;
        // }

        // // fix chage
        // var p_fix_price = parseFloat($('#p_fix_price').val()) || 0;
        // var fix_p_discount = parseFloat($('#fix_p_discount').val()) || 0;
        // var fix_dis = $('input[name="fix_dis"]:checked').val();

        // if(fix_dis == 'percentage')
        // {
        //     var discountfixAmount = (fix_p_discount / 100) * p_fix_price;
        //     var discountedfixTotal = p_fix_price - discountfixAmount;
        // }else{
        //     var discountedfixTotal = p_fix_price - fix_p_discount;
        // }


        // var total_metal_price = parseFloat($('#total_metal_price').val()) || 0;
        // var grand_total = total_metal_price + discountedmakingTotal + discounteddiamondTotal + discountedpearlTotal + discountedgemstoneTotal + discountedotherTotal + discountedfixTotal;
        // var national_above = parseFloat($('#p_above_amount').val()) || 0 ;
        // var national_tax = parseFloat($('#p_national_tax').val()) || 0;
        // if(grand_total > national_above)
        // {
        //     var taxAmount = grand_total * (national_tax / 100);
        //     grand_total += taxAmount;
        // }else{
        //     var taxAmount = 0;
        // }
        // $('#total_tax_charge').val(taxAmount);
        // $('#grand_price_total').val(grand_total);
        // $('#p_final_metal_price').val(total_metal_price);
        // $('#p_final_makin_price').val(discountedmakingTotal);
        // $('#p_final_diamond_price').val(discounteddiamondTotal);
        // $('#p_final_pearl_price').val(discountedpearlTotal);
        // $('#p_final_gemstone_price').val(discountedgemstoneTotal);
        // $('#p_final_other_price').val(discountedotherTotal);
        // $('#p_final_fix_price').val(discountedfixTotal);
        calculate_tax_values();
    });


    function calculate_tax_values(){
        
        
        // diamond charge
        var total_diamond_charge = parseFloat($('#total_diamond_charge').val()) || 0;
        var dis_diamond_price = parseFloat($('#dis_diamond_price').val()) || 0;
        var diamond_dis_check = $('input[name="diamond_dis"]:checked').val();

        if(diamond_dis_check == 'percentage')
        {
            var discountdiamondAmount = (dis_diamond_price / 100) * total_diamond_charge;
            var discounteddiamondTotal = total_diamond_charge - discountdiamondAmount;
            var diamond_string = '('+ total_diamond_charge + ' - ' + dis_diamond_price + '% = ' + discounteddiamondTotal + ')';
        }else{
            var discountdiamondAmount = dis_diamond_price;
            var discounteddiamondTotal = total_diamond_charge - dis_diamond_price;
            var diamond_string = '('+ total_diamond_charge + ' - ' + dis_diamond_price + ' = ' + discounteddiamondTotal + ')';
        }
        $('#diamond_dis_string').text(diamond_string);

        // pearl charge
        var total_pearl_charge = parseFloat($('#total_pearl_charge').val()) || 0;
        var dis_pearl_price = parseFloat($('#dis_pearl_price').val()) || 0;
        var pearl_dis_check = $('input[name="pearl_dis"]:checked').val();

        if(pearl_dis_check == 'percentage')
        {
            var discountpearlAmount = (dis_pearl_price / 100) * total_pearl_charge;
            var discountedpearlTotal = total_pearl_charge - discountpearlAmount;
            var pearl_string = '('+ total_pearl_charge + ' - ' + dis_pearl_price + '% = ' + discountedpearlTotal + ')';
        }else{
            var discountpearlAmount = dis_pearl_price;
            var discountedpearlTotal = total_pearl_charge - dis_pearl_price;
            var pearl_string = '('+ total_pearl_charge + ' - ' + dis_pearl_price + ' = ' + discountedpearlTotal + ')';
        }
        $('#pearl_dis_string').text(pearl_string);
        // gemstone charge 
        var total_gemstone_charge = parseFloat($('#total_gemstone_charge').val()) || 0;
        var dis_gemstone_price = parseFloat($('#dis_gemstone_price').val()) || 0;
        var gemstone_dis_check = $('input[name="gemstone_dis"]:checked').val();

        if(gemstone_dis_check == 'percentage')
        {
            var discountgemstoneAmount = (dis_gemstone_price / 100) * total_gemstone_charge;
            var discountedgemstoneTotal = total_gemstone_charge - discountgemstoneAmount;
            var gemstone_string = '('+ total_gemstone_charge + ' - ' + dis_gemstone_price + '% = ' + discountedgemstoneTotal + ')';
        }else{
            var discountgemstoneAmount = dis_gemstone_price;
            var discountedgemstoneTotal = total_gemstone_charge - dis_gemstone_price;
            var gemstone_string = '('+ total_gemstone_charge + ' - ' + dis_gemstone_price + ' = ' + discountedgemstoneTotal + ')';
        }
        $('#gemstone_dis_string').text(gemstone_string);
        // other chage
        var total_other_charge = parseFloat($('#total_other_charge').val()) || 0;
        var dis_other_price = parseFloat($('#dis_other_price').val()) || 0;
        var other_dis_check = $('input[name="other_dis"]:checked').val();

        if(other_dis_check == 'percentage')
        {
            var discountotherAmount = (dis_other_price / 100) * total_other_charge;
            var discountedotherTotal = total_other_charge - discountotherAmount;
            var other_string = '('+ total_other_charge + ' - ' + dis_other_price + '% = ' + discountedotherTotal + ')';
        }else{
            var discountotherAmount = dis_other_price;
            var discountedotherTotal = total_other_charge - dis_other_price;
            var other_string = '('+ total_other_charge + ' - ' + dis_other_price + ' = ' + discountedotherTotal + ')';
        }
        $('#other_dis_string').text(other_string);
        var p_pricetype = $('input[name="p_pricetype"]:checked').val();
        if(p_pricetype == 'fix_price')
        {
            var p_fix_price = parseFloat($('#p_fix_price').val()) || 0;
            var fix_p_discount = parseFloat($('#fix_p_discount').val()) || 0;
            var fix_dis = $('input[name="fix_dis"]:checked').val();

            if(fix_dis == 'percentage')
            {
                var discountfixAmount = (fix_p_discount / 100) * p_fix_price;
                var discountedfixTotal = p_fix_price - discountfixAmount;
            }else{
                var discountfixAmount = fix_p_discount;
                var discountedfixTotal = p_fix_price - fix_p_discount;
            }

            grand_total = discountedfixTotal;

            var national_above = parseFloat($('#p_above_amount').val()) || 0 ;
            var national_tax = parseFloat($('#p_national_tax').val()) || 0;
            if(national_tax === 0)
            {
                national_tax = parseFloat($('#global_national_tax').val()) || 0;
                var global_sting = 'Global';
            }else{
                var global_sting = '';
            }
            var taxAmount = grand_total * (national_tax / 100);
            var f_above_amount = parseFloat($('#p_above_amount').val());
            
            
            // alert(grand_total);
            
            $('#total_tax_charge').val(taxAmount.toFixed(4));
            if(grand_total > f_above_amount)
            {
                $('#tax_span_text').text('('+ national_tax + '% Tax on Total Amount '+ grand_total.toFixed(4) +')');
                grand_total += taxAmount;
                $('#total_tax_charge').val(taxAmount.toFixed(4));
            }else{
                $('#tax_span_text').text('');
                $('#total_tax_charge').val(0);
            }
            $('#grand_price_total').val(grand_total.toFixed(2));
            $('#p_final_fix_price').val(discountedfixTotal.toFixed(4));
            var final_text_string = 'Fix Price(' + p_fix_price + ') - Discount(' + discountfixAmount + ') + '+ global_sting +' Tax Charges(' + taxAmount + ')';
            $('#total_price_display').text(final_text_string);

        }else if(p_pricetype == 'no_price')
        {
            $('#total_price_display').text('');
            $('#total_metal_price').val('');
            $('#grand_price_total').val(0.00);
        }
        else{
            var total_metal_price = parseFloat($('#total_metal_price').val()) || 0;
            var grand_total = total_metal_price  + discounteddiamondTotal + discountedpearlTotal + discountedgemstoneTotal + discountedotherTotal;
            var national_tax = parseFloat($('#p_national_tax').val()) || 0;
            var p_makingtype = $('input[name="make_type"]:checked').val();
            var p_making_price = parseFloat($('#only_making_charges').val()) || 0;
            if(p_makingtype == 'percentage')
            {
                var makingchargeAmount = (total_metal_price / 100) * p_making_price;
                $('#total_making_price').val(makingchargeAmount.toFixed(4));
                
            }else{
                var no_making_charge = parseFloat($('#total_making_price').val()) || 0;
                var makingchargeAmount = p_making_price;
                $('#total_making_price').val(no_making_charge.toFixed(4));
                grand_total += no_making_charge;
            }
            var total_making_price = parseFloat($('#total_making_price').val()) || 0;
            var dis_making_price = parseFloat($('#dis_making_price').val()) || 0;
            var make_dis_check = $('input[name="make_dis"]:checked').val();
            
            if(make_dis_check == 'percentage')
            {
                var discountAmount = (dis_making_price / 100) * total_making_price;
                var discountedmakingTotal = total_making_price - discountAmount;
                var making_string = '('+ total_making_price + ' - ' + dis_making_price + '% = ' + discountedmakingTotal + ')';
                grand_total += makingchargeAmount - discountAmount;
            }else{
                var discountAmount = dis_making_price;
                var discountedmakingTotal = total_making_price - dis_making_price;
                var making_string = '('+ total_making_price + ' - ' + dis_making_price + ' = ' + discountedmakingTotal + ')';
                grand_total += makingchargeAmount - discountAmount;
            }
            console.log(grand_total);
            $('#making_dis_string').text(making_string);
            if(national_tax === 0)
            {
                national_tax = parseFloat($('#global_national_tax').val()) || 0;
                var global_sting = 'Global';
            }else{
                var global_sting = '';
            }
            var taxAmount = grand_total * (national_tax / 100);
            var f_above_amount = parseFloat($('#p_above_amount').val());
            
            $('#total_tax_charge').val(taxAmount.toFixed(4));
            
            
            if(grand_total > f_above_amount)
            {
                $('#tax_span_text').text('('+ national_tax + '% of on '+ grand_total.toFixed(4) +')');
                $('#total_tax_charge').val(taxAmount.toFixed(4));
                grand_total += taxAmount;
            }else{
                $('#total_tax_charge').val(0);
                $('#tax_span_text').text('');
            }
            $('#grand_price_total').val(grand_total.toFixed(2));
            $('#p_final_metal_price').val(makingchargeAmount.toFixed(4));
            $('#p_final_makin_price').val(discountedmakingTotal.toFixed(4));
            $('#p_final_diamond_price').val(discounteddiamondTotal.toFixed(4));
            $('#p_final_pearl_price').val(discountedpearlTotal.toFixed(4));
            $('#p_final_gemstone_price').val(discountedgemstoneTotal.toFixed(4));
            $('#p_final_other_price').val(discountedotherTotal.toFixed(4));
            // var final_text_string = 'Metal Price (' + total_metal_price.toFixed(2) + ') + Making charges (' + discountedmakingTotal.toFixed(2) + ') + Diamond Charges ('+ discounteddiamondTotal.toFixed(2) +')+ Pearl Charges ('+ discountedpearlTotal.toFixed(2) +') + Gemstone Charges ('+ discountedgemstoneTotal.toFixed(2) +') + Other Charges ('+ discountedotherTotal.toFixed(2) +') + '+ global_sting +' Tax Charges ('+ taxAmount.toFixed(2) +')';
            var final_text_string = '';

            if (total_metal_price > 0) {
                final_text_string += 'Metal Price (' + total_metal_price.toFixed(2) + ') + ';
            }

            if (discountedmakingTotal > 0) {
                final_text_string += 'Making charges (' + discountedmakingTotal.toFixed(2) + ') + ';
            }

            if (discounteddiamondTotal > 0) {
                final_text_string += 'Diamond Charges (' + discounteddiamondTotal.toFixed(2) + ') + ';
            }

            if (discountedpearlTotal > 0) {
                final_text_string += 'Pearl Charges (' + discountedpearlTotal.toFixed(2) + ') + ';
            }

            if (discountedgemstoneTotal > 0) {
                final_text_string += 'Gemstone Charges (' + discountedgemstoneTotal.toFixed(2) + ') + ';
            }

            if (discountedotherTotal > 0) {
                final_text_string += 'Other Charges (' + discountedotherTotal.toFixed(2) + ') + ';
            }

            final_text_string += global_sting + ' Tax Charges (' + taxAmount.toFixed(2) + ')';
            $('#total_price_display').text(final_text_string);
        }

    }
    $(document).on("change", ".make_type", function() {
        var make_type_val = $(this).val();
        if(make_type_val == 'price')
        {
            $('#only_making_charges').val('');
            $('#only_making_charges').attr('readonly',true);
        }else{
            $('#only_making_charges').attr('readonly',false);
        }
        calculate_tax_values();
    });
    $(document).on("change", "#total_making_price", function() {
        calculate_tax_values();
    });
    $(document).on("change keyup", "#p_national_tax, #p_above_amount, #p_fix_price, #fix_p_discount, .fix_dis, #total_other_charge, #dis_other_price, .other_dis, #dis_gemstone_price, .gemstone_dis, #dis_pearl_price, .pearl_dis, #dis_diamond_price, .diamond_dis, #dis_making_price, .make_dis", function() {
            calculate_tax_values();
    });
    $(document).on("change keyup", "#only_making_charges", function() {
        calculate_tax_values();
    });


    // Master Calculation End
    $(document).on("change", ".delivery_country", function() {
        var country = this.value;
        var stateContainer = $(this).closest('.col-xxl-6').next('.col-xxl-6').find('.state_sec .delivery_state');
        stateContainer.html('');
          // $("#state").html('');
          $.ajax({
            url: admin_url +"get-state",
            type: "POST",
            data: {
              country: country,
              _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function(result) {

              stateContainer.html('<option value="">Select State</option>');
              $.each(result.state, function(key, value) {
               stateContainer.append('<option value="' + value.id + '">' + value.name + '</option>');
              });

            }
          }); 
    });

    $(document).on("change", ".delivery_state", function() {
        var state = $(this).val();
        var zipContainer = $(this).closest('.col-xxl-6').next('.col-xxl-6').find('.zip_sec .delivery_zip');
        zipContainer.html('');
          // $("#state").html('');
          $.ajax({
            url: admin_url +"get-deliver-zip",
            type: "POST",
            data: {
              state: state,
              _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function(result) {
                if(result.status == 1)
                {
                   zipContainer.html('<option value="">Select Zip Code</option>');
                      $.each(result.zip, function(key, value) {
                       zipContainer.append('<option value="' + value.id + '">' + value.code + '</option>');
                    }); 
                }
              

            }
          }); 
    });
    $(document).on("change", "input[type=radio][name=p_pricetype]", function() {
        var val = $(this).val();
        var existing = $('#exis_total_price').val();
        if(val == 'fix_price')
        {
            $('#total_metal_price').val('');
            $('#p_fix_price').attr('readonly',false);
            $('#fix_p_discount').attr('readonly',false);
            $('.Dynamic_price_data').hide();
            $('.Fixed_price_data').show();
            $('#grand_price_total').attr('required',true);
            $('#cal_button_div').show();
            $('.price-calculate-sec').show();
        }
        else if(val == 'no_price')
        {
            $('#total_metal_price').val('');
            $('#p_fix_price').val('');
            $('#p_fix_price').attr('readonly',false);
            $('#fix_p_discount').attr('readonly',false);
            $('.Dynamic_price_data').hide();
            $('.Fixed_price_data').hide();
            $('#cal_button_div').hide();
            $('.price-calculate-sec').hide();
            $('#grand_price_total').attr('required',false);
        }
        else{
            $('#total_metal_price').val(existing);
            total_metal_price_calculate();
            $('#p_fix_price').val('');
            $('#fix_p_discount').val('');
            $('#p_fix_price').attr('readonly',true);
            $('#fix_p_discount').attr('readonly',true);
            $('.Dynamic_price_data').show();
            $('.Fixed_price_data').hide();
            $('#grand_price_total').attr('required',true);
            $('#cal_button_div').show();
            $('.price-calculate-sec').show();
        }
        calculate_tax_values();
    });


    $(document).on("change", "input[type=radio][name=fix_dis]", function() {
        calculate_fixed_price_with_discount();
    });

    $(document).on("keyup change", "#fix_p_discount", function() {
        calculate_fixed_price_with_discount();
    });

    $(document).on("keyup change", "#p_fix_price", function() {
        calculate_fixed_price_with_discount();
    });

    function calculate_fixed_price_with_discount(){
        var p_fix = $('#p_fix_price').val();
        var selected_amount = $('input[name="fix_dis"]:checked').val();
        var amount_or_per = $('#fix_p_discount').val();
        var Discount = 0;
        
        if(typeof p_fix !== "undefined" && typeof selected_amount !== "undefined" && typeof amount_or_per !== "undefined"){
            if(selected_amount == 'price'){
                Discount = p_fix - amount_or_per;
            }
            if(selected_amount == 'percentage'){
                Discount = p_fix - (p_fix)*(amount_or_per/100);
            }
        }
        if(isNaN(Discount)){
            Discount = 0;
        }
        $('#p_after_discount').val(Discount);
    }
    $(document).on("click", "#select_type", function() {
        var checked = $('input[type="radio"][name="type"]:checked').val();
        $.ajax({
            url: admin_url +"selected-data",
            type: "POST",
            data: {
                checked: checked,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function(result) {
               if(result.status == 1 && result.html != '')
               {
                    $('#type_modal').modal('hide');
                    $('#details_sec').append(result.html);
               }
            }
        }); 
    });
    $(document).on("change", ".attr_type_dynamic_class", function() {
        var $this = $(this);
        var d_type = $this.val();
        var d_caret =  $this.closest('.each-diamond-details').find('.caret_sec .attr_carat').val();
        $.ajax({
            url: admin_url +"get-diamond-rate",
            type: "POST",
            data: {
                d_type: d_type,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function(result) {
               if(result.status == 1 && result.rate != '')
               {
                    $this.closest('.each-diamond-details').find('.caret_sec .price_caret').val(result.rate.rate);
                    $this.closest('.each-diamond-details').find('.quality-sec .attr_type_quality_class').val(result.rate.quality);
                    var final = result.rate.rate * d_caret;
                    $this.closest('.each-diamond-details').find('.diamonds_total_sec .final_total').val(final);
                    $this.closest('.each-diamond-details').find('.diamonds_total_sec .dyn_summry').text('( '+ result.rate.rate + ' *'+ d_caret + ' = '+ final +')');
               }else{
                    $this.closest('.each-diamond-details').find('.caret_sec .price_caret').val('');
                    toastr.error(result.message);
                    $this.closest('.each-diamond-details').find('.diamonds_total_sec .final_total').val('');
                    $this.closest('.each-diamond-details').find('.diamonds_total_sec .dyn_summry').text('');
               }
            }
        }); 
    });
    $(document).on("change", ".attr_type_quality_class", function() {
        var $this = $(this);
        var d_type = $this.closest('.each-diamond-details').find('.type-sec .attr_type_dynamic_class').val();
        var d_caret =  $this.closest('.each-diamond-details').find('.caret_sec .attr_carat').val();
        var d_quality = $this.val();
        $.ajax({
            url: admin_url +"get-diamond-rate",
            type: "POST",
            data: {
                d_type: d_type,
                d_quality: d_quality,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: 'json',
            success: function(result) {
               if(result.status == 1 && result.rate != '')
               {
                    $this.closest('.each-diamond-details').find('.caret_sec .price_caret').val(result.rate.rate);
                    $this.closest('.each-diamond-details').find('.quality-sec .attr_type_quality_class').val(result.rate.quality);
                    var final = result.rate.rate * d_caret;
                    $this.closest('.each-diamond-details').find('.diamonds_total_sec .final_total').val(final);
                    $this.closest('.each-diamond-details').find('.diamonds_total_sec .dyn_summry').text('( '+ result.rate.rate + ' * '+ d_caret + ' = '+ final +')');
               }else{
                    $this.closest('.each-diamond-details').find('.caret_sec .price_caret').val('');
                    toastr.error(result.message);
                    $this.closest('.each-diamond-details').find('.diamonds_total_sec .final_total').val('');
                    $this.closest('.each-diamond-details').find('.diamonds_total_sec .dyn_summry').text('');
               }
            }
        }); 
    });

});


