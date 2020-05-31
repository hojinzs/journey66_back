/**
 * # Validation References
 *
 * vuetify.form.veeValidation :: https://vuetifyjs.com/en/components/forms/#vee-validate
 * veeValidation.handlingForm :: https://logaretm.github.io/vee-validate/guide/forms.html
 */

import Vue from 'vue'
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate'

export default (() => {
    Vue.component('ValidationProvider', ValidationProvider);
    Vue.component('ValidationObserver', ValidationObserver);
})()
