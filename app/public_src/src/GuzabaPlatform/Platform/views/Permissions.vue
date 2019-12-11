<template>
    <div class="permissions">
        <div class="content">
            <div id="data" class="tab">
                <h3 v-if="selectedMethod!=''"> {{selectedMethod}} permissions <b-button variant="success" @click="showModal('post', newObject)" size="sm">Add New Role</b-button> </h3>

                <template v-if="!selectedMethod">
                    <p>No class selected!</p>
                </template>

                <template v-else>
                    <b-form>
                        <b-table striped show-empty :items="items" :fields="fields" empty-text="No records found!" @row-clicked="rowClickHandler"  no-local-sorting @sort-changed="sortingChanged" head-variant="dark" table-hover>

                            <template slot="top-row" slot-scope="{ fields }">
                                <td v-for="field in fields">
                                    <template v-if="field.key=='meta_object_uuid'">
                                        <b-button size="sm" variant="outline-primary" type="submit" @click="search()">Search</b-button>
                                    </template>

                                    <template v-else>
                                        <b-form-input v-model="searchValues[field.key]" type="search" :placeholder="field.label"></b-form-input>
                                    </template>
                                </td>
                            </template>

                            <template v-slot:cell(meta_object_uuid)="row">
                                  <b-button size="sm" variant="outline-danger" v-on:click.stop="" @click="showModal('delete', row.item)">Delete</b-button>
                            </template>

                        </b-table>
                    </b-form>
                </template>

                <b-pagination v-if="totalItems > limit" size="md" :total-rows="totalItems" v-model="currentPage" :per-page="limit"  align="center"></b-pagination>

                <b-modal
                  id="crud-modal"
                  :title="modalTitle"
                  :header-bg-variant="modalVariant"
                  header-text-variant="light"
                  body-bg-variant="light"
                  body-text-variant="dark"
                  :ok-title="ButtonTitle"
                  :ok-variant="ButtonVariant"
                  centered
                  @ok="proceedAction($event)"
                  :cancel-disabled="actionState"
                  :ok-disabled="loadingState"
                  :ok-only="actionState && !loadingState"
                  size="lg"
                >
                    <template v-if="!actionState">
                        <p>{{actionTitle}}</p>

                        <b-form-group class="form-group" v-for="(value, index) in putValues" v-if="index!='meta_object_uuid'" :label="index" label-align="right" label-cols="3">

                            <template v-if="action=='delete'">
                                <b-form-input :value="value" disabled></b-form-input>
                            </template>

                            <template v-else>
                                <b-form-input v-model="putValues[index]"></b-form-input>
                            </template>

                        </b-form-group>
                    </template>

                    <template v-else>
                        <p v-if="loadingState">
                            {{loadingMessage}}
                            ...
                        </p>
                        <p v-else>
                            <template v-if="requestError == ''">
                                {{successfulMessage}}
                            </template>
                            <template v-else>
                                The operation can not be performed due to an error:<br />
                                {{requestError}}
                            </template>
                        </p>
                    </template>
                </b-modal>
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
            putValues: [],
            actionTitle: '',
            loadingState: '',
            actionState: '',
            ButtonVariant: '',
            ButtonTitle: '',
            modalVariant: '',
            limit: '',
            modalTitle: '',
            totalItems: '',
            selectedClassName: '',
        }
    },
    methods: {
        getPermissions(className) {
            this.$http.get('/permissions-users/' + className)
            .then(resp => {
                console.log(resp.data);
            })
            .catch(err => {
                console.log(err);
            });
        }
    },
    props: {
        contentArgs: {}
    },
    watch:{
        contentArgs: {
            handler: function(value) {
                this.getPermissions(value.selectedMethod);
            }
        }
    },
    mounted() {
        this.selectedMethod = this.contentArgs.selectedMethod;
        this.getPermissions(this.selectedMethod);
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

tr {
    cursor: pointer;
}
</style>
