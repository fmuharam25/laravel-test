<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>User List</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-bar my-2">
                            <div class="row">
                                <div class="col-md-2">

                                    <select class="form-select" v-model="length" @change="resetPagination()">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                    </select>

                                </div>
                                <div class="col-md-4 position-absolute end-0">
                                    <input type="text" v-model="search" placeholder="Search..." @input="resetPagination()"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered " style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th v-for="column in columns" :key="column.name" @click="sortBy(column.name)"
                                            :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                                            style="width: 40%; cursor:pointer;">
                                            {{ column.label }}
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in paginatedUsers" :key="user.id">
                                        <td class="text-capitalize">{{ user.name }}</td>
                                        <td>{{ user.username }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.phone }}</td>

                                        <td class="text-capitalize">{{ user.role }}</td>
                                        <td class="text-capitalize">{{ user.is_subscribe }}</td>
                                        <td>

                                            <a class="btn btn-danger btn-sm" href="#" @click="deleteUser(user.id)"
                                                v-if="user.name != auth.name && user.role == 'guest'">Delete</a>
                                            <a class="btn btn-sm text-capitalize" href="#"
                                                :class="{ 'btn-warning': user.is_subscribe == 'subscribed', 'btn-primary': user.is_subscribe == 'unsubscribe' }"
                                                @click="toggleSubscribe(user.id, user.is_subscribe)"
                                                v-if="user.name != auth.name && user.role == 'guest'">{{ user.is_subscribe
                                                    == 'subscribed' ? 'Unsubscribe' : 'Subscribe' }}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="talbe-foot-bar">
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="">
                                        {{ pagination.from }} - {{ pagination.to }} of {{ filteredUsers.length }}
                                        <span v-if="`filteredUsers.length < pagination.total`"></span>
                                    </span>
                                </div>
                                <div class="col-md-8 text-end">
                                    <a v-if="pagination.prevPage" class="btn btn-primary" @click="--pagination.currentPage">
                                        &lt;&lt;
                                    </a>
                                    <a class="btn btn-primary" v-else disabled>
                                        &lt;&lt;
                                    </a>
                                    <span class="mx-2"></span>
                                    <a v-if="pagination.nextPage" class="btn btn-sm btn-primary "
                                        @click="++pagination.currentPage">
                                        &gt;&gt;
                                    </a>
                                    <a class="btn  btn-primary" v-else disabled>
                                        &gt;&gt;
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import router from '../../router';

export default {
    created() {
        this.getUsers();
        this.$emit('reloadUsers', () => {
            this.getUsers();
        })
    },
    data() {
        let sortOrders = {};
        let columns = [
            { label: 'Name', name: 'name' },
            { label: 'Username', name: 'username' },
            { label: 'Email', name: 'email' },
            { label: 'Phone', name: 'phone' },
            { label: 'Role', name: 'role' },
            { label: 'Subscribe', name: 'is_subscribe' },
        ];
        columns.forEach((column) => {
            sortOrders[column.name] = -1;
        });
        return {
            auth: this.$store.state.auth.user,
            users: [],
            columns: columns,
            sortKey: 'name',
            sortOrders: sortOrders,
            length: 10,
            search: '',
            tableShow: {
                showdata: true,
            },
            pagination: {
                currentPage: 1,
                total: '',
                nextPage: '',
                prevPage: '',
                from: '',
                to: ''
            },
        }
    },
    methods: {
        deleteUser(id) {
            this.$swal({
                title: "Delete this data?",
                text: "Are you sure? You won't be able to revert this!",

                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Yes, Delete it!"
            }).then((res) => {
                if (res.isConfirmed) {
                    axios.delete(`/api/user/${id}`).then(() => {
                        this.$emit('reloadUsers')
                        this.$swal(
                            'Success!',
                            'User deleted',
                            'success'
                        )
                       
                    }).catch(() => {
                        this.$swal('Failed', 'There was something wrong', 'warning');
                    }).finally(()=>{
                        router.go()
                    });
                }
            })
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
                    axios.put(`/api/user/${id}`, { 'is_subscribe': subs }).then(() => {
                        this.$emit('reloadUsers')
                        
                    }).catch((e) => {
                        console.log(e);
                        this.$swal('Failed', 'There was something wrong', 'warning');
                    }).finally(()=>{
                        router.go()
                    });
                }
            })
        },
        getUsers() {
            axios.get('/api/user/', { params: this.tableShow })
                .then(({ data }) => {

                    this.users = data.data;
                    this.pagination.total = this.users.length;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        paginate(array, length, pageNumber) {

            this.pagination.from = array.length ? ((pageNumber - 1) * length) + 1 : ' ';
            this.pagination.to = pageNumber * length > array.length ? array.length : pageNumber * length;
            this.pagination.prevPage = pageNumber > 1 ? pageNumber : '';
            this.pagination.nextPage = array.length > this.pagination.to ? pageNumber + 1 : '';

            return array.slice((pageNumber - 1) * length, pageNumber * length);
        },
        resetPagination() {
            this.pagination.currentPage = 1;
            this.pagination.prevPage = '';
            this.pagination.nextPage = '';
        },
        sortBy(key) {
            this.resetPagination();
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },
        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value)
        },
    },
    computed: {
        filteredUsers() {
            let users = this.users;
            if (this.search) {
                users = users.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                users = users.slice().sort((a, b) => {
                    let index = this.getIndex(this.columns, 'name', sortKey);
                    a = String(a[sortKey]).toLowerCase();
                    b = String(b[sortKey]).toLowerCase();
                    if (this.columns[index].type && this.columns[index].type === 'date') {
                        return (a === b ? 0 : new Date(a).getTime() > new Date(b).getTime() ? 1 : -1) * order;
                    } else if (this.columns[index].type && this.columns[index].type === 'number') {
                        return (+a === +b ? 0 : +a > +b ? 1 : -1) * order;
                    } else {
                        return (a === b ? 0 : a > b ? 1 : -1) * order;
                    }
                });
            }
            return users;
        },
        paginatedUsers() {
            return this.paginate(this.filteredUsers, this.length, this.pagination.currentPage);
        }
    }
};
</script>

