<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span class="h4 m-0 p-0 row justify-content-center">{{ this.page }}</span>
                    </div>
                    <div class="card-body">
                        <span>Hi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'page': String
    }
}
</script>
