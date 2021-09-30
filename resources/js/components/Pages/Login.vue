<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form @submit.prevent="login($event)">
                            <input :value="csrf_token" name="_token" type="hidden">

                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label text-md-right" for="email">E-Mail</label>

                                <div class="col-md-6">
                                    <input id="email" v-model="email"
                                           autocomplete="email" autofocus
                                           class="form-control" name="email" required type="email">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label text-md-right" for="password">Password</label>

                                <div class="col-md-6">
                                    <input id="password" v-model="password"
                                           autocomplete="current-password" class="form-control"
                                           name="password" required type="password">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input id="remember" class="form-check-input" name="remember" type="checkbox">

                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-primary" type="submit">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="#">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['csrf_token', 'route_login'],

    data() {
        return {
            'email': '',
            'password': '',
        };
    },

    methods: {
        login(e) {
            let settings = {
                method: 'POST',
                body: new URLSearchParams({
                    'email': this.email,
                    'password': this.password,
                })
            };

            fetch(this.route_login, settings)
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        document.cookie = 'token=' + data.token + ';SameSite=Lax';
                        e.target.submit();
                    }
                });
        }
    }
}
</script>
