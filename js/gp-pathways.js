jQuery('#expand-9th').click(function(){
    var border = jQuery(this);
    border.css('border-bottom','none');
    var text = jQuery('#expand-9th span');
    jQuery('#grade-9th-group').slideToggle('800', function(){;
        if (jQuery(this).is(':visible')) {
            text.text('Collapse Section');
        }
        else {
            text.text('Expand Section');
            border.css('border-bottom','1px solid #d6d5d5');
        };
    });
});

jQuery('#expand-10th').closest('h3').click(function(){
    var border = jQuery(this);
    border.css('border-bottom','none');
    var text = jQuery('#expand-10th span');
    jQuery('#grade-10th-group').slideToggle('800', function(){;
        if (jQuery(this).is(':visible')) {
            text.text('Collapse Section');
        }
        else {
            text.text('Expand Section');
            border.css('border-bottom','1px solid #d6d5d5');
        };
    });
});

jQuery('#expand-11th').click(function(){
    var border = jQuery(this);
    border.css('border-bottom','none');
    var text = jQuery('#expand-11th span');
    jQuery('#grade-11th-group').slideToggle('800', function(){;
        if (jQuery(this).is(':visible')) {
            text.text('Collapse Section');
        }
        else {
            text.text('Expand Section');
            border.css('border-bottom','1px solid #d6d5d5');
        };
    });
});

jQuery('#expand-12th').click(function(){
    var border = jQuery(this);
    border.css('border-bottom','none');
    var text = jQuery('#expand-12th span');
    jQuery('#grade-12th-group').slideToggle('800', function(){;
        if (jQuery(this).is(':visible')) {
            text.text('Collapse Section');
        }
        else {
            text.text('Expand Section');
            border.css('border-bottom','1px solid #d6d5d5');
        };
    });
});

jQuery('#expand-early-college').click(function(){
    var border = jQuery(this);
    border.css('border-bottom','none');
    var text = jQuery('#expand-early-college span');
    jQuery('#early-college-group').slideToggle('600', function(){;
        if (jQuery(this).is(':visible')) {
            text.text('Collapse Section');
        }
        else {
            text.text('Expand Section');
            border.css('border-bottom','1px solid #d6d5d5');
        };
    });
});

jQuery('#expand-professional-learning').click(function(){
    var border = jQuery(this);
    border.css('border-bottom','none');
    var text = jQuery('#expand-professional-learning span');
    jQuery('#professional-learning-group').slideToggle('600', function(){;
        if (jQuery(this).is(':visible')) {
            text.text('Collapse Section');
        }
        else {
            text.text('Expand Section');
            border.css('border-bottom','1px solid #d6d5d5');
        };
    });
});

jQuery('#expand-industry-credentials').click(function(){
    var border = jQuery(this);
    border.css('border-bottom','none');
    var text = jQuery('#expand-industry-credentials span');
    jQuery('#industry-credentials-group').slideToggle('600', function(){;
        if (jQuery(this).is(':visible')) {
            text.text('Collapse Section');
        }
        else {
            text.text('Expand Section');
            border.css('border-bottom','1px solid #d6d5d5');
        };
    });
});

jQuery('#expand-icc-certificates-programs').click(function(){
    var border = jQuery(this);
    border.css('border-bottom','none');
    var text = jQuery('#expand-icc-certificates-programs span');
    jQuery('#icc-certificates-programs-group').slideToggle('600', function(){;
        if (jQuery(this).is(':visible')) {
            text.text('Collapse Section');
        }
        else {
            text.text('Expand Section');
            border.css('border-bottom','1px solid #d6d5d5');
        };
    });
});




jQuery("#9th-add-CFIS").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#9th-CFIS").append('<div><label>New CFIS</label><input name="9th_CFIS_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);

}); // END OF ADD-CLASS CLICK

