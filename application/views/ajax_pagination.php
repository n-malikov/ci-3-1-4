<html>
<head>
	<title>Ajax пагинация</title>

	<script src="<?=base_url()?>assets/js/jquery/jquery.min.js"></script>

	<link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/3.3.6/bootstrap.min.css">
	<script src="<?=base_url()?>assets/bootstrap/3.3.6/bootstrap.min.js"></script>

</head>
<body>

<div class="container box">
	<h3 align="center">Ajax пагинация</h3>

	<div align="right" id="pagination_link"></div>

	<div class="table-responsive" id="country_table"></div>

</div>

<script>
	$(document).ready(function ()) {

		$.ajax({
			url:"<?php echo base_url(); ?>ajax_pagination/pagination/"+page,
			method:"GET",
			dataType:"json",
			success:function(data)
			{
				$('#country_table').html(data.country_table);
				$('#pagination_link').html(data.pagination_link);
			}
		});

	}
</script>

</body>
</html>
