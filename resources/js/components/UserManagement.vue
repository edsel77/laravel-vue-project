<template>
    <div class="container mb-3">
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>User Lists</span>
                <router-link to="register-user">
                    <el-button style="float: right; padding: 3px 0" type="text">Register User</el-button>
                </router-link>
            </div>
            <div>
                <el-col :span="6" :offset="18">
                    <el-input
                    v-model="search"
                    @input="queryForKeywords"
                    size="small"
                    clearable
                    placeholder="Search"/>
                </el-col>
                <el-table
                    ref="multipleTable"
                    v-model="usersTable"
                    row-key="usersTableId"
                    height="500px"
                    :data="usersTable"
                    @sort-change="sortChange"
                    @row-click="rowClick"
                    :default-sort = "{prop: 'id', order: 'descending'}"
                    v-loading="loading"
                    element-loading-text="Loading..."
                    element-loading-spinner="el-icon-loading"
                    style="width: 100%"
                    @selection-change="handleSelectionChange">
                    <el-table-column
                    type="selection"
                    width="55">
                    </el-table-column>
                    <el-table-column
                    label="Username"
                    sortable
                    prop="username">
                    <template slot-scope="scope">
                        <el-popover trigger="hover" placement="top">
                            <p>Name: {{ scope.row.firstname + ' ' + scope.row.lastname }}</p>
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium">{{ scope.row.username }}</el-tag>
                            </div>
                        </el-popover>
                    </template>
                    </el-table-column>
                    <el-table-column
                    label="Firstname"
                    sortable
                    prop="firstname">
                    </el-table-column>
                    <el-table-column
                    label="Lastname"
                    sortable
                    prop="lastname">
                    </el-table-column>
                    <el-table-column
                    label="Contact"
                    prop="contact">
                    </el-table-column>
                    <el-table-column
                    property="email"
                    label="Email">
                    </el-table-column>
                    <el-table-column
                        label="Action"
                        >
                        <template slot-scope="scope">
                            <el-tooltip class="item" effect="dark" content="View account" placement="top">
                                <router-link :to="'/profile/'+usersTable[scope.$index].id">
                                    <i class="icon el-icon-info pointer" v-if="!showDeleted"></i>
                                    <i class="icon el-icon-info pointer info-deleted" v-else></i>
                                </router-link>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="Update account" placement="top" v-show="!showDeleted">
                                <router-link :to="'/update-user/'+usersTable[scope.$index].id">
                                    <i class="icon el-icon-edit-outline pointer ml-2"></i>
                                </router-link>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="float-right mt-2">
                    <el-pagination
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page.sync="pageInfo.current_page"
                        :page-sizes="[10, 20, 50, 100]"
                        :page-size="10"
                        layout="sizes, prev, pager, next"
                        @next-click="handleNextPage"
                        @prev-click="handlePrevPage"
                        :total="pageInfo.total">
                    </el-pagination>
                </div>
                <div class="mt-2">
                    <el-button type="danger" v-on:click="deleteMultipleUsers">{{ is_restore ? 'Restore' : 'Delete' }} selected</el-button>
                </div>
                <div class="show-deleted-checkbox mt-2" :span="6">
                    <el-checkbox v-model="showDeleted" @change="showDeletedUsers">Show deleted users</el-checkbox>
                </div>
            </div>
        </el-card>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
            return {
                form: new Form({
                    name: '',
                    email: '',
                    password: '',
                }),
                usersTable: [],
                search: '',
                pageInfo: {},
                perPage: 10,
                showDeleted: false,
                alert_text: '',
                keywords: '',
                sortParameter: {
                    "column":{},
                    "prop":"username",
                    "order":"ascending"
                },
                loading: '',
                is_restore: false,
            }
        },
        methods: {
            storeUser(){
                Swal.fire({
                    text: 'Are you sure you want to register new user?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Register',
                    width: '25rem',
                })
                .then((result) => {
                    if(result.value){
                        this.form.post('api/user')
                        .then((res) => {
                            Swal.fire(
                                'Registered!',
                                'User successfully registered.',
                                'success'
                            );
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: 'User registration failed.',
                            })
                            console.log(error)
                        });
                    }
                })
            },
            getUsers(){
                this.clearTable();
                this.loading = true;
                axios.get("api/user", { 
                    params: { 
                        take: this.perPage, 
                        showDeleted: this.showDeleted, 
                        search: this.keywords, 
                        sortProp: this.sortParameter.prop, 
                        sortOrder: this.sortParameter.order,
                    } 
                })
                .then((data) => {
                    this.pageInfo = data.data;
                    if(this.$currentPageUsersTable > 0){
                        this.handleCurrentChange(this.$currentPageUsersTable);
                    }
                    else{
                        this.usersTable = data.data.data;
                        this.pageInfo = data.data;
                        this.pageInfo.current_page = this.$currentPageUsersTable;
                        this.loading = false;   
                    }      
                })
                .catch(error => {
                    // this.loaded = true;
                    // if(error.response.status == 401){
                    //     window.location.href = '/login';
                    // }
                    // this.loading = false;
                });
            },
            handleSizeChange(val) {
                this.clearTable();
                this.loading = true;
                this.perPage = val;
                axios.get("api/user", { 
                    params: { 
                        take: this.perPage,
                        showDeleted: this.showDeleted, 
                        search: this.keywords,
                        sortProp: this.sortParameter.prop, 
                        sortOrder: this.sortParameter.order,
                    } 
                })
                .then((data) => {
                    this.usersTable = data.data.data;
                    this.pageInfo = data.data;
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                });
                this.currentPageGlobal();
            },
            handleCurrentChange(val) {
                this.clearTable();
                this.loading = true;
                axios.get(this.pageInfo.path+'?page='+val, { 
                    params: { 
                        take: this.perPage,
                        showDeleted: this.showDeleted, 
                        search: this.keywords,
                        sortProp: this.sortParameter.prop, 
                        sortOrder: this.sortParameter.order,
                    } 
                })
                .then((data) => {
                    this.usersTable = data.data.data;
                    this.pageInfo = data.data;
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                });
                this.currentPageGlobal();
            },
            handleNextPage(val) {
                this.clearTable();
                this.loading = true;
                axios.get(this.pageInfo.next_page_url, { 
                    params: { 
                        take: this.perPage, 
                        showDeleted: this.showDeleted, 
                        search: this.keywords,
                        sortProp: this.sortParameter.prop, 
                        sortOrder: this.sortParameter.order,
                    } 
                })
                .then((data) => {
                    this.usersTable = data.data.data;
                    this.pageInfo = data.data;
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                });
                this.currentPageGlobal();
            },
            handlePrevPage(val) {
                this.clearTable();
                this.loading = true;
                this.currentPageGlobal();
            },
            queryForKeywords(search) {
                this.clearTable();
                this.loading = true;
                this.keywords = search;
                axios.get("api/user", { 
                    params: { 
                        take: this.perPage, 
                        showDeleted: this.showDeleted, 
                        search: this.keywords,
                        sortProp: this.sortParameter.prop, 
                        sortOrder: this.sortParameter.order,
                    } 
                })
                .then((data) => {
                    this.usersTable = data.data.data;
                    this.pageInfo = data.data;
                    this.loading = false;
                })
                .catch(error => {
                    // this.loaded = true;
                    // if(error.response.status == 401){
                    //     window.location.href = '/login';
                    // }
                    // this.loading = false;
                });
            },
            sortChange(param){
                this.sortParameter = param;
                this.getUsers();
            },
            showDeletedUsers(){
                if(!this.is_restore) this.is_restore = true;
                else this.is_restore = false;

                this.clearTable();
                this.getUsers();
                Vue.prototype.$currentPageUsersTable = 1;
            },
            clearTable(){
                this.usersTable = [];
            },
            rowClick(val){
                this.$router.push('/profile/'+val.id);
            },
            toggleSelection(rows) {
                if(rows){
                    rows.forEach(row => {
                        this.$refs.multipleTable.toggleRowSelection(row);
                    });
                }
                else{
                    this.$refs.multipleTable.clearSelection();
                }
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },
            currentPageGlobal(){
                Vue.prototype.$currentPageUsersTable = this.pageInfo.current_page;
                this.currentPage = this.pageInfo.current_page;
            },
            deleteMultipleUsers(){
                let user_ids = _.map(this.multipleSelection, 'id');
                let action = 'delete';
                if(this.is_restore) action = 'restore';

                Swal.fire({
                    text: 'Are you sure you want to '+action+' user/s?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: action,
                    width: '25rem',
                })
                .then((result) => {
                    if(result.value){
                        this.form.delete('api/delete-users', {
                            params: {
                                user_ids: user_ids,
                                is_restore: this.is_restore,
                            }
                        })
                        .then((res) => {
                            Swal.fire(
                                'Deleted!',
                                'User/s successfully '+action+'d.',
                                'success'
                            );
                            Vue.prototype.$currentPageUsersTable = 1;
                            this.getUsers();
                        })
                        .catch((error) => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: 'User/s '+action+' failed.',
                            });
                        });
                    }
                });
            },
            deleteUser(test){
                console.log(test)
            }
        },
        created(){
            this.getUsers();
        },
    }
</script>
