<template>
    <div class="permissions">
        <div class="content">
            <div id="data" class="tab">
                <h4 v-if="selectedMethod!=''"> <b>{{selectedMethod}}</b> permissions </h4>

                <template v-if="!selectedMethod">
                    <p>No class selected!</p>
                </template>

                <template v-else>
                    <b-form>
                        <b-table
                            striped
                            show-empty
                            :items="items"
                            :fields="fields"
                            empty-text="No records found!"
                            head-variant="dark"
                            table-hover
                            :sort-by.sync="sortBy"
                            :sort-desc.sync="sortDesc"
                            :busy.sync="isBusy"
                        >
                            <template v-slot:cell(granted)="row">
                                <b-form-checkbox :value="1" :unchecked-value="0" @change="tooglePermission(row.item)" v-model="row.item.granted"></b-form-checkbox>
                            </template>
                        </b-table>
                    </b-form>
                </template>
            </div>
        </div>
    </div>

</template>

<script>

export default {
    name: "Permissions",
    data() {
        return {
            selectedMethod: '',
            items: [],
            isBusy: false,
            sortBy: 'role_name',
            sortDesc: false,
            fields: [
                { key: 'granted', sortable: true },
                { key: 'role_id', sortable: true },
                { key: 'role_name', sortable: true }
            ],
        }
    },
    methods: {
        getPermissions() {
            this.items = [];
            var self = this;

            this.$http.get('/permissions-users/' + this.selectedMethod.split('\\').join('.'))
            .then(resp => {
                self.items = resp.data.items;
            })
            .catch(err => {
                console.log(err);
            })
            .finally(function(){
                self.sortBy = 'granted';
                self.sortDesc = true;
            });
        },

        tooglePermission(row){
            this.isBusy = true;

            var sendValues = {};

            if (row.meta_object_uuid !== null) {
                this.action = "delete";

                var url = 'acl-permission/' + row.meta_object_uuid;
            } else {
                this.action = "post";

                var url = 'acl-permission';

                sendValues.role_id = row.role_id;

                var spl = this.selectedMethod.split("::");
                sendValues.class_name = spl[0];
                sendValues.action_name = spl[1];
            }

            var self = this;

            this.$http({
                method: this.action,
                url: url,
                data: this.$stringify(sendValues)
            })
            .catch(err => {
                console.log(err);
            })
            .finally(function(){
                self.getPermissions()
                self.isBusy = false;
            });
        },

    },
    props: {
        contentArgs: {}
    },
    watch:{
        contentArgs: {
            handler: function(value) {
                this.selectedMethod = value.selectedMethod;
                this.getPermissions();
            }
        }
    },
    mounted() {
        this.selectedMethod = this.contentArgs.selectedMethod;
        this.getPermissions();
    }
};

</script>

<style>
.content {
    height: 100vh;
    top: 64px;
}

.tab {
    float: left;
    height: 100%;
    overflow: none;
    padding: 20px;
}

#sidebar{
    font-size: 10pt;
    border-width: 0 5px 0 0;
    border-style: solid;
    width: 30%;
    text-align: left;
}

#data {
    width: 65%;
    font-size: 10pt;
}

li {
    cursor: pointer;
}

.btn {
    width: 100%;
}

tr:hover{
    background-color: #ddd !important;
}

th:hover{
    background-color: #000 !important;
}
</style>
