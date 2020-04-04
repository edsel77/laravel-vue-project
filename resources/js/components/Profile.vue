<template>
    <div class="container">
        <el-card class="box-card mb-3">
            <div slot="header" class="clearfix">
                <span>{{ name }}</span>
                <router-link :to="'/update-user/'+$route.params.user_id">
                    <el-button style="float: right; padding: 3px 0" type="text">Update Profile</el-button>
                </router-link>
            </div>
            <div v-for="(data, index) in userData" v-show="index != 'id'" :key="data.id" class="item-list">
                <span>{{ detail(index) + ': ' + value(index, data) }}</span>
                <hr>
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
                name: '',
                userData: {},
            }
        },
        methods: {
            getUserInfo(){
                axios.get('/api/user/'+this.$route.params.user_id)
                .then((res) => {
                    this.userData = res.data;
                    this.name = res.data.firstname + ' ' + res.data.lastname;
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            detail(index){
                return index.charAt(0).toUpperCase() + index.slice(1);
            },
            value(index, data){
                if(index == 'role') data = _.find(this.$user_roles, { 'id': data }).role;
                return data;

            }
        },
        created(){
            this.getUserInfo();
        },
    }
</script>
