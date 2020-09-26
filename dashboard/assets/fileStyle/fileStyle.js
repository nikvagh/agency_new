$(function () {
    $('.file-box input[type="file"]').change(function(e){
        multiple = false;
        var attr = $(this).attr('multiple');
        if(typeof attr !== "undefined" && typeof attr !== undefined && attr !== false) {
            multiple = true;
        }

        if(multiple == false){
            // console.log('111');
            console.log(e.target.files);
            $(this).siblings('.name-box').html(e.target.files[0].name);
        }else{
            // console.log('222');
            file = [];
            $.each(e.target.files, function( i, val ) {
                file.push(val.name);
            });

            allfile = file.join(', ');
            $(this).siblings('.name-box').html(allfile);
        }
    });
});