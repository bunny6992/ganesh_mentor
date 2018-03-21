<template>
    <div class="container-fluid">
        <div class="row">
            <!-- <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                    Project Resources
                    <span 
                        class="glyphicon glyphicon-plus pull-right"
                        style="cursor: pointer"
                        v-on:click="createResource"></span>
                    </div>

                    <div class="panel-body">
                        <div class="" v-for="resource in resources">
                            {{resource.name}}
                            <span 
                            class="glyphicon glyphicon-minus pull-right"
                            style="cursor: pointer"
                            v-on:click="deleteResource(resource.id)">                                
                            </span>

                            <span 
                            class="glyphicon glyphicon-edit pull-right"
                            style="cursor: pointer"
                            v-on:click="editResource(resource.id)">
                            &nbsp;
                            </span>
                            
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-md-12">
            <button type="button" class="btn btn-link" v-on:click="createResource"><b>Add New Resource</b></button>
            <small class="text-muted text-center">Double click any field to edit that field row</small>
                <input class="form-control mr-sm-2" type="search" name="" v-model="filterKey" placeholder="Search...">

                <table class="table table-responsive">
                  <thead>
                    <tr>
                      <th v-for="header in [{key: 'name', label: 'Name'}, {key: 'email', label: 'Email'}, {key: 'phone', label: 'Phone'}, {key: 'groupName', label: 'Group Name'}]" 
                      @click="sortBy(header.key)" :class="{ active: sortKey == header.key }">
                          {{ header.label }}
                          <span class="arrow" :class="sortOrders[header.key] > 0 ? 'asc' : 'dsc'">
                            </span>
                      </th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="resource in filteredData">
                      <td v-on:dblclick="editRes(resource)"> 
                        <label v-show="editedResource != resource.id">
                            {{resource.name}}
                        </label>
                        <input class="form-control edit" type="text" v-show="editedResource == resource.id"
                          v-model="resource.name"
                          v-todo-focus="resource == editedResource"
                          @keyup.enter="doneEdit(resource)"
                          @keyup.esc="cancelEdit(resource)">
                        </td>
                      <td v-on:dblclick="editRes(resource)">
                          <label v-show="editedResource != resource.id">
                            {{resource.email}}
                        </label>
                        <input class="form-control edit" type="text" v-show="editedResource == resource.id"
                          v-model="resource.email"
                          v-todo-focus="resource == editedResource"
                          @keyup.enter="doneEdit(resource)"
                          @keyup.esc="cancelEdit(resource)">
                      </td>
                      <td v-on:dblclick="editRes(resource)">
                          <label v-show="editedResource != resource.id">
                            {{resource.phone}}
                        </label>
                        <input class="form-control edit" type="text" v-show="editedResource == resource.id"
                          v-model="resource.phone"
                          v-todo-focus="resource == editedResource"
                          @keyup.enter="doneEdit(resource)"
                          @keyup.esc="cancelEdit(resource)">
                      </td>
                      <td v-on:dblclick="editRes(resource)">
                        <label v-show="editedResource != resource.id">
                            {{resource.groupName}}
                        </label>
                        <input class="form-control edit" type="text" v-show="editedResource == resource.id"
                          v-model="resource.groupName"
                          v-todo-focus="resource == editedResource"
                          @keyup.enter="doneEdit(resource)"
                          @keyup.esc="cancelEdit(resource)">
                      </td>
                      <td>
                          <span 
                            class="glyphicon glyphicon-trash pull-right"
                            style="cursor: pointer"
                            v-on:click="deleteResource(resource.id)">                                
                            </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="myResourceModal" tabindex="-1" resource="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" resource="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Project Resource</h4>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="computedAction" method="POST">
                                <div class="form-group" :class="{'has-error': errors['name'] }" >
                                    <label class="control-label" for="code">Full Name</label>
                                    <input v-model="resource.name" data-rules="required|name" class="form-control" type="text" placeholder="Naruto Uzumaki">
                                    <p class="text-danger" v-if="errors['name']">{{ errors['name'][0] }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': errors['email'] }" >
                                    <label class="control-label" for="email">Email</label>
                                    <input v-model="resource.email" data-rules="required|email" class="form-control" type="email" placeholder="mentor@gmail.com">
                                    <p class="text-danger" v-if="errors['email']">{{ errors['email'][0] }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': errors['phone'] }" >
                                    <label class="control-label" for="phone">Mobile</label>
                                    <input v-model="resource.phone" class="form-control" type="text" placeholder="+123456789">
                                    <p class="text-danger" v-if="errors['phone']">{{ errors['phone'][0] }}</p>
                                </div>

                                <div class="form-group" :class="{'has-error': errors['groupName'] }" >
                                    <label class="control-label" for="groupName">Group Name</label>
                                    <input v-model="resource.groupName" class="form-control" type="text" placeholder="">
                                    <p class="text-danger" v-if="errors['groupName']">{{ errors['groupName'][0] }}</p>
                                </div>

                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</template>

<script>
    export default {
        props: ['user', 'projectId'],
        data: function() {
            var sortOrders = {};
            _.forEach(['name', 'email', 'phone', 'groupName'], (item) => {
                sortOrders[item] = 1;
            })
            return {
                resources: [],
                resource: {
                    name: '',
                    email: '',
                    phone: '',
                    groupName: '',
                    user_id: this.user,
                    project_id: this.projectId
                },
                errors: [],
                updatable: false,
                needUpdationId: null,
                sortKey: '',
                sortOrders: sortOrders,
                filterKey: '',
                editedResource: null
            }
        },
        computed: {
            filteredData: function () {
            console.log(this.sortKey);
            var sortKey = this.sortKey
            var filterKey = this.filterKey && this.filterKey.toLowerCase()
            var order = this.sortOrders[sortKey] || 1
            var data = this.resources
            if (filterKey) {
                data = data.filter(function (row) {
                    return Object.keys(row).some(function (key) {
                        return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                    })
                })
            }
            if (sortKey) {
            data = data.slice().sort(function (a, b) {
            a = a[sortKey]
            b = b[sortKey]
            return (a === b ? 0 : a > b ? 1 : -1) * order
            })
            }
            return data
            }
        },
        filters: {
            capitalize: function (str) {
                return str.charAt(0).toUpperCase() + str.slice(1)
            }
        },
        methods: {
            editRes(resource) {
                this.editedResource = resource.id;
                this.resource = resource;
                console.log(this.editedResource);
            },
            doneEdit (resource) {
                this.updateResource(resource.id);
                this.editedResource = null;
            },
            cancelEdit (resource) {
                this.editedResource = null;
                this.resetResource();
            },
            sortBy: function (key) {
              this.sortKey = key
              this.sortOrders[key] = this.sortOrders[key] * -1
            },
            computedAction () {
                if (this.updatable === false) {
                    return this.storeResource();
                } else {
                    return this.updateResource(this.needUpdationId);
                }
            },

            createResource () {
                this.resetResource();
                this.updatable = false;
                this.needUpdationId = null;
                $('#myResourceModal').modal('show');
            },

            storeResource () {
                axios.post(`/resource`, this.resource).then((response) => {
                    // console.log(response);
                    $('#myResourceModal').modal('hide');
                    this.getResources();
                }).catch((error) => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                })
            },

            editResource (resourceId) {
                axios.get(`/resource/${resourceId}`).then((response) => {
                    console.log(response);
                    this.resource.name = response.data.name;
                    this.resource.email = response.data.email;
                    this.resource.phone = response.data.phone;
                    this.resource.groupName = response.data.groupName;
                    this.updatable = true;
                    this.needUpdationId = response.data.id;
                    $('#myResourceModal').modal('show');
                }).catch((error) => {
                    console.log(error);
                })
            },

            updateResource (resourceId) {
                axios.put(`/resource/${resourceId}`, this.resource).then((response) => {
                    console.log(response);
                    
                    this.resetResource();

                    this.updatable = false;
                    this.needUpdationId = null;
                    $('#myResourceModal').modal('hide');
                    this.getResources();
                }).catch((error) => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                })
            },

            deleteResource (resourceId) {
                if(window.confirm('Do you want to delete this resource ?')) {
                    axios.delete(`/resource/${resourceId}`).then((response) => {
                        console.log(response);
                        this.getResources();
                    }).catch((error) => {
                        console.log(error);
                    })
                }
            },

            getResources () {
                axios.get(`/resource?projectId=${this.projectId}`, this.resource).then((response) => {
                    // console.log(response);
                    this.resources = response.data;
                }).catch((error) => {
                    console.log(error);
                })
            },

            resetResource () {
                this.resource.name = '';
                this.resource.email = '';
                this.resource.phone = '';
                this.resource.groupName = '';
            }
        },
        directives: {
            'todo-focus': function (el, binding) {
                if (binding.value) {
                    el.focus()
                }
            }
        },
        mounted() {
            this.getResources();    
            console.log(this.filteredData);
        }
    }
</script>
