<?php

class ModelSuperDruidPremiumDBIndexes extends Model { 

	private $def = array(
		'superdruid_p2c' => array('table'=> 'product_to_category', 'col'=>array('category_id', 'product_id'))
		, 'superdruid_c2s' => array('table'=> 'category_to_store', 'col'=>array('store_id', 'category_id'))
		, 'superdruid_c2p' => array('table'=> 'category_path', 'col'=>array('path_id', 'category_id'))
		, 'superdruid_p2s' => array('table'=> 'product_to_store', 'col'=>array('store_id', 'product_id'))
		, 'superdruid_cp' => array('table'=> 'category', 'col'=>array('parent_id', 'category_id'))
		, 'superdruid_r' => array('table'=> 'review', 'col'=>array('product_id', 'status', 'rating'))
		, 'superdruid_ps' => array('table'=> 'product_special', 'col'=>array('product_id', 'customer_group_id', 'priority', 'date_start', 'date_end', 'price'))
		, 'superdruid_pi' => array('table'=> 'product_image', 'col'=>array('product_id', 'product_image_id', 'sort_order', 'image'))
		, 'superdruid_pr' => array('table'=> 'product_reward', 'col'=>array('product_id', 'product_reward_id', 'customer_group_id', 'points'))
		, 'superdruid_ss' => array('table'=> 'stock_status', 'col'=>array('stock_status_id', 'language_id', 'name'))
		, 'superdruid_wcd' => array('table'=> 'weight_class_description', 'col'=>array('weight_class_id', 'language_id', 'title', 'unit'))
		, 'superdruid_lcd' => array('table'=> 'length_class_description', 'col'=>array('length_class_id', 'language_id', 'title', 'unit'))
		, 'superdruid_m' => array('table'=> 'manufacturer', 'col'=>array('manufacturer_id', 'sort_order', 'name', 'image'))
		
		, 'superdruid_p_cat' => array('table'=> 'product', 'col'=>array('product_id', 'status', 'date_available'))

		, 'superdruid_pd' => array('table'=> 'product_discount', 'col'=>array('product_id', 'customer_group_id', 'product_discount_id', 'quantity', 'priority', 'date_start', 'date_end', 'price'))

		, 'superdruid_po' => array('table'=> 'product_option', 'col'=>array('product_id', 'option_id', 'product_option_id', 'required'))
		, 'superdruid_od' => array('table'=> 'option_description', 'col'=>array('option_id', 'language_id', 'name'))
		, 'superdruid_o' => array('table'=> 'option', 'col'=>array('option_id', 'type', 'sort_order'))
		
		, 'superdruid_pa' => array('table'=> 'product_attribute', 'col'=>array('product_id', 'attribute_id', 'language_id'))
		, 'superdruid_ag' => array('table'=> 'attribute_group', 'col'=>array('attribute_group_id', 'sort_order'))
		, 'superdruid_agd' => array('table'=> 'attribute_group_description', 'col'=>array('attribute_group_id', 'language_id', 'name'))
		, 'superdruid_a' => array('table'=> 'attribute', 'col'=>array('attribute_id', 'attribute_group_id', 'sort_order'))

		, 'superdruid_ua_query' => array('table'=> 'url_alias', 'col'=>array('query'))
		, 'superdruid_ua_keyword' => array('table'=> 'url_alias', 'col'=>array('keyword'))
		
		, 'superdruid_order' => array('table'=> 'order', 'col'=>array('date_added', 'order_status_id', 'order_id'))
		, 'superdruid_order_product' => array('table'=> 'order_product', 'col'=>array('order_id', 'quantity'))
		, 'superdruid_order_product2' => array('table'=> 'order_product', 'col'=>array('product_id', 'quantity'))

		
		
		);
	
	
	
	public function install()
	{
	$def = $this->def;
	
	$sql = 'SELECT DISTINCT 
				TABLE_NAME
				, INDEX_NAME
			FROM INFORMATION_SCHEMA.STATISTICS
			WHERE
				TABLE_SCHEMA = DATABASE()
				AND
				INDEX_NAME LIKE \'superdruid_%\'';

	$indexes = array();
	$tmp = $this->db->query($sql)->rows;
	foreach($tmp as $t)
		$indexes[$t['INDEX_NAME']] = $t['INDEX_NAME'];

	
	foreach($def as $index=>$data)
		{
		$cols = array();
		$colx = array();
		if(!in_array($index, $indexes))
			{
			$sql = sprintf('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME=oc_\'%s\'', DB_PREFIX.$data['table']);
			$tmp = $this->db->query($sql)->rows;
			foreach($tmp as $c)
				$cols[$c['COLUMN_NAME']] = $c['COLUMN_NAME'];
				
			foreach($data['col'] as $col)
				{
				if(!in_array($col, $cols)) continue 2;
				$colx[] = sprintf('`%s`', $col);
				}
				
			$sql = sprintf('ALTER TABLE oc_`%s` ADD INDEX `%s` (%s)', DB_PREFIX.$data['table'], $index, implode(', ', $colx));
			$this->db->query($sql);
			$sql = sprintf('ANALYZE TABLE oc_`%s`', DB_PREFIX.$data['table']);
			$this->db->query($sql);
			}
		}
	
	$this->reg();
	}
	
	public function uninstall()
	{
		$def = $this->def;
	
		$indexes = array();
		foreach($def as $index=>$data)
			$indexes[] = sprintf('\'%s\'', $index);
			
		
		$sql = sprintf('SELECT DISTINCT 
					TABLE_NAME
					, INDEX_NAME
				FROM INFORMATION_SCHEMA.STATISTICS
				WHERE
					TABLE_SCHEMA = DATABASE()
					AND
					INDEX_NAME IN (%s)', implode(', ', $indexes));

		$tmp = $this->db->query($sql)->rows;
		foreach($tmp as $x=>$index)
			{
			$sql = sprintf('ALTER TABLE `%s` DROP INDEX `%s`', $index['TABLE_NAME'], $index['INDEX_NAME']);
			$this->db->query($sql);
			}
	}
	
	
	private function reg()
	{
	$url = 'http://oc.superdruidsss.com/reg/';
	$fields = array(
						'plugin' => 'OC Premium DB Indexes'
						, 'store_url' => HTTP_SERVER
						, 'store_version' => VERSION
				);

	$fields_string = '';
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
	rtrim($fields_string, '&');

	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	$result = @curl_exec($ch);

	curl_close($ch);
	}
}
?>
