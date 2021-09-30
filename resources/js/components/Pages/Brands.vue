<template>
    <div class="container mt-0">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <span class="h2 m-0 p-0 row justify-content-center mb-3">{{ this.page }}</span>
                <!-- Search Card -->
                <card-component classes="row" title="Search for brands">
                    <template v-slot:body="">
                        <div class="col-4">
                            <input-component id="inputId" idHelp="idHelp" optional
                                             textHelp="Optional. Enter the registration ID" title="ID">
                                <input id="inputId" aria-describedby="idHelp" class="form-control"
                                       placeholder="ID"
                                       type="number">
                            </input-component>
                        </div>
                        <div class="col-8">
                            <input-component id="inputName" idHelp="inputNameHelp" optional
                                             textHelp="Optional. Enter the brand name." title="Brand name">
                                <input id="inputName" aria-describedby="inputNameHelp"
                                       class="form-control"
                                       placeholder="Brand name" type="text">
                            </input-component>
                        </div>
                    </template>

                    <template v-slot:footer="">
                        <button class="btn btn-primary btn-sm float-end" type="submit">Submit</button>
                    </template>
                </card-component>
                <!-- Search Card -->

                <!-- Brands Listing Card -->
                <card-component classes="table-responsive-xxl" title="Brands List">
                    <template v-slot:body="">
                        <table-component :data="brands.data" :titles="titles"></table-component>
                    </template>

                    <template v-slot:footer="">
                        <div class="row align-items-center align-middle">
                            <div class="col-10 pt-3">
                                <pagination-component>
                                    <li v-for="(obj, key) in brands.links" :key="key"
                                        :class="obj.active ? 'page-item active' : 'page-item'"
                                        @click="paginate(obj.url)">
                                        <a class="page-link" v-html="obj.label"></a>
                                    </li>
                                </pagination-component>
                            </div>
                            <div class="col">
                                <button class="btn btn-primary btn-sm float-end" data-bs-target="#modalBrand"
                                        data-bs-toggle="modal" type="button">
                                    Add
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- End Brands Listing Card -->
            </div>
        </div>

        <modal-component id="modalBrand" modalTitle="Add Brand">
            <template v-slot:alert="">
                <alert-component v-if="status === true" id="alert" :messages="messages"
                                 type="success"></alert-component>
                <alert-component v-if="status === false" :messages="messages" type="danger"></alert-component>
            </template>

            <template v-slot:body="">
                <div class="row">
                    <div class="col-12">
                        <input-component id="name" idHelp="nameHelp"
                                         textHelp="Enter the brand name." title="Brand name"
                                         type="text">
                            <input id="name" v-model="name" aria-describedby="nameHelp"
                                   class="form-control" placeholder="Brand name" required type="text">
                        </input-component>
                    </div>
                    <div class="col-12">
                        <input-component id="image" idHelp="imageHelp"
                                         textHelp="Insert the brand image." title="Brand image"
                                         type="file">
                            <input id="image" aria-describedby="imageHelp" class="form-control" required type="file"
                                   @change="loadImage($event)">
                        </input-component>
                    </div>
                </div>
            </template>

            <template v-slot:footer="">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                <button class="btn btn-primary" type="button" @click="save()">Save</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            image: [],
            status: Boolean,
            messages: [],
            brands: {data: []},
            titles: {
                id: {title: 'ID', type: 'text'},
                name: {title: 'Name', type: 'text'},
                image: {title: 'Image', type: 'image'},
                created_at: {title: 'Creation date', type: 'date'},
                updated_at: {title: 'Update date', type: 'date'}
            }
        };
    },

    props: {
        page: String,
        route_store_brand: String,
        route_index_brand: String,
    },

    computed: {
        token() {
            let token = document.cookie.split(';').find(index => {
                return index.includes('token=');
            });

            token = token.split('=')[1];
            token = 'Bearer ' + token;

            return token;
        },
    },

    methods: {
        loadList(url = this.route_index_brand) {
            let settings = {
                headers: {
                    Accept: 'application/json',
                    Authorization: this.token
                }
            };

            axios.get(url, settings)
                .then(response => {
                    this.brands = response.data;
                    console.log(this.brands);
                })
                .catch(errors => {
                    console.log(errors);
                });
        },

        loadImage(e) {
            this.image = e.target.files;
        },

        save() {
            if (this.name === '' && this.image.length === 0) {
                this.status = false;
                this.messages = [];
                this.messages = {
                    name: ['The name field is required.'],
                    image: ['The image field is required.']
                };
                return;
            }
            if (this.name === '') {
                this.status = false;
                this.messages = [];
                this.messages = {
                    name: ['The name field is required.']
                };
                return;
            }
            if (this.image.length === 0) {
                this.status = false;
                this.messages = [];
                this.messages = {
                    image: ['The image field is required.']
                };
                return;
            }

            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('image', this.image[0]);

            let settings = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    Accept: 'application/json',
                    Authorization: this.token
                }
            };

            axios.post(this.route_store_brand, formData, settings)
                .then(response => {
                    $('#name').val('');
                    $('#image').val([]);
                    this.name = '';
                    this.image = [];
                    this.messages = [];
                    this.status = true;
                    this.messages.push(response.data.message);
                    this.loadList(this.brands.last_page_url);
                    setTimeout(function () {
                        $('#alert').css('display', 'none');
                    }, 5000);
                })
                .catch(errors => {
                    this.messages = [];
                    this.status = false;
                    this.messages = errors.response.data.errors;
                });
        },

        paginate(url) {
            if (url) {
                this.loadList(url);
            }
        }
    },

    mounted() {
        this.loadList();
    },
};
</script>

<style scoped>

</style>
