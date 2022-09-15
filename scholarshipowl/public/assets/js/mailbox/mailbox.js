// Mailbox sorter
$('document').ready(function(){
$('#mailbox').jplist({
	itemsBox: '.list'
	,itemPath: '.list-item'
	,panelPath: '.jplist-panel'
});
});

$("button.jplist-drop-down[data-order]").click(function () {
	$(this).attr('data-order', ($(this).attr('data-order') === 'asc' ? 'desc' : 'asc'))
});