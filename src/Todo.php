<?php
namespace Todo;

use Todo\Database;


class Todo{
	private $db;
    public function __construct(){
        $this->db = new Database();
    }

    public function todoListData($filter = 'all'){
    	$res = $this->db->select($filter);
		
		return $res;
    }
    public function add($name){
    	$res = $this->db->insert($name);
    	if ($res) {
	    	$data = [
	    		'status' => 201,
	    		'msg' => 'Saved'
	    	];
    	}else{
	    	$data = [
	    		'status' => 500,
	    		'msg' => 'Something Wrong'
	    	];
    	}

    	return $data;

    }


    public function compeleteTaks($id){
    	$res = $this->db->complete_task($id);
    	if ($res) {
	    	$data = [
	    		'status' => 201,
	    		'msg' => 'Completed'
	    	];
    	}else{
	    	$data = [
	    		'status' => 500,
	    		'msg' => 'Something Wrong'
	    	];
    	}

    	return $data;
    }

    public function countActive(){
        $count = $this->db->countStatus(1);
        $res = [
            'count' => $count,
        ];
        return $res;
    }

    public function removeTask($id){
        $res = $this->db->removeTaskById($id);
        if($res){
            $data = [
                'status' => 'ok',
                'msg' => 'deleted',
            ];
        }else{
            $data = [
                'status' => '500',
                'msg' => 'Something Wrong',
            ];
        }
        return $data;
    }


    public function editTask($id, $name){
        $res = $this->db->updateTask($id, $name); 
        if($res){
            $data = [
                'status' => 'ok',
                'msg' => 'deleted',
            ];
        }else{
            $data = [
                'status' => '500',
                'msg' => 'Something Wrong',
            ];
        }
        return $data;
    }


    public function deleteCompleted(){
        $res = $this->db->deleteAllComeplete($id, $name); 
        if($res){
            $data = [
                'status' => 'ok',
                'msg' => 'deleted',
            ];
        }else{
            $data = [
                'status' => '500',
                'msg' => 'Something Wrong',
            ];
        }
        return $data;
    }



} 