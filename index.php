<?php
require_once 'core.php';
title('Dashboard');
top();
?>
<div class="row">
	<div class="col-lg-12">
		<div id="toolbar">
			<button id="create" class="btn btn-primary">
				<i class="glyphicon glyphicon-plus"></i> Create
			</button>
		</div>
		<table id="data"></table>
	</div>
</div>
<script>
$app.require_authed();
$('#data').bootstrapTable({
	url: ctx + '/deck/api.php',
	cache: false,
	striped: true,
	toolbar: '#toolbar',
	sidePagination: 'server',
	sortName: 'name',
	search: true,
	ajaxOptions: {
		headers: {
			Bearer: $app.getData().token
		}
	},
	pagination: true,
	columns: [
		{
			field: 'name',
			title: 'Name',
			sortable: true,
			formatter: (obj,row)=>{
				return '<a href="'+ctx+'/card/view.php?id='+row.id+'">'+obj+'</a>';
			}
		},
		{
			field: 'quantity',
			title: 'Cards',
			sortable: true,
		},		
		{
			field: 'create_on',
			title: 'Create',
			sortable: true,
		},
		{
			field: 'learn_on',
			title: 'Learn',
			sortable: true,
		}
	]

});
</script>
<?=bottom()?>
