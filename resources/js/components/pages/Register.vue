<template>
    <div class="container my-5">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card border-0 rounded-4 bg-secondary shadow-sm">
                    <div class="card-body">
                        <h1 class="text-center">Register</h1>
                        <hr />
                        <form action="javascript:void(0)" @submit="register" class="row" method="post">
                            <div class="col-12" v-if="Object.keys(validationErrors).length > 0">
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row gy-3 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="name" v-model="form.name" class="form-control" name="name" id="name"
                                            placeholder="Name" required>
                                        <label for="name" class="form-label">Name</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="username" v-model="form.username" class="form-control" name="username"
                                            id="username" placeholder="Username" required>
                                        <label for="username" class="form-label">Username</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="text" v-model="form.phone" class="form-control" name="phone" id="phone"
                                            placeholder="Phone" required>
                                        <label for="phone" class="form-label">Phone</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="email" v-model="form.email" class="form-control" name="email"
                                            id="email" placeholder="Email" required>
                                        <label for="email" class="form-label">Email</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" v-model="form.password" class="form-control" name="password"
                                            id="password" placeholder="Password" required>
                                        <label for="password" class="form-label">Password</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" v-model="form.password_confirmation" class="form-control"
                                            name="password_confirmation" id="password_confirmation" placeholder="Password"
                                            required>
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-lg" type="submit" :disabled="processing">
                                            {{ processing ? "Please wait" : "Register" }}</button>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 text-center mt-2">
                                <label>Already have an account? <router-link :to="{ name: 'login' }">Login
                                        Now!</router-link></label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
    name: 'register',
    data() {
        return {
            form: {
                name: "",
                username: "",
                email: "",
                phone: "",
                password: "",
                password_confirmation: ""
            },
            validationErrors: {},
            processing: false
        }
    },
    methods: {
        ...mapActions({
            signIn: 'auth/login'
        }),
        async register() {
            this.processing = true
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/api/auth/register', this.form).then(({ data }) => {

                this.validationErrors = {}
                this.$swal(
                    'Success!',
                    'Register Success',
                    'success'
                ).then(() => {
                    this.signIn(this.form)
                })
            }).catch(({ response }) => {
                console.log(response)
                if (response.status === 422) {
                    this.validationErrors = response.data.errors
                } else {
                    this.validationErrors = [['Register failed']]
                }
            }).finally(() => {
                this.processing = false
            })

        }
    }
}
</script>
