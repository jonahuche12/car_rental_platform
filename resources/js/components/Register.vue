<template>
  <div>
    <h2>Register</h2>
    <form @submit.prevent="register">
      <!-- Your registration form fields here -->
      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script>

export default {
  methods: {
    async register() {
      try {
        // Make an API call to register the user
        await axios.post('/api/register', {
          // User registration data
        });

        // Use Vue Router to navigate to the login page after successful registration
        this.$router.push('/login');
      } catch (error) {
        // Handle registration error
      }
    },
  },
};

</script>
