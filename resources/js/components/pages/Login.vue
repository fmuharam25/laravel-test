<template>
    <section class=" py-3 py-md-5 py-xl-8">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-12 col-md-6 col-xl-7">
                    <div class="d-flex justify-content-center">
                        <div class="col-12 col-xl-9">
                            <img class="img-fluid rounded mb-4" loading="lazy"
                                src="https://assets-global.website-files.com/63329fb5ec1e5cdf02d0af1b/63351388d9f415e2afbd7380_main-logo-growmodo-black.svg"
                                width="245" height="80" alt="Logo">
                            <hr class="border-primary-subtle mb-4">
                            <h2 class="h1 mb-4">Join a fast-growing team of rockstar creatives and code wizards who are
                                passionate about making customers say “WOW”.</h2>
                            <p class="lead mb-5">If you believe you have what it takes and want to grow your career instead
                                of just finding another job - you are at the right place!</p>
                            <div class="text-endx">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                    class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                    <path
                                        d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-5">
                    <div class="card border-0 rounded-4 bg-secondary">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4">
                                        <h3>Sign in</h3>
                                        <p>Don't have an account? <a href="#!">Sign up</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12" v-if="Object.keys(validationErrors).length > 0">
                                <div class="alert alert-danger">
                                    <ul class="mb-0 list-unstyled">
                                        <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
                                    </ul>
                                </div>
                            </div>
                            <form action="javascript:void(0)" method="post">
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="username" v-model="form.username" class="form-control"
                                                name="username" id="username" placeholder="Username / Email" required>
                                            <label for="username" class="form-label">Username / Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" v-model="form.password" class="form-control"
                                                name="password" id="password" placeholder="Password" required>
                                            <label for="password" class="form-label">Password</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-lg" type="submit" :disabled="processing"
                                                @click="login">
                                                {{ processing ? "Please wait" : "Log in now" }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { mapActions } from 'vuex'
export default {
    name: "login",
    data() {
        return {
            form: {
                username: "",
                password: ""
            },
            validationErrors: {},
            processing: false
        }
    },
    methods: {
        ...mapActions({
            signIn: 'auth/login'
        }),
        async login() {
            this.processing = true
            await axios.get('/sanctum/csrf-cookie')
            await this.signIn(this.form).then((res) => {
                if (res.status === 422) {
                    this.validationErrors = res.data.errors
                } else {
                    this.validationErrors = [['Invalid username or password']]
                }
            }).finally(() => {
                this.processing = false
            })
        },
    }
}
</script>
