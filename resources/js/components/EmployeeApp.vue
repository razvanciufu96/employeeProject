<template>
    <div>
        <div v-for="(notification, index) in notifications" :key="index">
            <Notification :message="notification.message" :type="notification.type" :duration="notification.duration" />
        </div>
        <h1 class="mb-4 p-5">Employee Management</h1>

        <div class="w-100 d-flex justify-content-between px-5">
            <div class="form-group">
                <input v-model="searchQuery" class="form-control" @input="getEmployees()" placeholder="Search by name" />
            </div>
            <button class="btn btn-primary mb-3 float-end" @click="openAddEmployeeForm">Add Employee</button>
        </div>

        <div class="w-100 table table-dark px-5 table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th @click="sortEmployees('name')">Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th @click="sortEmployees('job_title')">Job Title</th>
                    <th @click="sortEmployees('salary')">Salary</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="employee in paginatedEmployees" :key="employee.id">
                    <td>{{ employee.name }}</td>
                    <td>{{ employee.email }}</td>
                    <td>{{ employee.phone_number }}</td>
                    <td>{{ employee.job_title }}</td>
                    <td>{{ employee.salary }}</td>
                    <td>
                        <button class="btn btn-danger" @click="deleteEmployee(employee.id)">Delete</button>
                        <button class="btn btn-primary" @click="openUpdateEmployeeForm(employee)">Edit</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item" :class="{'disabled': currentPage === 1}">
                    <a class="page-link" href="#" @click="changePage(currentPage - 1)">Previous</a>
                </li>
                <li class="page-item" :class="{'active': currentPage === page}" v-for="page in totalPages" :key="page">
                    <a class="page-link" href="#" @click="changePage(page)">{{ page }}</a>
                </li>
                <li class="page-item" :class="{'disabled': currentPage === totalPages}">
                    <a class="page-link" href="#" @click="changePage(currentPage + 1)">Next</a>
                </li>
            </ul>
        </nav>

        <div v-if="showAddEmployeeForm" class="modal fade show" tabindex="-1" role="dialog" style="display: block;" aria-hidden="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-between">
                        <h5 v-if="modalType === 'create'" class="modal-title">Add Employee</h5>
                        <h5 v-else class="modal-title">Update Employee: {{ this.newEmployee.name }}</h5>
                        <button type="button" class="btn close float-end" @click="showAddEmployeeForm = false" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="modalType === 'create' ? addEmployee() : updateEmployee(this.newEmployee)" >
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="newEmployee.name" type="text" class="form-control" id="name" placeholder="Enter name"  />
                                <div v-if="errors.name" class="text-danger">{{ errors.name[0] }}</div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="newEmployee.email" type="email" class="form-control" id="email" placeholder="Enter email"  />
                                <div v-if="errors.email" class="text-danger">{{ errors.email[0] }}</div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input v-model="newEmployee.phone_number" type="text" class="form-control" id="phone_number" placeholder="Enter phone number"  />
                                <div v-if="errors.phone_number" class="text-danger">{{ errors.phone_number[0] }}</div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="job_title">Job Title</label>
                                <input v-model="newEmployee.job_title" type="text" class="form-control" id="job_title" placeholder="Enter job title"  />
                                <div v-if="errors.job_title" class="text-danger">{{ errors.job_title[0] }}</div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="salary">Salary</label>
                                <input v-model="newEmployee.salary" type="number" class="form-control" id="salary" placeholder="Enter salary" />
                                <div v-if="errors.salary" class="text-danger">{{ errors.salary[0] }}</div>
                            </div>
                            <br>
                            <button v-if="modalType === 'create'" type="submit" class="btn btn-primary btn-block mt-3 float-end">Add Employee</button>
                            <button v-else type="submit" class="btn btn-primary btn-block mt-3 float-end">Update Employee</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import Notification from './Notifications';

