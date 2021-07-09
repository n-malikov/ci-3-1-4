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
	$(document).ready(function(){

		function load_country_data(page)
		{
			$.ajax({
				url:"<?php echo base_url(); ?>ajax_pagination/pagination/"+page,
				method:"GET",
				dataType:"json",
				success:function(data)
				{
					console.log(data);
					$('#country_table').html(data.country_table);
					$('#pagination_link').html(data.pagination_link);
				}
			});
		}

		load_country_data(1);

		$(document).on("click", ".pagination li a", function(event){
			event.preventDefault();
			var page = $(this).data("ci-pagination-page");
			load_country_data(page);
		});

	});
</script>

</body>
</html>
