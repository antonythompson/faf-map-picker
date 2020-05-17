
/**
 * A setter getter for vuex, it's simple and only works with specific situations.
 * Use on a computed value
 * @param string state_getter used to retrieve the getter in (this.$store.getters[state_getter])
 * @param string mutation used to commit changes with (this.$store.commit(mutation, value))
 * @returns {*}
 * @constructor
 */
function getterSetter(state_getter, mutation){
    return {
        get(){
            return this.$store.getters[state_getter];
        },
        set(value){
            return this.$store.commit(mutation, value);
        }
    }
}
export {getterSetter};