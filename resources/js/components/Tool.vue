<template>
    <div>
        <h1 class="mb-3 text-90 font-normal text-2xl flex-no-shrink">API Inspector</h1>
        <div class="card mb-6 flex overflow-hidden">
            <div class="bg-30 text-purple-lighter flex-none w-1/4 pb-6">
                <div class="mb-2 mt-3 px-4 flex justify-between">
                    <h1 class="font-semibold text-xl leading-tight mb-1 truncate">API Routes</h1>
                </div>
                <div class="mb-8 overflow-hidden">
                    <div
                        v-for="route in routes"
                        :key="route.uri"
                        @click="selectRoute(route)"
                        class="cursor-pointer m-2 hover:bg-primary-30% rounded text-sm py-2 px-1 hover:text-blue-dark"
                    >{{ route.uri }}</div>
                </div>
            </div>
            <div class="flex-1 flex flex-col bg-white overflow-hidden">
                <div class="border-b px-6 py-4">
                    <div class="flex w-full justify-between items-center">
                        <h3
                            class="text-grey-darkest mb-1 font-extrabold"
                        >{{ route.uri || 'Select Route from Left Sidebar' }}</h3>
                        <button v-show="route" @click="run" class="btn btn-default btn-primary">Run</button>
                    </div>
                    <div class="w-full block" v-if="route && route.parameters.length > 0">
                        <p class="strong mb-4">Parameters</p>
                        <div
                            class="flex items-center mb-2"
                            v-for="parameter in route.parameters"
                            :key="parameter"
                        >
                            <div class="w-1/5">
                                <label
                                    class="block text-gray-500 uppercase tracking-wide font-bold md:text-right pr-4"
                                    :for="parameter"
                                >{{ parameter }}</label>
                            </div>
                            <div class="w-full">
                                <input
                                    class="bg-30 appearance-none border border-80 rounded w-full py-2 px-4 leading-tight focus:outline-none"
                                    type="text"
                                    :id="parameter"
                                    v-model="form[parameter]"
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 flex-1 overflow-y-scroll h-full max-h-screen" v-if="route">
                    <svg
                        v-if="loading"
                        class="spin mb-6"
                        width="72"
                        height="72"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"
                        ></path>
                    </svg>
                    <tree-view
                        v-if="!loading && response"
                        :data="response"
                        :options="{maxDepth: 3}"
                        class="h-full"
                    ></tree-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            routes: {},
            route: '',
            form: {},
            response: '',
            loading: false,
            token: '',
        }
    },

    mounted() {
        this.getToken();
        this.getRoutes();
    },

    methods: {
        getRoutes() {
            Nova.request().get('/nova-vendor/api-inspector/routes')
                .then(response => {
                    this.routes = response.data;
                });
        },
        getToken() {
            Nova.request().get('/nova-vendor/api-inspector/token/' + this.resourceId)
                .then(response => {
                    this.token = response.data;
                });
        },
        selectRoute(route) {
            this.response = '';
            this.form = {};
            this.route = route;
        },
        run() {
            this.loading = true;
            var url = this.route.uri;

            _.forEach(this.form, function(value, parameter) {
                url = url.replace('{' + parameter + '}', value);
            });

            axios.get('/nova-vendor/api-inspector/token/' + this.resourceId)
                .then(response => {
                    this.token = response.data;
                    
                    axios.get('/' + url, { headers: { Authorization: 'Bearer ' + this.token.accessToken } })
                        .then(response => {
                            this.loading = false;
                            this.response = response.data;
                        });
                });
        }
    }
}
</script>
