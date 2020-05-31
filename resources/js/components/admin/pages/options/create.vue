<template>
    <ValidationObserver ref="observer">
        <v-form>
            <v-row>
                <v-col cols="12" md="2">
                    <validation-provider v-slot="{ errors }" name="table">
                        <v-text-field v-model="form.table"
                                      label="table"
                                      :error-messages="errors"
                        />
                    </validation-provider>
                </v-col>
                <v-col cols="12" md="2">
                    <validation-provider v-slot="{ errors }" name="column">
                        <v-text-field v-model="form.column"
                                      label="column"
                                      :error-messages="errors"
                        />
                    </validation-provider>
                </v-col>
                <v-col cols="12" md="2">
                    <validation-provider v-slot="{ errors }" name="option">
                        <v-text-field v-model="form.option"
                                      label="option"
                                      :error-messages="errors"
                        />
                    </validation-provider>
                </v-col>
                <v-col cols="12" md="2">
                    <validation-provider v-slot="{ errors }" name="label">
                        <v-text-field v-model="form.label"
                                      label="label"
                                      :error-messages="errors"
                        />
                    </validation-provider>
                </v-col>
                <v-col cols="12" md="2">
                    <v-btn @click="createOption"
                           :loading="loading"
                    >SUBMIT</v-btn>
                </v-col>
            </v-row>
        </v-form>
    </ValidationObserver>
</template>

<script>
    import axios from "axios"

    export default {
        name: "create",
        data(){
            return {
                form: {
                    table: '',
                    column: '',
                    option: '',
                    label: '',
                },
                loading: false,
            }
        },
        methods: {
            async createOption(){
                this.loading = true;

                await axios.post('//'+this.$routeList('admin.api.options.create'),this.form)
                    .then(() => {
                        console.log("SDF")
                    })
                    .catch( error => {

                        console.log(error);

                        if(error.response.status === 422){
                            console.log(this.$refs.observer)
                            this.$refs.observer.setErrors(error.response.data.errors)
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            }
        }
    }
</script>
