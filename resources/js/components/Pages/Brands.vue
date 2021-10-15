<template>
    <div class="container mt-0">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <span class="h2 m-0 p-0 row justify-content-center mb-3">{{ this.page }}</span>
                <card-component classes="row" title="Search for brands">
                    <template v-slot:body="">
                        <div class="col-4">
                            <input-component id="inputId" idHelp="idHelp" optional
                                             textHelp="Optional. Enter the registration ID" title="ID">
                                <input id="inputId" v-model="search.id" aria-describedby="idHelp"
                                       class="form-control"
                                       placeholder="ID" type="number">
                            </input-component>
                        </div>
                        <div class="col-8">
                            <input-component id="inputName" idHelp="inputNameHelp" optional
                                             textHelp="Optional. Enter the brand name." title="Brand name">
                                <input id="inputName" v-model="search.name"
                                       aria-describedby="inputNameHelp"
                                       class="form-control" placeholder="Brand name" type="text">
                            </input-component>
                        </div>
                    </template>

                    <template v-slot:footer="">
                        <button class="btn btn-primary btn-sm float-end" type="submit"
                                @click="searchBrand">
                            Submit
                        </button>
                    </template>
                </card-component>

                <card-component classes="table-responsive-xxl" title="Brands List">
                    <template v-slot:body="">
                        <table-component :data="brands.data" :destroy="destroy" :show="show" :titles="titles"
                                         :update="update"></table-component>
                    </template>

                    <template v-slot:footer="">
                        <div class="row align-items-center align-middle justify-content-between">
                            <div class="col-md-auto pt-3">
                                <pagination-component>
                                    <li v-for="(obj, key) in brands.links" :key="key"
                                        :class="obj.active ? 'page-item active' : 'page-item'"
                                        @click="paginate(obj.url)">
                                        <a class="page-link" v-html="obj.label"></a>
                                    </li>
                                </pagination-component>
                            </div>
                            <div class="col-md-auto float-end">
                                <button class="btn btn-primary btn-sm float-end" data-bs-target="#modalAddBrand"
                                        data-bs-toggle="modal" type="button">
                                    Add
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>

        <modal-component id="modalAddBrand" modalTitle="Add Brand">
            <template v-slot:alert="">
                <alert-component v-if="$store.state.transaction.status === true" id="alert"
                                 :messages="$store.state.transaction.message"
                                 type="success"></alert-component>
                <alert-component v-if="$store.state.transaction.status === false"
                                 :messages="$store.state.transaction.message" type="danger"></alert-component>
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
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button" @click="removeMessage()">Close
                </button>
                <button class="btn btn-primary" type="button" @click="save()">Save</button>
            </template>
        </modal-component>

        <modal-component id="modalShowBrand" modalTitle="Show Brand">
            <template v-slot:alert=""></template>

            <template v-slot:body="">
                <section v-if="$store.state.item.image" class="mb-2">
                    <a :href="searchBrandGoogle($store.state.item.name)"
                       class="text-decoration-none row align-items-center justify-content-center m-0" target="_blank">
                        <img :alt="$store.state.item.name" :src="getImage($store.state.item.image)"
                             class="image"/>
                    </a>
                </section>

                <input-component :title="titles.id.title" :type="titles.id.type" optional>
                    <input :value="$store.state.item.id" class="form-control" disabled type="text">
                </input-component>

                <input-component :title="titles.name.title" :type="titles.name.type" optional>
                    <input :value="$store.state.item.name" class="form-control" disabled type="text">
                </input-component>

                <input-component :title="titles.created_at.title" :type="titles.created_at.type" optional>
                    <input :value="$store.state.item.created_at" class="form-control" disabled type="text">
                </input-component>
            </template>
            <template v-slot:footer="">
                <button class="btn btn-success" data-bs-dismiss="modal" type="button" @click="removeMessage()">
                    Close
                </button>
            </template>
        </modal-component>

        <modal-component id="modalDestroyBrand" modalTitle="Delete Brand">
            <template v-slot:alert="">
                <alert-component v-if="$store.state.transaction.status === true" id="alert"
                                 :messages="$store.state.transaction.message"
                                 type="success"></alert-component>
                <alert-component v-if="$store.state.transaction.status === false"
                                 :messages="$store.state.transaction.message"
                                 type="danger delete"></alert-component>
            </template>

            <template v-slot:body="">
                <section v-if="$store.state.item.image" class="mb-2">
                    <a :href="searchBrandGoogle($store.state.item.name)"
                       class="text-decoration-none row align-items-center justify-content-center m-0" target="_blank">
                        <img :alt="$store.state.item.name" :src="getImage($store.state.item.image)" class="image"/>
                    </a>
                </section>

                <input-component :title="titles.id.title" :type="titles.id.type" optional>
                    <input :value="$store.state.item.id" class="form-control" disabled type="text">
                </input-component>

                <input-component :title="titles.name.title" :type="titles.name.type" optional>
                    <input :value="$store.state.item.name" class="form-control" disabled type="text">
                </input-component>

                <input-component :title="titles.created_at.title" :type="titles.created_at.type" optional>
                    <input :value="$store.state.item.created_at" class="form-control" disabled type="text">
                </input-component>
            </template>
            <template v-slot:footer="">
                <button :class="$store.state.transaction.status === true ? 'btn btn-primary' : 'btn btn-secondary'"
                        data-bs-dismiss="modal" type="button" @click="removeMessage()">
                    Close
                </button>
                <button :class="$store.state.transaction.status === true ? 'btn btn-danger disabled' : 'btn btn-danger'"
                        class="btn btn-danger"
                        type="button"
                        @click="destroyBrand()">
                    Delete
                </button>
            </template>
        </modal-component>

        <modal-component id="modalUpdateBrand" modalTitle="Update Brand">
            <template v-slot:alert="">
                <alert-component v-if="$store.state.transaction.status === true" id="alert"
                                 :messages="$store.state.transaction.message"
                                 type="success"></alert-component>
                <alert-component v-if="$store.state.transaction.status === false"
                                 :messages="$store.state.transaction.message" type="danger"></alert-component>
            </template>

            <template v-slot:body="">
                <div class="row">
                    <div class="col-12">
                        <input-component id="updateName" idHelp="updateNameHelp"
                                         textHelp="Enter the brand name." title="Brand name"
                                         type="text">
                            <input id="updateName" v-model="$store.state.item.name" aria-describedby="updateNameHelp"
                                   class="form-control" placeholder="Brand name" required type="text">
                        </input-component>
                    </div>
                    <div class="col-12">
                        <input-component id="updateImage" idHelp="updateImageHelp"
                                         textHelp="Insert the brand image."
                                         title="Brand image" type="file">
                            <input id="updateImage" aria-describedby="updateImageHelp" class="form-control" required
                                   type="file" @change="loadImage($event)">
                        </input-component>
                    </div>
                </div>
            </template>

            <template v-slot:footer="">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button" @click="removeMessage()">
                    Close
                </button>
                <button class="btn btn-primary" type="button" @click="updateBrand()">Update</button>
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
            brands: {
                data: [],
                total: Number,
                per_page: Number,
                last_page: Number,
            },
            search: {
                id: '',
                name: '',
            },
            filterPaginate: '',
            filterBrand: '',
            titles: {
                id: {
                    title: 'ID',
                    type: 'text'
                },
                name: {
                    title: 'Name',
                    type: 'text'
                },
                image: {
                    title: 'Image',
                    type: 'image'
                },
                created_at: {
                    title: 'Creation date',
                    type: 'date'
                },
                updated_at: {
                    title: 'Update date',
                    type: 'date'
                }
            },
            show: {
                visible: true,
                dataToggle: 'modal',
                dataTarget: '#modalShowBrand'
            },
            update: {
                visible: true,
                dataToggle: 'modal',
                dataTarget: '#modalUpdateBrand'
            },
            destroy: {
                visible: true,
                dataToggle: 'modal',
                dataTarget: '#modalDestroyBrand'
            },
        };
    },

    props: {
        page: String,
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
        destroyBrand() {
            const confirmation = confirm('Are you sure you want to remove this record?');

            if (!confirmation)
                return false;

            const url = this.route_index_brand + '/' + this.$store.state.item.id;

            const formData = new FormData();
            formData.append('_method', 'delete');

            const settings = {
                headers: {
                    Accept: 'application/json',
                    Authorization: this.token
                }
            };

            axios.post(url, formData, settings)
                .then(response => {
                    this.$store.state.transaction.status = true;
                    this.$store.state.transaction.message = [];
                    this.$store.state.transaction.message.push(response.data.message);

                    if (this.brands.data.length === 1 && this.brands.last_page > 1)
                        this.paginate(this.route_index_brand + '?page=' + (this.brands.last_page - 1));
                    else
                        this.paginate(this.route_index_brand + '?page=' + this.brands.last_page);

                    setTimeout(() => {
                        $('#alert').css('display', 'none');
                        $('#modalDestroyBrand').modal('hide');
                    }, 3000);
                })
                .catch(errors => {
                    this.$store.state.transaction.status = false;
                    console.log(errors);
                    this.$store.state.transaction.message = [];
                    this.$store.state.transaction.message.push(errors.response.data.error);
                });
        },

        getImage(image) {
            let url;
            if (process.env.APP_ENV === 'production')
                url = 'https://';
            else
                url = 'http://';
            url += window.location.host + '/storage/' + image;
            return url;
        },

        loadList() {
            let url = this.route_index_brand + this.filterPaginate + this.filterBrand;

            let settings = {
                headers: {
                    Accept: 'application/json',
                    Authorization: this.token
                }
            };

            axios.get(url, settings)
                .then(response => {
                    this.brands = response.data;
                })
                .catch(errors => {
                    this.$store.state.transaction.status = false;
                    this.$store.state.transaction.message = [];
                    this.$store.state.transaction.message.push(errors.response.data.errors);
                });
        },

        loadImage(e) {
            this.image = e.target.files;
        },

        save() {
            if (!this.verifyRequiredInputs())
                return;

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

            axios.post(this.route_index_brand, formData, settings)
                .then(response => {
                    $('#name').val('');
                    $('#image').val([]);
                    this.name = '';
                    this.image = [];
                    this.$store.state.transaction.status = true;
                    this.$store.state.transaction.message.push(response.data.message);
                    let page = Math.floor(this.brands.total / this.brands.per_page + 1);
                    this.paginate(this.route_index_brand + '?page=' + page);
                    setTimeout(() => {
                        $('#alert').css('display', 'none');
                    }, 5000);
                })
                .catch(errors => {
                    this.$store.state.transaction.status = false;
                    this.$store.state.transaction.message = [];
                    this.$store.state.transaction.message.push(errors.response.data.errors);
                });
        },

        updateBrand() {
            this.name = this.$store.state.item.name;

            if (!this.verifyRequiredInputs())
                return;

            const url = this.route_index_brand + '/' + this.$store.state.item.id;

            const formData = new FormData();
            formData.append('_method', 'PATCH');
            formData.append('name', this.$store.state.item.name);
            formData.append('image', this.image[0]);

            const settings = {
                headers: {
                    Accept: 'application/json',
                    Authorization: this.token
                }
            };

            axios.post(url, formData, settings)
                .then(response => {
                    this.$store.state.transaction.status = true;
                    this.$store.state.transaction.message = [];
                    this.$store.state.transaction.message.push(response.data.message);
                    this.loadList();
                    setTimeout(() => {
                        $('#alert').css('display', 'none');
                        $('#modalUpdateBrand').modal('hide');
                        $('#updateName').val('');
                        $('#updateImage').val([]);
                    }, 3000);
                })
                .catch(errors => {
                    this.$store.state.transaction.status = false;
                    this.$store.state.transaction.message = [];
                    if(errors.response.data.errors.image)
                        this.$store.state.transaction.message.push(errors.response.data.errors.image);
                    if(errors.response.data.errors.name)
                        this.$store.state.transaction.message.push(errors.response.data.errors.name);
                });
        },

        verifyRequiredInputs() {
            if (this.name === '' && this.image.length === 0) {
                this.$store.state.transaction.status = false;
                this.$store.state.transaction.message = [];
                this.$store.state.transaction.message = {
                    name: ['The name field is required.'],
                    image: ['The image field is required.']
                };
                return false;
            }
            if (this.name === '') {
                this.$store.state.transaction.status = false;
                this.$store.state.transaction.message = [];
                this.$store.state.transaction.message = {
                    name: ['The name field is required.']
                };
                return false;
            }
            if (this.image.length === 0) {
                this.$store.state.transaction.status = false;
                this.$store.state.transaction.message = [];
                this.$store.state.transaction.message = {
                    image: ['The image field is required.']
                };
                return false;
            }
            return true;
        },

        removeMessage() {
            $('#alert').css('display', 'none');
        },

        paginate(url) {
            if (url) {
                this.filterPaginate = '?' + url.split('?')[1];
                this.loadList();
            }
        },

        searchBrand() {
            let filter = '';

            for (let key in this.search) {
                if (this.search[key]) {
                    if (filter !== '')
                        filter += ';';
                    if (key === 'name')
                        filter += key + ':like:%' + this.search[key] + '%';
                    else
                        filter += key + ':like:' + this.search[key];
                }
            }
            if (filter)
                this.filterBrand = '&filter=' + filter;
            else
                this.filterBrand = '';
            this.filterPaginate = '?page=1';
            this.loadList();
        },

        searchBrandGoogle(name) {
            const url = 'https://google.com/search?q=Car%20Brand%20';
            return url + name;
        },
    },

    mounted() {
        this.loadList();
    },
};
</script>

<style scoped>
img.image {
    max-width: 120px;
    border-radius: 50%;
}
</style>
