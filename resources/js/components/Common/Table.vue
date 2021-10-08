<template>
    <table class="table table-hover table-bordered table-borderless mb-0 align-middle text-center">
        <thead class="table-primary">
            <tr>
                <th v-for="(value, key) in titles" :key="key" class="align-middle" scope="col">
                    {{ value.title }}
                </th>
                <th v-if="show.visible || update.visible || destroy.visible" class="align-middle" scope="col">Options
                </th>
            </tr>
        </thead>
        <tbody class="table-light align-middle">
            <tr v-for="obj in filteredData" :key="obj.id">
                <th class="align-middle" scope="row">{{ obj.id }}</th>
                <td v-for="(value, key) in obj" v-if="key !== 'id'" :key="obj.id + ' - ' + key" class="align-middle">
                    <span v-if="titles[key].type === 'image'">
                        <a :href="searchBrand(obj.name)" class="text-decoration-none" target="_blank">
                            <img :alt="obj.name" :src="getImage(value)" class="image"/>
                        </a>
                    </span>
                    <span v-if="titles[key].type === 'text'">{{ value }}</span>
                    <span v-if="titles[key].type === 'date'">
                        {{ filteredDate(value)[0] }}
                        <br>
                        {{ filteredDate(value)[1] }}
                    </span>
                </td>
                <td v-if="show.visible || update.visible || destroy.visible" class="align-middle">
                    <button v-if="show.visible" :data-bs-target="show.dataTarget"
                            :data-bs-toggle="show.dataToggle"
                            class="btn btn-outline-primary btn-sm" @click="setStore(obj)">
                        Show
                    </button>
                    <button v-if="update.visible" :data-bs-target="update.dataTarget"
                            :data-bs-toggle="update.dataToggle"
                            class="btn btn-outline-warning btn-sm" @click="setStore(obj)">
                        Update
                    </button>
                    <button v-if="destroy.visible" :data-bs-target="destroy.dataTarget"
                            :data-bs-toggle="destroy.dataToggle"
                            class="btn btn-outline-danger btn-sm" @click="setStore(obj)">
                        Delete
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        data: {},
        titles: {},
        show: {
            visible: Boolean,
            dataToggle: String,
            dataTarget: String
        },
        update: {
            visible: Boolean,
            dataToggle: String,
            dataTarget: String
        },
        destroy: {
            visible: Boolean,
            dataToggle: String,
            dataTarget: String
        },
    },

    methods: {
        searchBrand(name) {
            const url = 'https://google.com/search?q=Car%20Brand%20';
            return url + name;
        },

        getImage(image) {
            let url = window.location.protocol + '//' + window.location.host + '/storage/';
            return url + image;
        },

        filteredDate(date) {
            let filtered = [];
            filtered.push(date.substring(0, 10));
            filtered.push(date.substring(11, date.length));
            return filtered;
        },

        setStore(obj) {
            this.$store.state.transaction.status = Boolean;
            this.$store.state.transaction.message = [];
            this.$store.state.item = obj;
        },
    },

    computed: {
        filteredData() {
            let fields = Object.keys(this.titles);
            let filteredData = [];

            this.data.map((item, key) => {
                let filteredItem = {};
                fields.forEach(field => {
                    filteredItem[field] = item[field];
                });
                filteredData.push(filteredItem);
            });
            return filteredData;
        }
    }
};
</script>

<style scoped>
img.image {
    max-width: 50px;
    max-height: 50px;
    border-radius: 50%;
}
</style>
