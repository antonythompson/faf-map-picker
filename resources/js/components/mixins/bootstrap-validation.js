export default {
    methods: {
        fieldState(field){
            if (this.errors && this.errors[field]) {
                return false;
            }
            return null;
        },
        invalidField(field){
            if (this.errors && this.errors[field]) {
                return this.errors[field].join('<br />');
            }
            return '';
        },
    }
}