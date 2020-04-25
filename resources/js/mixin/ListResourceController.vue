<script>
    import EditWatcher from "../plugins/editWatcher";

    export default {
        name: 'ListResourceController',
        data(){
            return {
                LRC_originArray: [],
                LRC_editableArray: [],
                LRC_itemModel: {},
            }
        },
        methods: {
            LRC_initialize(itemModel, dataArray){
                this.LRC_itemModel = JSON.parse(JSON.stringify(itemModel));
                this.LRC_originArray = dataArray;

                this.LRC_originArray.forEach( item => {
                    let editable = new EditWatcher(item);
                    editable.$mode = 'update';
                    this.LRC_editableArray.push(editable)
                })

                return this.LRC_editableArray;
            },
            LRC_insertItem(){
                let newModel = JSON.parse(JSON.stringify(this.LRC_itemModel))
                newModel.$mode = 'insert'
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
             * @constructor
             */
            LRC_updateOrigin(item, newData){
                if(item.$mode === 'update'){
                    item.updateOrigin(newData, true)
                }
                if(item.$mode === 'insert'){
                    let inserter = new EditWatcher(newData);
                    inserter.$mode = 'update'
                    console.log(inserter)
                    this.LRC_destroyItem(item)
                    this.LRC_editableArray.push(inserter)
                }
            }
        },
    }
</script>
