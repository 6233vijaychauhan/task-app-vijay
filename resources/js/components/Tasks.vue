<template>
    <div class="container">
        <h1 class="heading">Tasks</h1>

        <!-- Form for creating or editing a task -->
        <form class="form-card" @submit.prevent="saveTask">
            <div class="field">
                <!-- Input for task title, bound to form.title -->
                <input type="text" v-model="form.title" placeholder="Title" class="input" />
            </div>

            <div class="field">
                <!-- Textarea for task description, bound to form.description -->
                <textarea placeholder="Description" v-model="form.description" class="input textarea"></textarea>
            </div>

            <div class="field">
                <!-- Select dropdown for task status, bound to form.status -->
                <select v-model="form.status" class="input">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="field">
                <!-- Date input for due date, bound to form.due_date -->
                <input type="date" v-model="form.due_date" class="input" />
            </div>
            
            <!-- Submit button, text changes based on editMode -->
            <button type="submit" class="btn primary">{{ editMode ? 'Update' : 'Create' }}</button>

        </form>

        <!-- Loop through tasks.data to display each task -->
        <div v-for="task in tasks.data" :key="task.id" class="task-card">
            <h3 class="task-title">{{ task.title }}</h3>
            <p class="task-desc">{{ task.description }}</p>
            <p class="task-meta">Status: {{ task.status }}</p>
            <p class="task-meta" v-if="task.due_date">Due Date: {{ task.due_date }}</p>

            <!-- Edit button, calls editTask method -->
            <button type="button" class="btn warn" @click="editTask(task)">Edit</button>

            <!-- Delete button, calls deleteTask method -->
            <button type="button" class="btn danger" @click="deleteTask(task.id)">Delete</button>

        </div>

        <!-- Pagination section, only show if meta.links exist -->
        <div v-if="tasks.meta && tasks.meta.links" class="pagination">
            <!-- Loop through pagination links -->
            <button v-for="(link, index) in tasks.meta.links" 
                :key="index"
                @click="fetchTasks(link.url)"
                :disabled="!link.url"
                :class="['page-btn', { 'active': link.active, 'disabled': !link.url }]"
                v-html="link.label"
                ></button>
        </div>

    </div>
</template>

<script>
import axios from 'axios';


export default{
    data(){
        return {
            tasks: {},
            form: {
                title: '',
                description: '',
                status: 'pending',
                due_date: ''
            },
            editMode: false,
            editId: null
        }
    },
    methods:{
        // when page loads, fetch tasks
        async fetchTasks(url = "/api/tasks"){
            const {data} = await axios.get(url);
            this.tasks = data;
        },
        async saveTask(){
            if(this.editMode){
                await axios.put(`/api/tasks/${this.editId}`, this.form);
                this.editMode = false;
            }else{
                await axios.post('/api/tasks', this.form);
            }
            this.form = {
                title: '',
                description: '',
                status: 'pending',
                due_date: ''
            };
            
            this.fetchTasks();
        },
        editTask(task){
            this.form = {
                title: task.title,
                description: task.description,
                status: task.status,
                due_date: task.due_date || ''
            };
            this.editId = task.id;
            this.editMode = true;
        },
        async deleteTask(id){
            await axios.delete(`/api/tasks/${id}`);
            this.fetchTasks();
        }
    },
    mounted(){
        // fetch tasks when component is mounted while page loads
        this.fetchTasks();
    }
}

</script>

<style scoped>
.container{
    max-width:800px;
    margin:20px auto;
    padding:16px;
    font-family: Arial, Helvetica, sans-serif;
    background: transparent;
}
.heading{
    font-size:24px;
    margin-bottom:12px;
}
.form-card{
    background:#ffffff;
    padding:14px;
    border:1px solid #e6e6e6;
    border-radius:8px;
    margin-bottom:16px;
}
.field{ margin-bottom:10px; }
.input{
    width:100%;
    padding:8px 10px;
    border:1px solid #cfcfcf;
    border-radius:4px;
    font-size:14px;
    box-sizing:border-box;
}
.textarea{ min-height:80px; resize:vertical; }
.btn{ padding:8px 12px; border-radius:4px; border:none; cursor:pointer; margin-right:8px; font-size:14px; }
.btn.primary{ background:#3b82f6; color:#fff; }
.btn.warn{ background:#f59e0b; color:#fff; }
.btn.danger{ background:#ef4444; color:#fff; }
.task-card{
    background:#fff;
    border:1px solid #e6e6e6;
    padding:12px;
    border-radius:6px;
    margin-bottom:12px;
}
.task-title{ font-weight:600; margin-bottom:6px; }
.task-desc{ margin-bottom:6px; color:#333; }
.task-meta{ font-size:13px; color:#666; margin-bottom:6px; }
.pagination{ text-align:center; margin-top:12px; }
.page-btn{ padding:6px 10px; margin:0 4px; border-radius:4px; border:1px solid #ddd; background:#f3f4f6; cursor:pointer; }
.page-btn.active{ background:#3b82f6; color:#fff; border-color:#3b82f6; }
.page-btn.disabled{ opacity:0.6; cursor:not-allowed; }
</style>