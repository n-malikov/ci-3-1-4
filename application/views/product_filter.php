<html>
<head>
	<title>Пагинация с фильтрами</title>

	<script src="<?= base_url() ?>assets/js/jquery/jquery.min.js"></script>

	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/3.3.6/bootstrap.min.css">
	<script src="<?= base_url() ?>assets/bootstrap/3.3.6/bootstrap.min.js"></script>

	<!--	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>-->
	<!--	<link href = "--><?php //echo base_url(); ?><!--asset/jquery-ui.css" rel = "stylesheet">-->
	<!--	<link href="--><?php //echo base_url(); ?><!--asset/style.css" rel="stylesheet">-->

</head>
<body>


<div class="container">
	<div class="row">
		<div class="col-md-3">
			<br/>
			<br/>
			<br/>
			<div class="list-group">
				<h3>Цена</h3>
				<input type="hidden" id="hidden_minimum_price" value="0"/>
				<input type="hidden" id="hidden_maximum_price" value="65000"/>
				<p id="price_show">1000 - 65000</p>
				<div id="price_range"></div>
			</div>

			<div class="list-group">
				<h3>Бренд</h3>
				<?php
				foreach ($brand_data->result_array() as $row) {
					?>
					<div class="list-group-item checkbox">
						<label><input type="checkbox" class="common_selector brand"
									  value="<?php echo $row['product_brand']; ?>"> <?php echo $row['product_brand']; ?>
						</label>
					</div>
					<?php
				}
				?>
			</div>

		</div>

		<ul class="col-md-9">

			<br><br><br><br>

			<h4>Память</h4>
			<ul class="nav nav-tabs">

				<li class="active">
					<a class="active filterOnClick filterChecked" data-filter-name="storage" data-filter-value=""
					   data-toggle="tab" role="button">Все</a>
				</li>

				<?php
				foreach ($product_storage->result_array() as $row) {
					?>

					<li>
						<a class="filterOnClick"
						   data-filter-name="storage"
						   data-filter-value="<?=$row['product_storage']?>"
						   data-toggle="tab"
						   role="button"><?=$row['product_storage']?> GB</a>
					</li>

					<?php
				}
				?>

			</ul>

			<br>
			<h4>Оперативка</h4>

			<ul class="nav nav-tabs">
				<?php
				foreach ($ram_data->result_array() as $row) {
					?>

					<li>
						<a class="filterOnClick"
						   data-filter-name="ram"
						   data-filter-value="<?=$row['product_ram']?>"
						   data-toggle="tab"
						   role="button"><?=$row['product_ram']?> GB</a>
					</li>


					<?php
					/*
					 * <div class="list-group-item checkbox">
						<label><input type="checkbox" class="common_selector ram"
									  value="<?php echo $row['product_ram']; ?>"> <?php echo $row['product_ram']; ?> GB</label>
					</div>
					 */
				}
				?>
			</ul>


			<h2 align="center">Пагинация с фильтрами</h2>
			<br/>
			<div align="center" id="pagination_link">

			</div>
			<br/>
			<br/>
			<br/>
			<div class="row filter_data">

			</div>


		</div>
	</div>

</div>
<style>
	#loading {
		text-align: center;
		background: url('<?php echo base_url(); ?>assets/img/loader.gif') no-repeat center;
		height: 256px;
	}
</style>

<script>
	$(document).ready(function () {

		filter_data(1);

		function filter_data(page) {
			$('.filter_data').html('<div id="loading" style="" ></div>');
			var action = 'fetch_data';
			var minimum_price = $('#hidden_minimum_price').val();
			var maximum_price = $('#hidden_maximum_price').val();
			var brand = get_filter('brand');
			var ram = get_filter('ram');
			var storage = get_filter('storage');

			let filters = get_filters();

			$.ajax({
				url: "<?php echo base_url(); ?>product_filter/fetch_data/" + page,
				method: "POST",
				dataType: "JSON",
				data: {
					action: action,
					minimum_price: minimum_price,
					maximum_price: maximum_price,
					brand: brand,
					ram: ram,
					storage: storage,

					filters: filters
				},
				success: function (data) {
					//console.log(data);
					$('.filter_data').html(data.product_list);
					$('#pagination_link').html(data.pagination_link);
					console.log(data.pyatak)
				}
			})
		}

		function get_filter(class_name) {
			var filter = [];
			$('.' + class_name + ':checked').each(function () {
				filter.push($(this).val());
			});
			return filter;
		}

		$(document).on('click', '.pagination li a', function (event) {
			event.preventDefault();
			var page = $(this).data('ci-pagination-page');
			filter_data(page);
		});

		$('.common_selector').click(function () {
			filter_data(1);
		});

		// $('#price_range').slider({
		// 	range:true,
		// 	min:1000,
		// 	max:65000,
		// 	values:[1000,65000],
		// 	step:500,
		// 	stop:function(event, ui)
		// 	{
		// 		$('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
		// 		$('#hidden_minimum_price').val(ui.values[0]);
		// 		$('#hidden_maximum_price').val(ui.values[1]);
		// 		filter_data(1);
		// 	}
		//
		// });


		// ФИЛЬТРАЦИЯ ПО КЛИКУ
		$('.filterOnClick').on('click', function () {

			// првоерим, не выбрали ли текущее значение повторно
			if(this.classList.contains('filterChecked')) {
				return false;
			}

			let elem = $(this).data('filter-name'), // текущее имя фильтра
					val = $(this).data('filter-value'), // выбранное значение
					elements = $('[data-filter-name=' + elem +']');

			// удаляем у текущего фильтра все значения
			Array.from(elements).forEach(item => {
				item.classList.remove('filterChecked')
			})

			// добавляем значение
			$(this).addClass('filterChecked');

			// запускаем ajax выгрузку
			filter_data(1);

			//console.log(val);
		});

		// СОБИРАЕМ ВСЕ ЗНАЧНИЯ ЗАПОЛНЕННЫХ ФИЛЬТРОВ
		function get_filters() {

			let filters = {};

			$( '.filterChecked' ).each(function( i ) {
				filters[$(this).data('filter-name')] = $(this).data('filter-value');
			});

			return filters;

		}


	});
</script>

</body>
</html>
