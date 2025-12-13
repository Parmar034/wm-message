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
                    $('#details_sec').html(result.html);
                    if(result.cat == 9)
                    {
                        $('#metal_sec_div').hide();
                        $('#gemstone_div').show();
                        $('#p_metal_purity').prop('required',false);
                        $('#p_metal_weigth').prop('required',false);
                    }else{
                        $('#metal_sec_div').show();
                        $('#gemstone_div').hide();
                        $('#p_metal_purity').prop('required',true);
                        $('#p_metal_weigth').prop('required',true);
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
            var field = '<div class="diamond-deatils-sec mt-4"><div class="each-diamond-details row"><a><span class="remove_details"><i class="fa fa-times-circle"></i></span></a><div class="col-sm-12 col-xs-12 col-lg-12 col-md-12 col-xl-12 col-xxl-12 sub-heading"><h3>Diamonds Details</h3></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Type <span data-bs-placement=right data-bs-toggle=tooltip title="Please select type of diamond"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <select class=form-control name=attr_type[]><option disabled selected>Select<option value=Natural>Natural<option value=Lab-Grown>Lab-Grown<option value=Lab-Grown>Cultured<option value=Lab-Grown>Saltwater<option value=Lab-Grown>Imitation</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Fancy Colour <span data-bs-placement=right data-bs-toggle=tooltip title="Please select fancy colour for diamond"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <select class=form-control name=attr_fancy_color[]><option disabled selected>Select<option value=White>White<option value=Yellow>Yellow<option value=Pink>Pink<option value=Purple>Purple<option value=Blue>Blue<option value=Green>Green<option value=Orange>Orange<option value=Brown>Brown<option value=Black>Black</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 caret_sec"><div class=form-sec><label for="">Carat <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter carat for diamond"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <input class="form-control attr_carat"name=attr_diamond_caret[] step="0.0001" placeholder="Enter Carat"type=number></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Gemstone <span data-bs-placement=right data-bs-toggle=tooltip title="Please select gemstone for diamond"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <select class=form-control name=attr_gemstone[]><option disabled selected>Select<option value=Diamond>Diamond<option value="Sapphire Yellow">Sapphire Yellow<option value="Sapphire Blue">Sapphire Blue<option value="Sapphire Pink">Sapphire Pink<option value=Ruby>Ruby<option value=Emerald>Emerald<option value=Moissanite>Moissanite</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Shape <span data-bs-placement=right data-bs-toggle=tooltip title="Please select shape for diamond"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <select class=form-control name=attr_shape[]><option disabled selected>Select<option value=Round>Round<option value=Princess>Princess<option value=Emerald>Emerald<option value="Sq. Emerald">Sq. Emerald<option value=Asscher>Asscher<option value=Cushion>Cushion<option value=Oval>Oval<option value=Radiant>Radiant<option value=Pear>Pear<option value=Marquise>Marquise<option value=Heart>Heart<option value=Triangle>Triangle<option value=Trilliant>Trilliant<option value=Baguette>Baguette<option value=Trapezoid>Trapezoid<option value=Kite>Kite<option value="Rose Cut">Rose Cut<option value=Briolette>Briolette</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Clarity <span data-bs-placement=right data-bs-toggle=tooltip title="Please select clarity for diamond"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <select class=form-control name=attr_clarity[]><option disabled selected>Select<option value=FL>FL<option value=IF>IF<option value=VVS1>VVS1<option value=VVS2>VVS2<option value=VS1>VS1<option value=VS2>VS2<option value=SI1>SI1<option value=SI2>SI2<option value=SI3>SI3<option value=I1>I1<option value=I2>I2<option value=I3>I3</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Cut <span data-bs-placement=right data-bs-toggle=tooltip title="Please select cut for diamond"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <select class=form-control name=attr_cut[]><option disabled selected>Select<option value=Ideal>Ideal<option value=Excellent>Excellent<option value="Very Good">Very Good<option value=Good>Good<option value=Fair>Fair<option value=Poor>Poor</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Setting <span data-bs-placement=right data-bs-toggle=tooltip title="Please select setting for diamond"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <select class=form-control name=attr_setting[]><option disabled selected>Select<option value=Prong>Prong<option value=Bezel>Bezel<option value=Channel>Channel<option value=Pave>Pave<option value=Bar>Bar<option value=Cluster>Cluster<option value=Halo>Halo<option value=Tension>Tension<option value=Invisible>Invisible<option value=Bead>Bead<option value=Flush>Flush<option value=Cup>Cup<option value=Wire>Wire<option value=Button>Button<option value=Cage>Cage</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Total Diamond Count <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter total diamond count"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <input class="form-control attr_total_dimond"name=attr_total_diamond_count[] placeholder="Enter Total Diamond Count"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_wight_sec"><div class=form-sec><label for="">Total Diamond Weight <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter total diamond weight"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <input class="form-control total_weight"name=attr_total_diamond_wight[] placeholder="Enter Total Diamond Weight "></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec caret_sec><label for="">Diamond Price Per Carat <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter diamond price per carat"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <input class="form-control price_caret"name=attr_diamond_per_carat[] placeholder="Enter Diamond Price Per Carat"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_final_sec"><div class=form-sec><label for="">Final Diamond Price <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter final diamond price"><i aria-hidden=true class="fa fa-info-circle"></i></span></label> <input class="form-control final_total"name=attr_final_diamond_price[] placeholder="Enter Final Diamond Price"></div></div></div></div>';
            $("#details_sec").append(field);
        });
        $(document).on("click", ".remove_details", function() {
            $(this).closest('.diamond-deatils-sec').remove();
        });
        $(document).on("click", ".remove_deliver_sec", function() {
            $(this).closest('.delivery-field-sec').remove();
        });
        $(document).on("click", ".all_dimond_add", function() {
            var html = '<div class="diamond-deatils-sec mt-4"><div class="each-diamond-details row"><a><span class="remove_details"><i class="fa fa-times-circle"></i></span></a><div class="col-sm-12 col-xs-12 col-lg-12 col-md-12 col-xl-12 col-xxl-12 sub-heading"><h3>Diamonds Details</h3></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Type <span data-bs-placement=right data-bs-toggle=tooltip title="Please select type of diamond"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_type[]><option disabled selected>Select<option value=Natural>Natural<option value=Lab-Grown>Lab-Grown<option value=Lab-Grown>Cultured<option value=Lab-Grown>Saltwater<option value=Lab-Grown>Imitation</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Fancy Colour <span data-bs-placement=right data-bs-toggle=tooltip title="Please select fancy colour for diamond"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_fancy_color[]><option disabled selected>Select<option value=White>White<option value=Yellow>Yellow<option value=Pink>Pink<option value=Purple>Purple<option value=Blue>Blue<option value=Green>Green<option value=Orange>Orange<option value=Brown>Brown<option value=Black>Black</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 caret_sec"><div class=form-sec><label for="">Carat <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter carat for diamond"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control attr_carat"name=attr_diamond_caret[] step="0.0001" placeholder="Enter Carat" type=number></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Gemstone <span data-bs-placement=right data-bs-toggle=tooltip title="Please select gemstone for diamond"> <i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_gemstone[]><option disabled selected>Select<option value=Diamond>Diamond<option value="Sapphire Yellow">Sapphire Yellow<option value="Sapphire Blue">Sapphire Blue<option value="Sapphire Pink">Sapphire Pink<option value=Ruby>Ruby<option value=Emerald>Emerald<option value=Moissanite>Moissanite</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Shape <span data-bs-placement=right data-bs-toggle=tooltip title="Please select shape for diamond"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_shape[]><option disabled selected>Select<option value=Round>Round<option value=Princess>Princess<option value=Emerald>Emerald<option value="Sq. Emerald">Sq. Emerald<option value=Asscher>Asscher<option value=Cushion>Cushion<option value=Oval>Oval<option value=Radiant>Radiant<option value=Pear>Pear<option value=Marquise>Marquise<option value=Heart>Heart<option value=Triangle>Triangle<option value=Trilliant>Trilliant<option value=Baguette>Baguette<option value=Trapezoid>Trapezoid<option value=Kite>Kite<option value="Rose Cut">Rose Cut<option value=Briolette>Briolette</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Clarity <span data-bs-placement=right data-bs-toggle=tooltip title="Please select clarity for diamond"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_clarity[]><option disabled selected>Select<option value=FL>FL<option value=IF>IF<option value=VVS1>VVS1<option value=VVS2>VVS2<option value=VS1>VS1<option value=VS2>VS2<option value=SI1>SI1<option value=SI2>SI2<option value=SI3>SI3<option value=I1>I1<option value=I2>I2<option value=I3>I3</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Cut <span data-bs-placement=right data-bs-toggle=tooltip title="Please select cut for diamond"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_cut[]><option disabled selected>Select<option value=Ideal>Ideal<option value=Excellent>Excellent<option value="Very Good">Very Good<option value=Good>Good<option value=Fair>Fair<option value=Poor>Poor</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Setting <span data-bs-placement=right data-bs-toggle=tooltip title="Please select setting for diamond"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_setting[]><option disabled selected>Select<option value=Prong>Prong<option value=Bezel>Bezel<option value=Channel>Channel<option value=Pave>Pave<option value=Bar>Bar<option value=Cluster>Cluster<option value=Halo>Halo<option value=Tension>Tension<option value=Invisible>Invisible<option value=Bead>Bead<option value=Flush>Flush<option value=Cup>Cup<option value=Wire>Wire<option value=Button>Button<option value=Cage>Cage</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Total Diamond Count <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter total diamond count"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control attr_total_dimond"name=attr_total_diamond_count[] placeholder="Enter Total Diamond Count"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_wight_sec"><div class=form-sec><label for="">Total Diamond Weight <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter total diamond weight"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control total_weight"name=attr_total_diamond_wight[] placeholder="Enter Total Diamond Weight"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec caret_sec"><label for="">Diamond Price Per Carat <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter diamond price per carat"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control price_caret"name=attr_diamond_per_carat[] placeholder="Enter Diamond Price Per Carat"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_final_sec"><div class="form-sec diamonds_total_sec"><label for="">Final Diamond Price <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter final diamond price"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control final_total"name=attr_final_diamond_price[] placeholder="Enter Final Diamond Price"></div></div></div></div>';
            $('#all_diamond_div').append(html);
        });

        $(document).on("click", ".all_gemstone_add", function() {
            var gem_html = '<div class="diamond-deatils-sec mt-4"><div class="each-diamond-details row"><a><span class="remove_details"><i class="fa fa-times-circle"></i></span></a><div class="col-sm-12 col-xs-12 col-lg-12 col-md-12 col-xl-12 col-xxl-12 sub-heading"><h3>Gemstones Details</h3></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Type <span data-bs-placement=right data-bs-toggle=tooltip title="Please select type of gemstone"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_gemstone_type[]><option disabled selected>Select<option value=Natural>Natural<option value=Lab-Grown>Lab-Grown<option value=Lab-Grown>Cultured<option value=Lab-Grown>Saltwater<option value=Lab-Grown>Imitation</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Fancy Colour <span data-bs-placement=right data-bs-toggle=tooltip title="Please select fancy colour for gemstone"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_gemstone_fancy_color[]><option disabled selected>Select<option value=White>White<option value=Yellow>Yellow<option value=Pink>Pink<option value=Purple>Purple<option value=Blue>Blue<option value=Green>Green<option value=Orange>Orange<option value=Brown>Brown<option value=Black>Black</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 gemstone_caret_sec"><div class=form-sec><label for="">Carat <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter carat for gemstone"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control attr_gemstone_carat"name=attr_gemstone_caret[] step="0.0001" placeholder="Enter Total Caret" type=number></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Gemstone <span data-bs-placement=right data-bs-toggle=tooltip title="Please select gemstone "><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_gemstone_gem[]><option disabled selected>Select<option value=Diamond>Diamond<option value="Sapphire Yellow">Sapphire Yellow<option value="Sapphire Blue">Sapphire Blue<option value="Sapphire Pink">Sapphire Pink<option value=Ruby>Ruby<option value=Emerald>Emerald<option value=Moissanite>Moissanite</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Shape <span data-bs-placement=right data-bs-toggle=tooltip title="Please select shape for gemstone"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_gemstone_shape[]><option disabled selected>Select<option value=Round>Round<option value=Princess>Princess<option value=Emerald>Emerald<option value="Sq. Emerald">Sq. Emerald<option value=Asscher>Asscher<option value=Cushion>Cushion<option value=Oval>Oval<option value=Radiant>Radiant<option value=Pear>Pear<option value=Marquise>Marquise<option value=Heart>Heart<option value=Triangle>Triangle<option value=Trilliant>Trilliant<option value=Baguette>Baguette<option value=Trapezoid>Trapezoid<option value=Kite>Kite<option value="Rose Cut">Rose Cut<option value=Briolette>Briolette</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Clarity <span data-bs-placement=right data-bs-toggle=tooltip title="Please select clarity for gemstone"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_gemstone_clarity[]><option disabled selected>Select<option value=FL>FL<option value=IF>IF<option value=VVS1>VVS1<option value=VVS2>VVS2<option value=VS1>VS1<option value=VS2>VS2<option value=SI1>SI1<option value=SI2>SI2<option value=SI3>SI3<option value=I1>I1<option value=I2>I2<option value=I3>I3</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Cut <span data-bs-placement=right data-bs-toggle=tooltip title="Please select cut for gemstone"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_gemstone_cut[]><option disabled selected>Select<option value=Ideal>Ideal<option value=Excellent>Excellent<option value="Very Good">Very Good<option value=Good>Good<option value=Fair>Fair<option value=Poor>Poor</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Setting <span data-bs-placement=right data-bs-toggle=tooltip title="Please select setting for gemstone"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_gemstone_setting[]><option disabled selected>Select<option value=Prong>Prong<option value=Bezel>Bezel<option value=Channel>Channel<option value=Pave>Pave<option value=Bar>Bar<option value=Cluster>Cluster<option value=Halo>Halo<option value=Tension>Tension<option value=Invisible>Invisible<option value=Bead>Bead<option value=Flush>Flush<option value=Cup>Cup<option value=Wire>Wire<option value=Button>Button<option value=Cage>Cage</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Total Gemstone Count <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter total gemstone count"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control attr_total_gemstone"name=attr_gemstone_total_gem_count[] placeholder="Enter Total Gemstone Count"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_gemstone_wight_sec"><div class=form-sec><label for="">Total Gemstone Weight <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter total gemstone weight"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control total_gemstone_weight"name=attr_gemstone_total_wight[] placeholder="Enter Total Gemstone Weight "></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec gemstone_caret_sec"><label for="">Gemstone Price Per Carat <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter gemstone price per carat"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control gemstone_price_caret"name=gemstone_price_carat[] placeholder="Enter Gemstone Price Per Carat"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 gemstone_total_final_sec"><div class="form-sec gemstone_total_sec"><label for="">Final Gemstone Price <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter final gemstone price"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control gemstone_final_total"name=gemstone_final_total[] placeholder="Enter Final Gemstone Price"></div></div></div></div>';
            $('#all_gemstone_div').append(gem_html);
        });

        $(document).on("click", ".all_pearl_add", function() {
            var pearl_html = '<div class="diamond-deatils-sec mt-4"><div class="each-diamond-details row"><a><span class="remove_details"><i class="fa fa-times-circle"></i></span></a><div class="col-sm-12 col-xs-12 col-lg-12 col-md-12 col-xl-12 col-xxl-12 sub-heading"><h3>Pearls Details</h3></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Type <span data-bs-placement=right data-bs-toggle=tooltip title="Please select type of pearl"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_pearl_type[]><option disabled selected>Select<option value=Natural>Natural<option value=Lab-Grown>Lab-Grown<option value=Lab-Grown>Cultured<option value=Lab-Grown>Saltwater<option value=Lab-Grown>Imitation</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Fancy Colour <span data-bs-placement=right data-bs-toggle=tooltip title="Please select fancy colour for pearl"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_pearl_fancy_color[]><option disabled selected>Select<option value=White>White<option value=Yellow>Yellow<option value=Pink>Pink<option value=Purple>Purple<option value=Blue>Blue<option value=Green>Green<option value=Orange>Orange<option value=Brown>Brown<option value=Black>Black</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 pearl_caret_sec"><div class=form-sec><label for="">Carat <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter carat for pearl"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control attr_pearl_carat" step="0.0001" name=attr_pearl_caret[] placeholder="Enter Caret" type=number></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for=""> Pearl <span data-bs-placement=right data-bs-toggle=tooltip title="Please select pearl"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_pearl_gem[]><option disabled selected>Select<option value=Diamond>Diamond<option value="Sapphire Yellow">Sapphire Yellow<option value="Sapphire Blue">Sapphire Blue<option value="Sapphire Pink">Sapphire Pink<option value=Ruby>Ruby<option value=Emerald>Emerald<option value=Moissanite>Moissanite</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Shape <span data-bs-placement=right data-bs-toggle=tooltip title="Please select shape for pearl"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_pearl_shape[]><option disabled selected>Select<option value=Round>Round<option value=Princess>Princess<option value=Emerald>Emerald<option value="Sq. Emerald">Sq. Emerald<option value=Asscher>Asscher<option value=Cushion>Cushion<option value=Oval>Oval<option value=Radiant>Radiant<option value=Pear>Pear<option value=Marquise>Marquise<option value=Heart>Heart<option value=Triangle>Triangle<option value=Trilliant>Trilliant<option value=Baguette>Baguette<option value=Trapezoid>Trapezoid<option value=Kite>Kite<option value="Rose Cut">Rose Cut<option value=Briolette>Briolette</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Clarity <span data-bs-placement=right data-bs-toggle=tooltip title="Please select clarity for pearl"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_pearl_clarity[]><option disabled selected>Select<option value=FL>FL<option value=IF>IF<option value=VVS1>VVS1<option value=VVS2>VVS2<option value=VS1>VS1<option value=VS2>VS2<option value=SI1>SI1<option value=SI2>SI2<option value=SI3>SI3<option value=I1>I1<option value=I2>I2<option value=I3>I3</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Cut <span data-bs-placement=right data-bs-toggle=tooltip title="Please select cut for pearl"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_pearl_cut[]><option disabled selected>Select<option value=Ideal>Ideal<option value=Excellent>Excellent<option value="Very Good">Very Good<option value=Good>Good<option value=Fair>Fair<option value=Poor>Poor</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Setting <span data-bs-placement=right data-bs-toggle=tooltip title="Please select setting for pearl"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <select class=form-control name=attr_pearl_setting[]><option disabled selected>Select<option value=Prong>Prong<option value=Bezel>Bezel<option value=Channel>Channel<option value=Pave>Pave<option value=Bar>Bar<option value=Cluster>Cluster<option value=Halo>Halo<option value=Tension>Tension<option value=Invisible>Invisible<option value=Bead>Bead<option value=Flush>Flush<option value=Cup>Cup<option value=Wire>Wire<option value=Button>Button<option value=Cage>Cage</select></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class=form-sec><label for="">Total Pearl Count <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter total pearl count"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control attr_total_pearl"name=attr_pearl_total_gem_count[] placeholder="Enter Total Pearl Count"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 total_pearl_wight_sec"><div class=form-sec><label for="">Total Pearl Weight<span data-bs-placement=right data-bs-toggle=tooltip title="Please enter total pearl weight"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control total_pearl_weight"name=attr_pearl_total_wight[] placeholder="Enter Total Pearl Weight"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6"><div class="form-sec pearl_caret_sec"><label for="">Pearl Price Per Carat <span data-bs-placement=right data-bs-toggle=tooltip title="Please enter pearl price per carat"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control pearl_price_caret"name=pearl_price_carat[] placeholder="Enter Pearl Price Per Carat"></div></div><div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6 col-xxl-6 pearl_total_final_sec"><div class="form-sec pearl_total_sec"><label for="">Final Pearl Price<span data-bs-placement=right data-bs-toggle=tooltip title="Please enter final pearl price"><i class="fa fa-info-circle"aria-hidden=true></i></span></label> <input class="form-control pearl_final_total"name=pearl_final_total[] placeholder="Enter Final Pearl Price"></div></div></div></div>';
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
        var maxSizeKB = 500; // Maximum size in KB
        var maxWidth = 500;
        var maxHeight = 500;
        var existingImageCount = parseInt($('#existing_image_count').val());
        // Clear existing preview
        

        // Validate the number of selected files
        if (input.files.length + existingImageCount > maxImages) {
            alert('You can only select up to ' + maxImages + ' images.');
            // Reset the input field
            $(this).val('');
            return;
        }
        imagePreviewContainer.empty();
        // Preview the selected images
        

        for (var i = 0; i < input.files.length; i++) {
            var file = input.files[i];
            
            // Validate file size
            if (file.size > maxSizeKB * 1024) {
                alert('File size for each image should be up to ' + maxSizeKB + ' KB.');
                // Reset the input field
                $(this).val('');
                return;
            }

            var reader = new FileReader();

            reader.onload = function (e) {
                var img = new Image();
                img.src = e.target.result;

                // Validate image dimensions
                img.onload = function () {
                    // if (this.width > maxWidth || this.height > maxHeight) {
                    //     alert('Image dimensions should be ' + maxWidth + 'x' + maxHeight + ' pixels.');
                    //     // Reset the input field
                    //     $('#large_file').val('');
                    //     return;
                    // }

                    // Append an image tag to the preview container
                    imagePreviewContainer.append('<img class="img-fluid preview_image" src="' + e.target.result + '" style="margin-right:10px;">');
                };
            };

            reader.readAsDataURL(file);
        }
        for (var i = 0; i < existingImageCount; i++) {
            var imageUrl = $('#existing_image_' + i).val();
            var p_id = $('#existing_id_' + i).val();
            imagePreviewContainer.append('<div class="p_img_parent" style="position: relative;" data-id="' + p_id + '"><img class="img-fluid preview_image" src="' + imageUrl + '" style="margin-right:10px;"><a><span class="remove_icons" data-id="'+p_id+'"><i class="fa fa-times-circle"></i></span></a></div>');
        }
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

                if (fileType.startsWith('video/') && fileSize < 5 * 1024 * 1024) {
                    // It's a video file
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        previewImage.attr("src", "https://jewelxy.workdemo.in.net/images/videoicon.jpg");
                        videoPlayer.src = e.target.result; // Replace with the path to your play icon
                    };
                    previewImage.addClass('show_video');
                    reader.readAsDataURL(file);
                } else {
                    alert('Please select a valid video file with size less than 5 MB.');
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
    $('#p_metal_weight_unit').on('change', function () {
        total_metal_price_calculate();
    });

    function total_metal_price_calculate(){
        var weight = parseFloat($("#p_metal_weigth").val());
        var rate = parseFloat($('#metal_rate').val());
        var weight_unit = $('#p_metal_weight_unit').val();
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

    //$('#p_metal_weigth').keyup(function (){
    $(document).on("keyup change", "#p_metal_weigth", function() {
        total_metal_price_calculate();
    });
    // diamond calculations start
    $(document).on("keyup", ".attr_total_dimond", function() {
        var weight = $(this).val();
        var caret = $(this).closest('.each-diamond-details').find('.caret_sec .attr_carat').val();
        var result = weight * caret;
        var caret = $(this).closest('.each-diamond-details').find('.total_wight_sec .total_weight').val(result);
        // $('#total_metal_price').val(result);
    });
        $(document).on("keyup", ".price_caret, .attr_carat", function() {
        var price_caret = $(this).closest('.each-diamond-details').find('.caret_sec .price_caret').val();
        var caret = $(this).closest('.each-diamond-details').find('.caret_sec .attr_carat').val();
        var result = price_caret * caret;
        var caret = $(this).closest('.each-diamond-details').find('.total_final_sec .final_total').val(result);
        dimondgrandTotal = calculatediamondGrandTotal();
        $('#total_diamond_charge').val(dimondgrandTotal);
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
        $(document).on("keyup", ".gemstone_price_caret, .attr_gemstone_carat", function() {
            var gemstone_price_caret = $(this).closest('.each-diamond-details').find('.gemstone_caret_sec .gemstone_price_caret').val();
            var gem_caret = $(this).closest('.each-diamond-details').find('.gemstone_caret_sec .attr_gemstone_carat').val();
            var result = gemstone_price_caret * gem_caret;
            var total_caret = $(this).closest('.each-diamond-details').find('.gemstone_total_final_sec .gemstone_final_total').val(result);
            gemstoneTotal = calculategemstoneGrandTotal();
            $('#total_gemstone_charge').val(gemstoneTotal);
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
        $(document).on("keyup", ".pearl_price_caret, .attr_pearl_carat", function() {
            var pearl_price_caret = $(this).closest('.each-diamond-details').find('.pearl_caret_sec .pearl_price_caret').val();
            var pearl_caret = $(this).closest('.each-diamond-details').find('.pearl_caret_sec .attr_pearl_carat').val();
            var result = pearl_price_caret * pearl_caret;
            var total_caret = $(this).closest('.each-diamond-details').find('.pearl_total_final_sec .pearl_final_total').val(result);
            pearlTotal = calculatepearlGrandTotal();
            $('#total_pearl_charge').val(pearlTotal);
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
        }else{
            var discountdiamondAmount = dis_diamond_price;
            var discounteddiamondTotal = total_diamond_charge - dis_diamond_price;
        }

        // pearl charge
        var total_pearl_charge = parseFloat($('#total_pearl_charge').val()) || 0;
        var dis_pearl_price = parseFloat($('#dis_pearl_price').val()) || 0;
        var pearl_dis_check = $('input[name="pearl_dis"]:checked').val();

        if(pearl_dis_check == 'percentage')
        {
            var discountpearlAmount = (dis_pearl_price / 100) * total_pearl_charge;
            var discountedpearlTotal = total_pearl_charge - discountpearlAmount;
        }else{
            var discountpearlAmount = dis_pearl_price;
            var discountedpearlTotal = total_pearl_charge - dis_pearl_price;
        }

        // gemstone charge 
        var total_gemstone_charge = parseFloat($('#total_gemstone_charge').val()) || 0;
        var dis_gemstone_price = parseFloat($('#dis_gemstone_price').val()) || 0;
        var gemstone_dis_check = $('input[name="gemstone_dis"]:checked').val();

        if(gemstone_dis_check == 'percentage')
        {
            var discountgemstoneAmount = (dis_gemstone_price / 100) * total_gemstone_charge;
            var discountedgemstoneTotal = total_gemstone_charge - discountgemstoneAmount;
        }else{
            var discountgemstoneAmount = dis_gemstone_price;
            var discountedgemstoneTotal = total_gemstone_charge - dis_gemstone_price;
        }

        // other chage
        var total_other_charge = parseFloat($('#total_other_charge').val()) || 0;
        var dis_other_price = parseFloat($('#dis_other_price').val()) || 0;
        var other_dis_check = $('input[name="other_dis"]:checked').val();

        if(other_dis_check == 'percentage')
        {
            var discountotherAmount = (dis_other_price / 100) * total_other_charge;
            var discountedotherTotal = total_other_charge - discountotherAmount;
        }else{
            var discountotherAmount = dis_other_price;
            var discountedotherTotal = total_other_charge - dis_other_price;
        }

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
            var final_text_string = 'Fix Price(' + p_fix_price + ') - Discount(' + discountfixAmount + ') + Tax Charges(' + taxAmount + ')';
            $('#total_price_display').text(final_text_string);

        }else{
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
                $('#total_making_price').val(p_making_price.toFixed(4));
                var makingchargeAmount = p_making_price;
                // grand_total += makingchargeAmount;
            }
            var total_making_price = parseFloat($('#total_making_price').val()) || 0;
            var dis_making_price = parseFloat($('#dis_making_price').val()) || 0;
            var make_dis_check = $('input[name="make_dis"]:checked').val();

            if(make_dis_check == 'percentage')
            {
                var discountAmount = (dis_making_price / 100) * total_making_price;
                var discountedmakingTotal = total_making_price - discountAmount;
                grand_total += makingchargeAmount;
            }else{
                var discountAmount = dis_making_price;
                var discountedmakingTotal = total_making_price - dis_making_price;
                grand_total += makingchargeAmount;
            }
            var taxAmount = grand_total * (national_tax / 100);
            var f_above_amount = parseFloat($('#p_above_amount').val());
            
            $('#total_tax_charge').val(taxAmount.toFixed(4));
            
            
            if(grand_total > f_above_amount)
            {
                $('#tax_span_text').text('('+ national_tax + '% Tax on Total Amount '+ grand_total.toFixed(4) +')');
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
            var final_text_string = 'Total Metal Price(' + total_metal_price.toFixed(4) + ') + Total Making charges(' + makingchargeAmount.toFixed(4) + ') - Discount Making charges('+ discountAmount +') + Total Diamond Charges('+ discounteddiamondTotal.toFixed(4) +') - Discount Diamond Charge('+ discountdiamondAmount.toFixed(4) +') + Total Pearl Charges('+ discountedpearlTotal.toFixed(4) +') - Discount Pearl Charge('+ discountpearlAmount.toFixed(4) +') + Total Gemstone Charges('+ discountedgemstoneTotal.toFixed(4) +') - Discount Gemstone Charge('+ discountgemstoneAmount.toFixed(4) +') + Total Other Charges('+ discountedotherTotal.toFixed(4) +') - Discount Other Charge('+ discountotherAmount.toFixed(4) +') + Tax Charges ('+ taxAmount.toFixed(4) +')';
            $('#total_price_display').text(final_text_string);
        }

    }

    $(document).on("change keyup", "#p_national_tax, #p_above_amount, #p_fix_price, #fix_p_discount, .fix_dis, #total_other_charge, #dis_other_price, .other_dis, #dis_gemstone_price, .gemstone_dis, #dis_pearl_price, .pearl_dis, #dis_diamond_price, .diamond_dis, #dis_making_price, .make_dis", function() {
            calculate_tax_values();
    });
    $(document).on("change keyup", "#only_making_charges, .make_type", function() {
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
        }else{
            $('#total_metal_price').val(existing);
            total_metal_price_calculate();
            $('#p_fix_price').val('');
            $('#fix_p_discount').val('');
            $('#p_fix_price').attr('readonly',true);
            $('#fix_p_discount').attr('readonly',true);
            $('.Dynamic_price_data').show();
            $('.Fixed_price_data').hide();
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


});


