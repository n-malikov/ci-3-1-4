<?php

class Product_filter_model extends CI_Model
{
	function fetch_filter_type($type)
	{
		$this->db->distinct();
		$this->db->select($type);
		$this->db->from('product');
		$this->db->where('product_status', '1');
		return $this->db->get();
	}

	function make_query($minimum_price, $maximum_price, $brand, $ram, $filters)
	{
		$query = "
  SELECT * FROM product 
  WHERE product_status = '1' 
  ";

		if(isset($minimum_price, $maximum_price) && !empty($minimum_price) &&  !empty($maximum_price))
		{
			$query .= "
    AND product_price BETWEEN '".$minimum_price."' AND '".$maximum_price."'
   ";
		}

		if(isset($brand))
		{
			$brand_filter = implode("','", $brand);
			$query .= "
    AND product_brand IN('".$brand_filter."')
   ";
		}

		if(isset($ram))
		{
			$ram_filter = implode("','", $ram);
			$query .= "
    AND product_ram IN('".$ram_filter."')
   ";
		}

		//поиск по памяти
		if(!empty($filters['storage']))
		{
			$query .= " AND product_storage = '" . $filters['storage'] . "' ";
		}

		return $query;
	}

	function count_all($minimum_price, $maximum_price, $brand, $ram, $filters)
	{
		$query = $this->make_query($minimum_price, $maximum_price, $brand, $ram, $filters);
		$data = $this->db->query($query);
		return $data->num_rows();
	}

	function fetch_data($limit, $start, $minimum_price, $maximum_price, $brand, $ram, $filters)
	{
		$query = $this->make_query($minimum_price, $maximum_price, $brand, $ram, $filters);

		$query .= ' LIMIT '.$start.', ' . $limit;

		$data = $this->db->query($query);

		$output = '';
		if($data->num_rows() > 0)
		{
			foreach($data->result_array() as $row)
			{
				$output .= '
					<div class="col-sm-4 col-lg-3 col-md-3">
						<div style="border:1px solid #ccc; border-radius:5px; padding:16px; margin-bottom:16px; height:450px;">
							<img src="/assets/img/1.jpg" alt="" class="img-responsive" >
					    	<p align="center"><strong><a href="#">'. $row['product_name'] .'</a></strong></p>
					    	<h4 style="text-align:center;" class="text-danger" >'. $row['product_price'] .'</h4>
					    	<p>Камера : '. $row['product_camera'].' MP<br />
								Брэнд : '. $row['product_brand'] .' <br />
								Оперативка : '. $row['product_ram'] .' GB<br />
								Память : '. $row['product_storage'] .' GB </p>
						</div>
					</div>
			    ';


			}
		}
		else
		{
			$output = '<h3>No Data Found</h3>';
		}


		return $output;
	}
}