export default {
    components: {
        Notification,
    },
    data() {
        return {
            employees: [],
            searchQuery: '',
            sortBy: 'job_title',
            sortOrder: 'asc',
            newEmployee: {
                name: '',
                email: '',
                phone_number: '',
                job_title: '',
                salary: '',
                id : null
            },
            modalType: 'create',
            currentPage: 1,
            itemsPerPage: 5,
            showAddEmployeeForm: false,
            notifications: [],
            errors: {}
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.employees.length / this.itemsPerPage);
        },
        paginatedEmployees() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.employees.slice(start, end);
        }
    },
    mounted() {
        this.getEmployees();
    },
    methods: {
        showNotification(message, type, duration = 3000) {
            this.notifications.push({ message, type, duration });
            setTimeout(() => {
                this.notifications.shift();
            }, duration);
        },
        getEmployees() {
            axios.get('api/employees', {
                params: {
                    search: this.searchQuery,
                    sort_by: this.sortBy,
                    sort_order: this.sortOrder,
                }
            }).then((response) => {
                if (response.data.error) {
                    this.showNotification(response.data.message, 'error');
                } else {
                    this.employees = response.data.data;
                }

            });
        },

        openAddEmployeeForm() {
            this.modalType = 'create';
            this.showAddEmployeeForm = true;
            this.newEmployee = {
                name: '',
                email: '',
                phone_number: '',
                job_title: '',
                salary: '',
                id: null
            };
        },
        openUpdateEmployeeForm(employee) {
            this.modalType = 'update';
            this.showAddEmployeeForm = true;
            this.newEmployee = {
                name: employee.name,
                email: employee.email,
                phone_number: employee.phone_number,
                job_title: employee.job_title,
                salary: employee.salary,
                id: employee.id
            };
        },
        addEmployee() {
            axios.post('api/employees', this.newEmployee).then((response) => {
                this.getEmployees();
                this.newEmployee = {};
                this.showAddEmployeeForm = false;
                this.errors = {};
                if (response.data.error) {
                    this.$notify({
                        title: "Error",
                        text: response.data.message,
                        type: "error",
                        duration: 7000
                    });
                    this.errors = error.response.data.errors;
                    this.showNotification(response.data.message, 'error');
                } else {
                    this.$notify({
                        title: "Success",
                        text: response.data.message,
                        type: "success",
                        duration: 7000
                    });
                    this.showNotification(response.data.message, 'success');
                }
            }).catch((error) => {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            });
        },
        deleteEmployee(id) {
            axios.delete(`api/employees/${id}`).then((response) => {
                this.getEmployees();
                if (response.data.error) {
                    this.$notify({
                        title: "Error",
                        text: response.data.message,
                        type: "error",
                        duration: 7000
                    });
                    this.showNotification(response.data.message, 'error');
                } else {
                    this.$notify({
                        title: "Success",
                        text: response.data.message,
                        type: "success",
                        duration: 7000
                    });
                    this.showNotification(response.data.message, 'success');
                }
            });
        },
        updateEmployee(employee) {
            axios.put(`api/employees/${employee.id}`, employee).then((response) => {
                this.getEmployees();
                this.newEmployee = {};
                this.showAddEmployeeForm = false;
                this.errors = {};
                if (response.data.error) {
                    this.$notify({
                        title: "Error",
                        text: response.data.message,
                        type: "error",
                        duration: 7000
                    });
                    this.errors = error.response.data.errors;
                    this.showNotification(response.data.message, 'error');
                } else {
                    this.$notify({
                        title: "Success",
                        text: response.data.message,
                        type: "success",
                        duration: 7000
                    });
                    this.showNotification(response.data.message, 'success');
                }
            }).catch((error) => {
                console.log(error)
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            });
        },
        changePage(page) {
            if (page < 1 || page > this.totalPages) return;
            this.currentPage = page;
        },
        sortEmployees(sortBy) {
            if (this.sortBy === sortBy) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = sortBy;
                this.sortOrder = 'asc';
            }
            this.getEmployees();
        },
    }
};
</script>

<style scoped>
.modal {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
