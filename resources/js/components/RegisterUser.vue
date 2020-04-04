<template>
    <div class="container">
        <el-card class="box-card mb-3">
            <div slot="header" class="clearfix">
                <span>Register User</span>
            </div>
            <div>
                <el-form ref="form" :model="form" label-width="120px">
                    <el-form-item label="Username">
                        <el-col :span="6">
                            <el-input v-model="form.username" :class="userNameError" placeholder="Username"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="Name">
                        <el-col :span="6">
                            <el-input v-model="form.firstname" :class="firstNameError" placeholder="Firstname"></el-input>
                        </el-col>
                        <el-col :span="6">
                            <el-input v-model="form.lastname" :class="lastNameError" placeholder="Lastname"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="Address">
                        <el-col :span="12">
                            <el-input 
                            v-model="form.address" 
                            :class="addressError"
                            type="textarea" 
                            resize="none" 
                            :autosize="{ minRows: 4, maxRows: 6}" 
                            placeholder="Complete Address">
                            </el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="Postcode">
                        <el-col :span="6">
                            <el-input v-model="form.postcode" :class="postcodeError" placeholder="Postcode"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="Phone Number">
                        <el-col :span="6">
                            <el-input v-model="form.contact" :class="contactError" placeholder="Contact No."></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="Email">
                        <el-col :span="12">
                            <el-input v-model="form.email" :class="emailError" placeholder="Email Address"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="Role">
                        <el-col :span="6">
                            <el-select v-model="form.role" placeholder="User Role">
                                <el-option
                                v-for="role in $user_roles"
                                :key="role.id"
                                :label="role.role"
                                :value="role.id">
                                </el-option>
                            </el-select>
                        </el-col>
                    </el-form-item>
                    <el-row class="text-right">
                        <el-button type="primary" v-on:click="storeUser">Register</el-button>
                        <el-button type="danger" v-on:click="resetForm">Reset</el-button>
                    </el-row>
                </el-form>
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
                    username: '',
                    firstname: '',
                    lastname: '',
                    address: '',
                    postcode: '',
                    contact: '',
                    email: '',
                    role: '',
                }),
                errors: {},
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
                        this.errors = {};
                        this.form.post('api/user')
                        .then((res) => {
                            this.form.reset();
                            Swal.fire(
                                'Registered!',
                                'User successfully registered.',
                                'success'
                            );
                        })
                        .catch((error) => {
                            if(error.response){
                                this.errors = error.response.data.errors;
                            };
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: 'User registration failed.',
                            });
                        });
                    }
                });
            },
            resetForm(){
                Swal.fire({
                    text: 'Are you sure you want to reset this form?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Reset',
                    width: '25rem',
                })
                .then((result) => {
                    if(result.value){
                        this.form.reset();
                        this.errors = {};
                    }
                });
            },
        },
        created(){

        },
        computed: {
            userNameError(){
                if(this.errors.username) return 'with-error';
            },
            firstNameError(){
                if(this.errors.firstname) return 'with-error';
            },
            lastNameError(){
                if(this.errors.lastname) return 'with-error';
            },
            addressError(){
                if(this.errors.address) return 'with-error';
            },
            postcodeError(){
                if(this.errors.postcode) return 'with-error';
            },
            contactError(){
                if(this.errors.contact) return 'with-error';
            },
            emailError(){
                if(this.errors.email) return 'with-error';
            },
        }
    }
</script>
