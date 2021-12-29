<?php
require_once('database.php');
class M_category extends database
{
	public function categories($offset, $limit)
	{
		$sql = "select * from categories LIMIT $limit OFFSET $offset";
		$this->setQuery($sql);
		$list = $this->loadAllRows();
		$data = [];
		foreach ($list as $key => $value) {
			array_push($data, ['id' => $value->id, 'name' => $value->name, 'parent_id' => $value->parent_id]);
		}

		$sql = "select COUNT(id) c from categories";
		$this->setQuery($sql);
		$total = $this->loadRow();
		$rs  = [
			'data' => $this->flatten_array_with_level_marking($data, 0, 0),
			'total' => $total->c
		];
		return $rs;
	}

	public function update($name, $prid, $id)
	{
		$sql = "UPDATE categories SET name ='$name',parent_id = $prid WHERE id = $id";
		$this->setQuery($sql);
		return $this->execute();
	}

	public function insert($name, $prid)
	{
		$sql = "INSERT INTO categories(name, parent_id) VALUES ('$name',$prid)";
		$this->setQuery($sql);
		return $this->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM `categories` WHERE id = $id";
		$this->setQuery($sql);
		return $this->execute();
	}

	public function getAllSelect()
	{
		$sql = "select * from categories";
		$this->setQuery($sql);
		return $this->loadAllRows();
	}

	public function flatten_array_with_level_marking($arr, $parent = 0, $level = 0)
	{

		$returnArr = [];

		$currentArray = array_filter($arr, function ($item) use ($parent) {
			return $item['parent_id'] == $parent;
		});

		array_splice($currentArray, 0, 0); // Reset index of $currentArray

		$amount = count($currentArray);

		for ($i = 0; $i < $amount; $i++) {

			// Recursive
			switch (true) {
				case $currentArray[$i]['parent_id'] == 0:
					$level = 0;
					break;
				case $currentArray[$i]['parent_id'] == $parent;
					$level++;
					break;
			}

			$currentArray[$i]['level'] = $level;
			$returnArr[] = $currentArray[$i];

			$parent = $currentArray[$i]['id'];
			$returnArr = array_merge($returnArr, $this->flatten_array_with_level_marking($arr, $parent, $level));
		}

		return $returnArr;
	}


	public function getAll($limit, $offset)
	{
		$sql = "select * from categories limit 3,1";
		$this->setQuery($sql);
		$list = $this->loadAllRows();
		// $data = [];
		// foreach ($list as $key => $value) {
		// 	array_push($data, ['id' => $value->id, 'name' => $value->name, 'parent_id' => $value->parent_id]);
		// }
	

		$sql = "select COUNT(id) c from categories WHERE parent_id =0";
		$this->setQuery($sql);
		$total = $this->loadRow();
		$rs  = [
			'data' => $this->getCategoriesParent($list),
			'total' => $total->c
		];
		
		return $rs;
	}
	public function getCategoriesParent($categories, $parent_id = 0, $item = null)
	{
		
		$temp_result = [];
		foreach ($categories as $category) {
			if ($category->parent_id == $parent_id) {
				$sub_category =  $this->getCategoriesParent(
					$this->getAllSelect(),
					$category->id,
					$category
				);
			
				$category->children = $sub_category;
				$element_array = ['id' => $category->id, 'name' => $category->name, 'parent_id' => $category->parent_id, 'children' => $category->children];
				$temp_result[] = $element_array;
			}
		}
		return $temp_result;
	}

	public function Get_category_id($id)
	{
		$sql = "select * from categories where id=?";
		$this->setQuery($sql);
		$param = array($id);
		return $this->loadRow($param);
	}
}
