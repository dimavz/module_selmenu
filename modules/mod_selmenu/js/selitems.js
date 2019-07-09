jQuery(function($) {

    var module_ids = $("div.hidden_module_id");

    module_ids.each(function()
    {
        var mod_id = $(this).data('id');
        console.log(mod_id);

        var listItems = $('#list_'+mod_id).data('list');
        console.log(listItems);

        $('#selmenu_'+mod_id+'_level_1').change(function()
        {
            var selmenu_id = $('#selmenu_'+mod_id+'_level_1 option:selected').data('menuid');
            // console.log(seloption);
            // console.log('Tect обработчика');
            $('#selmenu_'+mod_id+'_level_2').removeAttr("disabled");
            $.each(listItems, function (index,value) {
                // console.log('Индекс: '+index+'; Значение: ' + value);
                if(index=='level_2'){
                    $('#selmenu_'+mod_id+'_level_2').empty();
                    $.each(value,function (ind) {
                        // console.log('Индекс: '+ind+'; Значение: ' + this.text);
                        if(this.selected == 1 && this.published == 1 && this.menu_id == selmenu_id ){
                            $('#selmenu_'+mod_id+'_level_2').append('<option data-menuid ='+ this.menu_id +' value='+ this.value +'>'+ this.text +'</option>');
                        }
                        // $('#selmenu_'+mod_id+'_level_2').append('<option data-menuid ='+ this.menu_id +' value='+ this.value +'>'+ this.text +'</option>');

                    });
                }
            });
        });
    });
});