const EditWatcher = class {
    constructor(originObject) {
        this.$origin = originObject
        this.$mutated = {}
        this.$properties = []
        this.$changed = false

        this._setProperties()

        return this
    }

    setMode(mode){
        // 향후 update, insert 검증 추가
        this.$mode = mode
        return this
    }

    get mode(){
        return this.$mode
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

        if(this.$mutated[property] === this.$origin[property]){
            return false
        } else {
            return true
        }
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
