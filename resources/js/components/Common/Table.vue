<template>
    <table class="table table-hover table-bordered table-borderless mb-0 align-middle text-center">
        <thead class="table-primary">
            <tr>
                <th v-for="(value, key) in titles" :key="key" scope="col">
                    {{ value.title }}
                </th>
            </tr>
        </thead>
        <tbody class="table-light">
            <tr v-for="obj in filteredData" :key="obj.id">
                <th scope="row">{{ obj.id }}</th>
                <td v-for="(value, key) in obj" v-if="key !== 'id'" :key="obj.id + ' - ' + key">
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
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        data: {},
        titles: {}
    },

    methods: {
        getType(value) {
            return typeof (value);
        },

        searchBrand(name) {
            return 'https://google.com/search?q=Car%20Brand%20' + name;
        },

        getImage(image) {
            let url = window.location.protocol + '//' + window.location.host + '/storage/';
            return url + image;
        },

        filteredDate(data) {
            let filtered = [];
            filtered.push(data.substring(0, 10));
            filtered.push(data.substring(11, data.length));
            return filtered;
        }
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
    width: 50px;
}
</style>
