<template>
    <table class="table table-hover table-bordered table-borderless table-striped mb-0">
        <thead class="table-info text-center">
            <tr>
                <th v-for="(title, key) in titles" :key="key" class="text-uppercase" scope="col">
                    <span v-if="title === 'created_at' || title === 'updated_at' || title === 'deleted_at'">
                        {{
                            title.substring(0, title.length - 3) + ' ' + title.substring(title.length - 2, title.length)
                        }}
                    </span>
                    <span v-else>{{ title }}</span>
                </th>
            </tr>
        </thead>
        <tbody class="table-light text-center">
            <tr v-for="obj in data.data" :key="obj.id">
                <th scope="row">{{ obj.id }}</th>
                <td v-for="(value, key) in obj" v-if="titles.includes(key) && key !== 'id'" :key="obj.id + ' - ' + key">
                    <span v-if="key === 'image'">
                        <a :href="searchBrand(obj.name)" class="text-decoration-none" target="_blank">
                            <img :alt="obj.name" :src="getImage(value)" class="image"/>
                        </a>
                    </span>
                    <span v-else>
                        <span v-if="key === 'deleted_at' && value === null">-</span>
                        <span v-else>{{ value }}</span>
                    </span>
                </td>

                <!--<th scope="row">{{ value.id }}</th>
                <td>{{ value.name }}</td>
                <td>
                    <a :href="searchBrand(value.name)" class="text-decoration-none" target="_blank">
                        <img class="image" :alt="value.name" :src="getImage(value.image)"/>
                    </a>
                </td>-->
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        data: [],
        titles: []
    },

    methods: {
        searchBrand(name) {
            return 'https://google.com/search?q=Car%20Brand%20' + name;
        },

        getImage(image) {
            let url = window.location.protocol + '//' + window.location.host + '/storage/';
            return url + image;
        }
    }
}
</script>

<style scoped>
img.image {
    width: 30px;
}
</style>
