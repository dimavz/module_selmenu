jQuery(function ($) {

    var modules_ids = $("div.hidden_module_id");

    modules_ids.each(function () {
        var mod_id = $(this).data('id');
        // console.log(mod_id);

        var listItems = $('#list_' + mod_id).data('list');
        // console.log(listItems);

        // var ListItemsLevel2 = [];
        // var ListItemsLevel3 = [];
        //Заполняем select при изменении 1-го уровня
        $('#selmenu_' + mod_id + '_level_1').change(function () {
            //Получаем id выбранного меню
            var selmenu_id = $('#selmenu_' + mod_id + '_level_1 option:selected').data('menuid');

            if (!Number(selmenu_id)){
                $('#selmenu_' + mod_id + '_level_2').empty();
                $('#selmenu_' + mod_id + '_level_2').attr('disabled','disabled');
                $('#selmenu_' + mod_id + '_level_2').append('<option>' + Joomla.JText._('MOD_SELMENU_SELECTOR') +'</option>');
                $('#selmenu_' + mod_id + '_level_3').empty();
                $('#selmenu_' + mod_id + '_level_3').attr('disabled','disabled');
                $('#selmenu_' + mod_id + '_level_3').append('<option>' + Joomla.JText._('MOD_SELMENU_SELECTOR') +'</option>');
            }

            var listItemsLevel2 = [];
            var listItemsLevel3 = [];
            //Перебираем список элементов меню
            $.each(listItems, function (index, value) {
                // console.log(value);
                //Создаём список пунктов меню 2 уровня для выбранного пункта меню
                if (index == 'level_2') {
                    $.each(value, function (ind, val) {
                        if (this.menu_id == selmenu_id) {
                            listItemsLevel2.push(val);
                        }
                    });
                }
                //Создаём список пунктов меню 3 уровня для выбранного пункта меню
                if (index == 'level_3') {
                    $.each(value, function (ind, val) {
                        if (this.menu_id == selmenu_id) {
                            listItemsLevel3.push(val);
                        }
                    });
                }
            });
            // console.log(listItemsLevel2);
            // console.log(listItemsLevel3);

            //Создаём массивы с видимыми пунктами меню
            //Для 2-го уровня
            var viewListItemsLevel2 = [];
            //Для 3-го уровня
            var viewListItemsLevel3 = [];

            $.each(listItemsLevel2, function (ind2, item2) {
                if (item2.selected && item2.published) {
                    viewListItemsLevel2.push(item2);
                }
                // console.log(item2);
                $.each(listItemsLevel3, function (ind3, item3) {

                    //Проверяем не добавлен ли уже элемент в массив viewListItemsLevel2
                    var fl = 0; // Флаг для проверки
                    $.each(viewListItemsLevel2, function (i, itm) {
                        // console.log('itm =');
                        // console.log(itm.value);
                        // console.log(item3.parent_id);
                        // console.log('item3 =');
                        // console.log(item3);
                        if (itm.value == item3.parent_id) {
                            fl = 1;
                        }
                    });
                    if (fl) {
                        return false;
                    }
                    // console.log(item3.value);
                    if (item3.selected && item3.published && item3.parent_id == item2.value) {
                        viewListItemsLevel2.push(item2);
                    }
                });
            });

            // Формируем видимые пункты меню 3-го уровня
            $.each(listItemsLevel3, function (ind3, item3) {
                if (item3.selected && item3.published) {
                    viewListItemsLevel3.push(item3);
                }
            });
            // console.log(viewListItemsLevel2);
            // console.log(viewListItemsLevel3);

            //Заполняем select 2-го уровня
            if(viewListItemsLevel2.length){
                $('#selmenu_' + mod_id + '_level_2').removeAttr("disabled");
                $('#selmenu_' + mod_id + '_level_2').empty();
                //Если пунктов больше 1
                if(viewListItemsLevel2.length >1){
                    $('#selmenu_' + mod_id + '_level_2').append('<option>' + Joomla.JText._('MOD_SELMENU_SELECTOR') +'</option>');
                }
                $.each(viewListItemsLevel2, function (ind2, item2) {
                    $('#selmenu_' + mod_id + '_level_2').append('<option data-menuid ='+ item2.menu_id +' value='+ item2.value +' link="'+item2.link+'">'+ item2.text +'</option>');
                });

                // Выбираем пункты 3-го уровня
                // Если список содержит элементы
                if(viewListItemsLevel3.length){
                    //Получаем id выбранного пункта
                    var itemId = $('#selmenu_' + mod_id + '_level_2 option:selected').attr('value');
                    // console.log(itemId);
                    if(Number(itemId)){ // Если не пустой
                        // Заполняем селект 3-го уровня
                        $('#selmenu_' + mod_id + '_level_3').removeAttr("disabled");
                        $('#selmenu_' + mod_id + '_level_3').empty();
                        $.each(viewListItemsLevel3, function (ind3, item3) {
                            if(item3.parent_id == itemId){
                                $('#selmenu_' + mod_id + '_level_3').append('<option data-menuid ='+ item3.menu_id +' value='+ item3.value +' link="'+item3.link+'">'+ item3.text +'</option>');
                            }
                        });

                    }
                    else{
                        $('#selmenu_' + mod_id + '_level_3').empty();
                        $('#selmenu_' + mod_id + '_level_3').attr('disabled','disabled');
                        $('#selmenu_' + mod_id + '_level_3').append('<option>' + Joomla.JText._('MOD_SELMENU_SELECTOR') +'</option>');
                    }
                }
                else{ // Если список 3-го уровня пуст
                    $('#selmenu_' + mod_id + '_level_3').empty();
                    $('#selmenu_' + mod_id + '_level_3').attr('disabled','disabled');
                    $('#selmenu_' + mod_id + '_level_3').append('<option>' + Joomla.JText._('MOD_SELMENU_SELECTOR') +'</option>');
                }
            }
        });

        //Заполняем select 3-го уровня при выборе пункта 2-го селекта
        $('#selmenu_' + mod_id + '_level_2').change(function () {
            //Получаем ID выбранного элемента
            var id = $('#selmenu_' + mod_id + '_level_2 option:selected').attr('value');
            if (!Number(id)){
                $('#selmenu_' + mod_id + '_level_3').empty();
                $('#selmenu_' + mod_id + '_level_3').attr('disabled','disabled');
                $('#selmenu_' + mod_id + '_level_3').append('<option>' + Joomla.JText._('MOD_SELMENU_SELECTOR') +'</option>');
            }

            var listItemsLevel3 = [];
            //Перебираем список элементов меню
            $.each(listItems, function (index, value) {
                // console.log(value);
                //Создаём список пунктов меню 3 уровня для выбранного пункта меню
                if (index == 'level_3') {
                    $.each(value, function (ind, val) {
                        // console.log(val);
                        if (val.parent_id == id && val.published && val.selected) {
                            listItemsLevel3.push(val);
                        }
                    });
                }
            });
            if(listItemsLevel3.length){
                $('#selmenu_' + mod_id + '_level_3').empty();
                if(listItemsLevel3.length > 1){
                    $('#selmenu_' + mod_id + '_level_3').append('<option>' + Joomla.JText._('MOD_SELMENU_SELECTOR') +'</option>');
                }
                $('#selmenu_' + mod_id + '_level_3').removeAttr("disabled");
                $.each(listItemsLevel3, function (ind, item3) {
                    $('#selmenu_' + mod_id + '_level_3').append('<option data-menuid ='+ item3.menu_id +' value='+ item3.value +' link="'+item3.link+'">'+ item3.text +'</option>');
                });
            }
            else{ //Список пуст
                $('#selmenu_' + mod_id + '_level_3').empty();
                $('#selmenu_' + mod_id + '_level_3').attr('disabled','disabled');
                $('#selmenu_' + mod_id + '_level_3').append('<option>' + Joomla.JText._('MOD_SELMENU_SELECTOR') +'</option>');
            }
            // console.log(id);
            // console.log(listItemsLevel3);
        });

        //Действия при клике на кнопке Перейти по ссылке
        $('#button_selmenu_'+mod_id).click(function () {
            // console.log('Тест кнопки');
            var fl = 1;
            var link = $('#selmenu_'+ mod_id+'_level_3 option:selected').attr('link');
            if(link === undefined || link=='' ){
                 link = $('#selmenu_'+ mod_id+'_level_2 option:selected').attr('link');
                if(link === undefined || link==''){
                    link = $('#selmenu_'+ mod_id+'_level_1 option:selected').attr('link');
                    if(link === undefined || link==''){
                        alert('' + Joomla.JText._('MOD_SELMENU_SELECTOR') +'');
                        fl = 0;
                    }
                }
            }
            // console.log(link);
            if(fl)
            {
                window.location.href=link;
            }
        });
    });

});