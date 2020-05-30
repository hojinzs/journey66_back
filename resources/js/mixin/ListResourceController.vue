<script>
    import EditWatcher from "../plugins/editWatcher";

    const ajaxHandler = function(){
        this._status = 'pending'; // ['pending', 'sending', 'error']
        this._errorText = null;
    };
    ajaxHandler.prototype.setSending = function(){
        console.log("$AJAX => Before Send");
        this._status = 'sending'
    };
    ajaxHandler.prototype.setError = function(errorText){
        console.log("$AJAX => Send Error");
        this._status = 'error';
        this._errorText = errorText;
        setTimeout(() => this._status = 'pending', 2000)
    }

    export default {
        name: 'ListResourceController',
        data(){
            return {
                LRC_originArray: [],
                LRC_editableArray: [],
                LRC_itemModel: {},
            }
        },
        computed: {
            LRC_changedCount: function(){
                let list = this.LRC_editableArray.filter( item => {
                    return !(item.$mode === 'update' && item.$changed === false);
                })
                return list.length
            }
        },
        methods: {
            LRC_initialize(itemModel, dataArray){
                this.LRC_itemModel = JSON.parse(JSON.stringify(itemModel));
                this.LRC_originArray = dataArray;

                this.LRC_editableArray = this.LRC_originArray.map( item => {
                    let editable = new EditWatcher(item);
                    editable.$mode = 'update';
                    editable.$ajax = new ajaxHandler;
                    return editable
                })

                return this.LRC_editableArray;
            },
            LRC_insertItem(){
                let newModel = new EditWatcher(JSON.parse(JSON.stringify(this.LRC_itemModel)))
                newModel.$mode = 'insert';
                newModel.$ajax = new ajaxHandler;
                this.LRC_editableArray.push(newModel)
            },
            /**
             * Restore Item
             *
             * @param item Number | EditWatcher
             * @constructor
             */
            LRC_restoreItem(item){
                if(typeof item === 'number'){
                    this.LRC_editableArray[item].restoreChanges();
                } else {
                    item.restoreChanges();
                }
            },
            LRC_deleteItem(item){
                let index = this.LRC_editableArray.indexOf(item);
                switch (item.$mode) {
                    case "insert":
                        if(index > -1){
                            this.LRC_editableArray.splice(index, 1)
                        }
                        break;

                    case "update":
                        item.$mode = 'delete';
                        break;

                    case "delete":
                        item.$mode = 'update';
                        break;
                }
            },
            LRC_destroyItem(item){
                let index = this.LRC_editableArray.indexOf(item);
                this.LRC_editableArray.splice(index, 1);
            },
            /**
             * updateOriginData
             *
             * @param item EditWatcher | Object
             * @param newOrigin
             * @constructor
             */
            LRC_updateOrigin(item, newOrigin){
                if(item.$mode === 'update'){
                    item.updateOrigin(newOrigin, true)
                }
                if(item.$mode === 'insert'){
                    let inserter = new EditWatcher(newOrigin);
                    inserter.$mode = 'update'
                    inserter.$ajax = new ajaxHandler;

                    this.LRC_destroyItem(item)
                    this.LRC_editableArray.push(inserter)
                }
            }
        },
    }
</script>
