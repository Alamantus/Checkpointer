//var sortables = $(".goal");
var draggedItem;
var draggedItemOldValues;

$(".root_checkpoints, .childrenList").sortable({
    handle: '.handle',
    connectWith: ".childrenList",
    dropOnEmpty: true,
    start: function(event, ui) {
        draggedItem = ui.item;
        draggedItemOldValues = { index: ui.item.index(), parentID: ui.item.parent().attr("parentid") };
        //$(window).mousemove(moved);
    },
    stop: function(event, ui) {
        //$(window).unbind("mousemove", moved);
        
        // Update checkpoints sort and parent
        if (draggedItemOldValues.index != ui.item.index() ||
            draggedItemOldValues.parentID != ui.item.parent().attr("parentid"))
        {
            var success = true;
            $.get( "ajax/update_sort.php", "id=" + ui.item.attr("cpid") + "&sort=" + ui.item.index() + "&parent=" + ui.item.parent().attr("parentid"))
                .done(function (data) {
                    if (data == "success") {    //If the moved checkpoint updates successfully, do all of its siblings.
                        ui.item.siblings().each(function () {
                            $.get( "ajax/update_sort.php", "id=" + $(this).attr("cpid") + "&sort=" + $(this).index() + "&parent=" + $(this).parent().attr("parentid"))
                                .done(function (data) {
                                    if (data != "success") {
                                        success = false;
                                        alert(data);
                                        return;
                                    }
                                });
                            }).promise().done(function () {
                                if (success) {
                                    location.reload();  // Reload the page after being sure all siblings have finished successfully.
                                }
                            });
                    } else {
                        success = false;
                        alert(data);
                    }
                });
        }
    }
});

/*function moved(e) {
    //Retrieved from:
    //http://stackoverflow.com/questions/3298712/jquery-ui-sortable-determine-which-element-is-beneath-the-element-being-dragge
    
    //Dragged item's position++
    var d = {
        top: draggedItem.position().top,
        bottom: draggedItem.position().top + draggedItem.height(),
        left: draggedItem.position().left,
        right: draggedItem.position().left + draggedItem.width()
    };

    //Find sortable elements (li's) covered by draggedItem
    var hoveredOver = sortables.not(draggedItem).filter(function() {
        var t = $(this);
        var pos = t.position();

        //This li's position++
        var p = {
            top: pos.top,
            bottom: pos.top + t.height(),
            left: pos.left,
            right: pos.left + t.width()
        };

        //itc = intersect
        var itcTop      = p.top <= d.bottom;
        var itcBtm      = d.top <= p.bottom;
        var itcLeft     = p.left <= d.right;
        var itcRight    = d.left <= p.right;

        return itcTop && itcBtm && itcLeft && itcRight;
    });
    
    
};*/