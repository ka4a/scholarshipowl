<script type="text/javascript">
function countWords(){
	var s = $('#{{$count_field_name}}').val();
	s = s.replace(/(^\s*)|(\s*$)/gi,"");
	s = s.replace(/[ ]{2,}/gi," ");
	s = s.replace(/\n /,"\n");
	$('#{{$wordcount_field}}').text(s.split(' ').length);
};
</script>
