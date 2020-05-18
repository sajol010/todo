<!DOCTYPE html>
<html>
<head>
<title>Todo List</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" />
<link rel="stylesheet" href="assets/css/style.css" />

</head>
<body>
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="card px-3">
                    <div class="card-header">
                        <h4 class="justify-content-center" style="text-align: center;">Todo list</h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="add-items d-flex"> <input type="text" class="form-control todo-list-input" v-model="input_todo" placeholder="What do you need to do today?" v-on:keyup.enter="addTodo">  </div>
                        <div class="list-wrapper">
                            <ul class="d-flex flex-column-reverse todo-list">
                                <li v-for="(task, index) in todo_list" v-bind:key="index" v-bind:class="(task.status==2)?'completed':''" >
                                    <div class="form-check"> 
                                        <label v-if="task.is_edit==false" class="form-check-label" v-on:click="completeTask(task.id)" v-on:dblclick="showEditInputField(index, task.id)"> 
                                            <input class="checkbox" type="checkbox" :checked="(task.status==2)?'checked':'' " v-on:input="completeTask(task.id)">
                                             {{ task.name }}
                                             <i class="input-helper">
                                                 <input type="text" name="">
                                             </i>
                                         </label> 
                                         <input class="form-control" v-else type="text" name="edit" v-model="todo_list[index].name" v-on:keyup.enter="editTask(index, task.id)">
                                     </div> 
                                     <i class="fa fa-edit" style="margin-left: 10px;" v-on:click="showEditInputField(index, task.id)"></i>

                                     <i class="remove mdi mdi-close-circle-outline" v-on:click="removeItem(task.id)"></i>
                                </li>                        
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-3">{{ count }} items left</div>
                            <div class="col-md-6">

                                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                  <div class="collapse navbar-collapse" id="navbarNav">
                                    <ul class="navbar-nav">
                                      <li class="nav-item active">
                                        <a class="nav-link" href="#" v-on:click="listData('all')">All <span class="sr-only">(current)</span></a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" href="#" v-on:click="listData('active')">Active</a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" href="#" v-on:click="listData('completed')">Completed</a>
                                      </li>
                                    </ul>
                                  </div>
                                </nav>

                        </div>
                           
                                        <a class="nav-link" href="#" style="text-decoration: none" v-on:click="deleteAllCompeleted">Clear Completed</a>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
var app = new Vue({
  el: '#page-content',
  data: {
    input_todo: '',
    todo_list: [
    ],
    count: 0,
  },
  methods:{
    listData: function(filter = 'all'){
        let ref = this;
        let data = {
            action: 'getData',
            filter: filter,
        }
        axios.post('ajax.php', data).then(function(response){
            let res = response.data;
            ref.todo_list = [];

            for (var i = 0; i < res.length; i++) {
                let new_obj = new Object();
                new_obj.id = res[i].id;
                new_obj.name = res[i].name;
                new_obj.status = res[i].status;
                new_obj.is_edit = false;

                ref.todo_list.push(new_obj);
            }
        });
    },
    addTodo: function(){
        let ref = this;
        let data = {
            action: 'add',
            name: ref.input_todo,
        }
        axios.post('ajax.php', data).then(function(response){
            let res = response.data;
            ref.input_todo = '';
            ref.listData();
            ref.countActiveNumber();
        });
    },
    completeTask: function(id){
        let ref = this;
        let data = {
            action: 'compelete_task',
            id: id,
        }
        axios.post('ajax.php', data).then(function(response){
            let res = response.data;
            ref.listData();
            ref.countActiveNumber();
        });
    },
    countActiveNumber: function(){
        let ref = this;
        let data = {
            action: 'count_active_number',
        }

        axios.post('ajax.php', data).then(function(response){
            let res = response.data;
            ref.count = res.count;
        });
    },
    removeItem: function(id){
        let ref = this;
        let data = {
            action: 'remove_task',
            id: id,
        }
        axios.post('ajax.php', data).then(function(response){
            let res = response.data;
            ref.listData();
            ref.countActiveNumber();
        });
    },
    showEditInputField: function(index, id){
        console.log('working');
        let ref = this;
        ref.todo_list[index].is_edit = true;

        console.log(ref.todo_list[index].is_edit);
    },

    editTask: function(index, id){
        let ref = this;
        let name = ref.todo_list[index].name;
        let data = {
            action: 'edit_task',
            id: id,
            name: name,
        }
        axios.post('ajax.php', data).then(function(response){
            let res = response.data;
            ref.listData();
            ref.countActiveNumber();
        });

    },

    deleteAllCompeleted: function(){
        let ref = this;
        let data = {
            action: 'delete_completed',

        }
        axios.post('ajax.php', data).then(function(response){
            let res = response.data;
            ref.listData();
            ref.countActiveNumber();
        });
    }

  },
  created(){
    this.listData();
    this.countActiveNumber();
    }
})
</script>
</body>
</html>
