<?php

include('model/m_category.php');

class C_index
{
	public $stringMenu = "";
	public function Read_index($page)
	{
		$m_category = new M_category();
		$limit =10 ;
		$offset = ($page - 1) * $limit;
		$data = $m_category->categories($offset,$limit);
		// $data =  $m_category->getAll($limit,$offset);
		$list = $m_category->getAllSelect();
		// $categories = $data['data'];
	
	
		//view 	
		include('view/layout/header.php');
		include('view/list.php');
		include('view/layout/footer.php');
	}
	public function postCategory($val)
	{
		$m_category = new M_category();

		if ($val['id'] > 0) {
			$list = $m_category->update($val['name'], $val['parent_id'], $val['id']);
		} else {
			$list = $m_category->insert($val['name'], $val['parent_id']);
		}
		header("location:/category");
	}

	public function deleteCategory($id)
	{
		$m_category = new M_category();
		$list = $m_category->delete($id);
		header("location:/category");
	}

	public function printMenu($categories)
	{

		$i = 1;
		//go through each top level menu item

		foreach ($categories as $category) {
			$this->stringResult = '
			<tr>
                            <td>
			' . $i . '
                            </td>
                            <td>
						' . $category['name'];

			if (!empty($category['children'])) {
				$temp = '<div style="margin-left:10px">';
				//echo the child menu
				$this->printMenu($category['children']);
				$temp = $temp . $category['name'] . '</div>';
				$this->stringResult .= $temp;
			}
			$stringResult = $this->stringResult . ' </td>
			<td>
				<button class="btn btn-primary btn-xs">
					<i class="fa fa-edit"></i>
				</button>
				<button class="btn btn-danger btn-xs">
					<i class="fa fa-trash"></i>
				</button>sss
			</td>
		</tr>';
		}
		return $stringResult;
	}
}
