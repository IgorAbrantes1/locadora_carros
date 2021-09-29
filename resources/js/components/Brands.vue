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
                            <input-component id="inputName" idHelp="inputNameHelp"
                                             optional
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
                <card-component classes="table-responsive" title="Brands List">
                    <template v-slot:body="">
                        <table-component></table-component>
                    </template>

                    <template v-slot:footer="">
                        <button class="btn btn-primary btn-sm float-end" data-bs-target="#modalBrand"
                                data-bs-toggle="modal"
                                type="button">Add
                        </button>
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
                        <input-component id="name" idHelp="nameHelp" textHelp="Enter the brand name."
                                         title="Brand name">
                            <input id="name" v-model="name" aria-describedby="nameHelp"
                                   class="form-control" placeholder="Brand name" required type="text">
                        </input-component>
                    </div>
                    <div class="col-12">
                        <input-component id="file" idHelp="fileHelp"
                                         textHelp="Insert the brand image." title="Brand image"
                                         type="file">
                            <input id="file" aria-describedby="fileHelp" class="form-control" required
                                   type="file" @change="loadImage($event)">
                        </input-component>
                    </div>
                </div>
            </template>

            <template v-slot:footer="">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                <button class="btn btn-primary" type="button" @click="save()">Save changes</button>
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
            messages: []
        };
    },

    props: {
        'page': String,
        'route_store_brand': String
    },

    computed: {
        token() {
            let token = document.cookie.split(';').find(index => {
                return index.includes('token=');
            });

            token = token.split('=')[1];
            token = 'Bearer ' + token;

            return token;
        }
    },

    methods: {
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
            } else {
                if (this.name === '') {
                    this.status = false;
                    this.messages = [];
                    this.messages = {
                        name: ['The name field is required.']
                    };
                    return;
                } else {
                    if (this.image.length === 0) {
                        this.status = false;
                        this.messages = [];
                        this.messages = {
                            image: ['The image field is required.']
                        };
                        return;
                    }
                }
            }

            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('image', this.image[0]);

            let settings = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            axios.post(this.route_store_brand, formData, settings)
                .then(response => {
                    this.messages = [];
                    document.querySelector('#name').value = '';
                    document.querySelector('#file').value = '';
                    this.status = true;
                    this.messages.push(response.data.message);
                    setTimeout(function () {
                        $('#alert').css('display', 'none');
                    }, 5000);
                })
                .catch(errors => {
                    this.messages = [];
                    this.status = false;
                    this.messages = errors.response.data.errors;
                });
        }
    }
}
</script>
