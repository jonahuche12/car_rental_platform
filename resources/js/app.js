import Vue from 'vue';
import axios from 'axios';


const app = new Vue({
    el: '#app',
    data() {
        return {
            users: [],
            
        };
    },
    mounted() {
        // axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fetch user data directly in the Vue component
        axios.get('/api/users')
            .then(response => {
                
                this.users = response.data;
                console.log('Users:', this.users);
            })
            .catch(errors => {
                console.log(errors);
                // this.loading = false;
            });
    }
});
