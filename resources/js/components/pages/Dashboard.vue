<template>
    <div class="container">
        <div class="row">
            <div class="col-8 offset-2">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-capitalize">Welcome <b>{{ user.username }}</b></p>
                        <p class="mb-0 text-capitalize">Status : {{ user.is_subscribe }}</p>
                        <a class="btn btn-sm text-capitalize" href="#"
                            :class="{ 'btn-warning': user.is_subscribe == 'subscribed', 'btn-primary': user.is_subscribe == 'unsubscribe' }"
                            @click="toggleSubscribe(user.id, user.is_subscribe)">{{ user.is_subscribe
                                == 'subscribed' ? 'Click To Unsubscribe' : 'Click To Subscribe' }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import router from '../../router';

export default {
    name: "dashboard",
    created() {
        this.getUser();
    },
    data() {
        return {
            auth: this.$store.state.auth.user,
            user: [],
        }
    },
    methods: {
        getUser() {
            axios.get(`/api/user/${this.auth.id}`)
                .then(({ data }) => {
                    
                    this.user = data.data;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        toggleSubscribe(id, is_subscribe) {
            this.$swal({
                title: "Warning",
                text: "Are you sure wanto to change subscription status?",

                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Yes"
            }).then((res) => {
                let subs = is_subscribe == 'subscribed' ? 'unsubscribe' : 'subscribed';
                if (res.isConfirmed) {
                    axios.put(`/api/user/${id}`, { 'is_subscribe': subs }).then(({data}) => {
                        this.user = data.data;
                    }).catch((e) => {
                        
                        this.$swal('Failed', 'There was something wrong', 'warning');
                    })
                }
            })
        },
    }
}
</script>
