/**
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
jQuery(function($)
{
    var treeselectmenu = $('div#treeselectmenu').html();

    $('.mytreeselect li').each(function()
    {
        $li = $(this);
        $div = $li.find('div.tree-item:first');

        // Add icons
        $li.prepend('<span class="pull-left icon-"></span>');

        // Append clearfix
        $div.after('<div class="clearfix"></div>');

        if ($li.find('ul.tree-sub').length) {
            // Add classes to Expand/Collapse icons
            $li.find('span.icon-').addClass('treeselect-toggle icon-minus');

            // Append drop down menu in nodes
            $div.find('label:first').after(treeselectmenu);

            if (!$li.find('ul.tree-sub ul.tree-sub').length) {
                $li.find('div.treeselect-menu-expand').remove();
            }
        }
    });

    // Takes care of the Expand/Collapse of a node
    $('span.treeselect-toggle').click(function()
    {
        $i = $(this);

        // Take care of parent UL
        if ($i.parent().find('ul.tree-sub').is(':visible')) {
            $i.removeClass('icon-minus').addClass('icon-plus');
            $i.parent().find('ul.tree-sub').hide();
            $i.parent().find('ul.tree-sub i.treeselect-toggle').removeClass('icon-minus').addClass('icon-plus');
        } else {
            $i.removeClass('icon-plus').addClass('icon-minus');
            $i.parent().find('ul.tree-sub').show();
            $i.parent().find('ul.tree-sub i.treeselect-toggle').removeClass('icon-plus').addClass('icon-minus');
        }
    });

    // Takes care of the filtering
    $('#treeselectfilter').keyup(function()
    {
        var text = $(this).val().toLowerCase();
        var hidden = 0;
        $("#noresultsfound").hide();
        var $list_elements = $('.mytreeselect li');
        $list_elements.each(function()
        {
            if ($(this).text().toLowerCase().indexOf(text) == -1) {
                $(this).hide();
                hidden++;
            }
            else {
                $(this).show();
            }
        });
        if(hidden == $list_elements.length)
        {
            $("#noresultsfound").show();
        }
    });

    // Checks all checkboxes the tree
    $('#mytreeCheckAll').click(function()
    {
        $('.mytreeselect input').attr('checked', 'checked');
    });

    // Unchecks all checkboxes the tree
    $('#mytreeUncheckAll').click(function()
    {
        $('.mytreeselect input').attr('checked', false);
    });

    // Checks all checkboxes the tree
    $('#mytreeExpandAll').click(function()
    {
        $('ul.mytreeselect ul.tree-sub').show();
        $('ul.mytreeselect span.treeselect-toggle').removeClass('icon-plus').addClass('icon-minus');
    });

    // Unchecks all checkboxes the tree
    $('#mytreeCollapseAll').click(function()
    {
        $('ul.mytreeselect ul.tree-sub').hide();
        $('ul.mytreeselect span.treeselect-toggle').removeClass('icon-minus').addClass('icon-plus');
    });
    // Take care of children check/uncheck all
    $('a.checkall').click(function()
    {
        $(this).parents().eq(5).find('ul.tree-sub input').attr('checked', 'checked');
    });
    $('a.uncheckall').click(function()
    {
        $(this).parents().eq(5).find('ul.tree-sub input').attr('checked', false);
    });

    // Take care of children toggle all
    $('a.expandall').click(function()
    {
        var $parent = $(this).parents().eq(6);
        $parent.find('ul.tree-sub').show();
        $parent.find('ul.tree-sub i.treeselect-toggle').removeClass('icon-plus').addClass('icon-minus');
    });
    $('a.collapseall').click(function()
    {
        var $parent = $(this).parents().eq(6);
        $parent.find('li ul.tree-sub').hide();
        $parent.find('li i.treeselect-toggle').removeClass('icon-minus').addClass('icon-plus');
    });
});