jQuery("#9th-add-science").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#9th-science").append('<div><label>New science class</label><input name="9th_science_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#9th-add-social").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#9th-social").append('<div><label>New social studies class</label><input name="9th_social_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#9th-add-math").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#9th-math").append('<div><label>New math class</label><input name="9th_math_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#9th-add-english").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#9th-english").append('<div><label>New english class</label><input name="9th_english_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#10th-add-CFIS").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#10th-CFIS").append('<div><label>New CFIS</label><input name="10th_CFIS_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#10th-add-science").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#10th-science").append('<div><label>New science class</label><input name="10th_science_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#10th-add-social").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#10th-social").append('<div><label>New social studies class</label><input name="10th_social_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#10th-add-math").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#10th-math").append('<div><label>New math class</label><input name="10th_math_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#10th-add-english").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#10th-english").append('<div><label>New english class</label><input name="10th_english_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#11th-add-CFIS").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#11th-CFIS").append('<div><label>New CFIS</label><input name="11th_CFIS_['+(count)+']"></div>');

}); // END OF ADD-CLASS CLICK

jQuery("#11th-add-science").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#11th-science").append('<div><label>New science class</label><input name="11th_science_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#11th-add-social").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#11th-social").append('<div><label>New social studies class</label><input name="11th_social_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#11th-add-math").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#11th-math").append('<div><label>New math class</label><input name="11th_math_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#11th-add-english").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#11th-english").append('<div><label>New english class</label><input name="11th_english_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#12th-add-CFIS").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#12th-CFIS").append('<div><label>New CFIS</label><input name="12th_CFIS_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#12th-add-science").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#12th-science").append('<div><label>New science class</label><input name="12th_science_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#12th-add-social").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#12th-social").append('<div><label>New social studies class</label><input name="12th_social_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#12th-add-math").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#12th-math").append('<div><label>New math class</label><input name="12th_math_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#12th-add-english").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#12th-english").append('<div><label>New english class</label><input name="12th_english_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#early-college-add").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#early-college").append('<div><label>New early college class</label><input name="early_college_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#professional-learning-add").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#professional-learning").append('<div><label>New professional learning</label><input name="professional_learning_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#industry-creds-add").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#industry-credentials").append('<div><label>New industry credential</label><input name="industry_creds_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#icc-certificate-add").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#icc-certificates").append('<div><label>New ICC certificate title</label><input name="icc_certificate_title_['+(count)+']"><br><label>New ICC certificate link</label><input name="icc_certificate_url_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK

jQuery("#icc-program-add").click(function () {
    var count = jQuery(this).attr("data-count");
    parseInt(count);
    count++;
    jQuery("#icc-programs").append('<div><label>New ICC program title</label><input name="icc_program_title_['+(count)+']"><br><label>New ICC program link</label><input name="icc_program_url_['+(count)+']"></div>');
    jQuery(this).attr("data-count", count);
}); // END OF ADD-CLASS CLICK



////////////////////////////////////////////////////
//----jQuery AJAX click delete button function----//
////////////////////////////////////////////////////

jQuery('.gp-delete-meta').click(function (e) {
    
    e.preventDefault(); // prevent default event on button
    
    var id = jQuery(this).attr("data-keys"); // Get button attribute data-id contains meta_keys to delete
    
    var post_id = jQuery(this).attr("data-post"); // Get button attribute data-post contains post_id
    
    var msg = confirm('You are about to permanently delete these items from your site. This action cannot be undone "Cancel" to stop, "OK" to delete.'); // Alert message to confirm delete
    
    if(msg){
        jQuery.ajax({
            url: ajaxurl, // localized ajax url from wordpress
            type: 'POST',
            data : {
                action : 'ajax_action', // function in php to run 
                delete_id: id, // ajax post meta_keys retrieved by action function php
                id_post: post_id // ajax post post_id retrieved by action function php
            },
            success: function(data){
                if(data=='finished'){alert('Successfully deleted, The page will Reload, No need to update the page.');location.reload(true);};
            },
            error: function(jqXHR){ alert(jqXHR.status +" "+jqXHR.statusText) + "<br> Reload the Page, Try Again"; } // alert error 
            });
        jQuery(this).closest('div').css('background-color','red'); // jQuery .closest div to button backbround red
        
        
        jQuery(this).closest('div').fadeOut(800, function(){ // jQuery fadeout closest div to button
            jQuery(this).closest('div').remove(); // remove div after 1.3sec fadout
        });
    }
});