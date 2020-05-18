<?php
require_once __DIR__ . '/vendor/autoload.php'; 
use Todo\Todo;

$todoObj = new Todo();

$data = json_decode(file_get_contents("php://input"),true);


switch ($data['action']) {
	case 'add':
		$name = $data['name'];
		$response = $todoObj->add($name);
		echo json_encode($response);
		break;

	case 'getData':
		$filter = $data['filter'];
		$response = $todoObj->todoListData($filter);
		echo json_encode($response);
		break;

	case 'compelete_task':
		$id = $data['id'];
		$response = $todoObj->compeleteTaks($id);
		echo json_encode($response);

	case 'count_active_number':
		$response = $todoObj->countActive();
		echo json_encode($response);
		break;

	case 'remove_task':
		$id = $data['id'];
		$response = $todoObj->removeTask($id);
		echo json_encode($response);

	case 'edit_task':
		$id = $data['id'];
		$name = $data['name'];
		$response = $todoObj->editTask($id, $name);
		echo json_encode($response);
		break;

	case 'delete_completed':
		$response = $todoObj->deleteCompleted();
		echo json_encode($response);
		break;

	default:
		# code...
		break;
}




