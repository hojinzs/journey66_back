const EditWatcher = class {
    constructor(originObject) {
        this.$origin = originObject
        this.$mutated = {}
        this.$properties = []
        this.$changed = false

        this._setProperties()

        return this
    }

    _setProperties(){
        this.$properties = Object.keys(this.$origin)
        this.$properties.forEach( key => {
            this.$mutated[key] = this.$origin[key]

            Object.defineProperty(this, key, {
                set: value => {
                    this.$mutated[key] = value;

                    let changed = this._getPropertiesChanged()

                    this.$changed = changed.length !== 0;
                },
                get: () => {
                    return this.$mutated[key]
                }
            })
        })
    }

    _getPropertiesChanged(){
        let changed = [];
        this.$properties.forEach( property => {
            if(this.$mutated[property] !== this.$origin[property]){
                changed.push(property)
            }
        });
        return changed;
    }

    restoreChanges(){
        this.$properties.forEach( key => this.$mutated[key] = this.$origin[key] );
        this.$changed = false
    }

    updateOrigin(newOrigin, restore = true){
        this.$properties.forEach( key => {
            if(newOrigin.hasOwnProperty(key)){
                this.$origin[key] = newOrigin[key]
            } else {
                this.$origin[key] = null
            }
        });

        if(restore){
            this.restoreChanges()
        }
    }

    isChanged(property){
        if(!this.hasOwnProperty(property)){
            return undefined
        }

        return this.$mutated[property] !== this.$origin[property];
    }
}

/**
 * 미완성 (작업 필요)
 * @type {EditWatcherHtml}
 */
const EditWatcherHtml = class extends EditWatcher{

    /**
     * 반응형 Element 세팅하기
     *
     * @param property
     * @param mutationElement
     * @param props
     */
    setListenElement(
        property,
        mutationElement,
        props = {
            classes: []
        }
    ){
        if(!this.hasOwnProperty(property)){
            throw new Error('cannot find '+property+' in Object');
        }

        console.log(mutationElement);

        mutationElement.addEventListener('change', event => {
            super[property] = event.target.value
        })
    }
}

export default EditWatcher
export { EditWatcher, EditWatcherHtml }
