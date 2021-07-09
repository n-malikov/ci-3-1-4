<?php

class ajax_pagination_model extends CI_Model
{

	function count_all()
	{
		$this->load->database();
		$query = $this->db->get("country");
		return $query->num_rows();
	}

	function fetch_details($limit, $start)
	{
		$this->load->database();
		$output = '';
		$this->db->select("*");
		$this->db->from("country");
		$this->db->order_by("name", "ASC");
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		$output .= '
  <table class="table table-bordered">
   <tr>
    <th>ID</th>
    <th>Название страны</th>
   </tr>
  ';
		foreach($query->result() as $row)
		{
			$output .= '
   <tr>
    <td>'.$row->id.'</td>
    <td>'.$row->name.'</td>
   </tr>
   ';
		}
		$output .= '</table>';
		return $output;
	}
}
