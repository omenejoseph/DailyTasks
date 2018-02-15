<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">My Tasks</div>

                    <div class="card-body" v-cloak>
                        <div v-cloak class="alert alert-info text-center" v-if="!tasks.length">You have no tasks </div>
                       <div class="input-group">
                       		<input type="text" class="form-control" 
                               v-model="task.name"
                               @keydown.enter="create"
                               />
                       			<span class="input-group-btn">
                       				<button class="btn btn-primary" @click="create">Add Task</button>
                       			</span>
                        </div>
                        
                        <div class="tasks-list">
        					<ul class="list-unstyled" >
        						<li v-for="task in tasks" :key="task.id" :class="{done : task.completed}">
        							<div class="checkbox">
                                        <label>
                                            <input type="checkbox" v-model="task.completed" @click="done(task)"/> {{task.name}} 
                                        </label>
                                        <div class="pull">
                                            <a href="#" @click.prevent="remove(task)">x</a>
                                        </div>
                                    </div>
                                </li>	
        					</ul>
        				</div>	
                    </div>
                    <div class="card-footer" v-if="tasks.length" v-cloak>
                        <span class="badge badge-info">you have {{ tasks.length }} task(s) to complete</span>
                        <span class="badge badge-success"> you have complete {{ completedTasks() }} tasks</span>
                        <span class="badge badge-warning">you have {{ remainingTasks() }} to complete</span>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
           this.fetchData();
        },
        data () {
            return {
                tasks: [],
                task: {
                    name: '',
                   
                }
            }
        },
        methods: {
            fetchData () {
                axios.get('api/tasks')
                .then((res)=>{
                    this.tasks = res.data
                })
                .catch((err) =>{
                    console.log(err)
                    console.log('fetch error')
                })
            },

            create () {
                axios.post('api/tasks', this.task)
                .then((res)=>{
                    this.tasks.unshift(res.data)
                    this.task.name = ''
                })
                .catch((err) =>{
                    console.log(err)
                })
            },

            done (task) {
                axios.put(`api/tasks/${task.id}`, {
                    completed: !task.completed
                })
                .then((res)=>{
                   
                    console.log('task updated');
                })
                .catch((err) =>{
                    console.log(err)
                })
            },

            remove (task) {
                axios.delete(`api/tasks/${task.id}`)
                .then((res)=>{
                   
                    const taskIndex = this.tasks.indexOf(task)
                    this.tasks.splice(taskIndex, 1)
                })
                .catch((err) =>{
                    console.log(err)
                })
            },

            remainingTasks(){

                return this.tasks.filter(task => {return !task.completed}).length;
            },

            completedTasks(){

                return this.tasks.filter(task => {return task.completed}).length;
            }
        }
    }
</script>

<style>
body, .tasks-list{
    padding-top: 40px;
}

.done label{
    text-decoration: line-through;
}

.pull{
    float: right;
}

v-cloack{
    display: hidden;
}
</style>

